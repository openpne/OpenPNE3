<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/**
 * Implements the OAuth dance (http://oauth.net/core/1.0/) for gadgets.
 *
 * Reading the example in the appendix to the OAuth spec will be helpful to
 * those reading this code.
 *
 * This class is not thread-safe; create a new one for each request that
 * requires OAuth signing.
 */
class OAuthFetcher extends RemoteContentFetcher {

  // We store some blobs of data on the client for later reuse; the blobs
  // contain key/value pairs, and these are the key names.
  private static $REQ_TOKEN_KEY = "r";
  private static $REQ_TOKEN_SECRET_KEY = "rs";
  private static $ACCESS_TOKEN_KEY = "a";
  private static $ACCESS_TOKEN_SECRET_KEY = "as";
  private static $OWNER_KEY = "o";

  // names for the JSON values we return to the client
  public static $CLIENT_STATE = "oauthState";
  public static $APPROVAL_URL = "oauthApprovalUrl";
  public static $ERROR_CODE = "oauthError";
  public static $ERROR_TEXT = "oauthErrorText";
  // names of additional OAuth parameters we include in outgoing requests
  public static $XOAUTH_APP_URL = "xoauth_app_url";

  /**
   * @var RemoteContentFetcher
   */
  private $fetcher;

  /**
   * Maximum age for our client state; if this is exceeded we start over. One
   * hour is a fairly arbitrary time limit here.
   */
  private static $CLIENT_STATE_MAX_AGE_SECS = 3600;

  /**
   * The gadget security token, with info about owner/viewer/gadget.
   */
  protected $authToken;

  /**
   * Parameters from makeRequest
   * @var OAuthRequestParams
   */
  protected $requestParams;

  /**
   * Reference to our persistent store for OAuth metadata.
   */
  protected $tokenStore;

  /**
   * The accessor we use for signing messages. This also holds metadata about
   * the service provider, such as their URLs and the keys we use to access
   * those URLs.
   * @var AccesorInfo
   */
  private $accessorInfo;

  /**
   * We use this to encrypt and sign the state we cache on the client.
   */
  private $oauthCrypter;

  /**
   * State the client sent with their request.
   */
  private $origClientState = array();

  /**
   * The request the client really wants to make.
   * @var RemoteContentRequest
   */
  private $realRequest;

  /**
   * State to cache on the client.
   */
  private $newClientState;

  /**
   * Authorization URL for the client
   */
  private $aznUrl;

  /**
   * Error code for the client
   */
  private $error;

  /**
   * Error text for the client
   */
  private $errorText;

  /**
   * Whether or not we're supposed to ignore the spec cache when referring
   * to the gadget spec for information (e.g. OAuth URLs).
   */
  private $bypassSpecCache;

  private $responseMetadata = array();

  /**
   *
   * @param oauthCrypter used to encrypt transient information we store on the
   *        client.
   * @param authToken user's gadget security token
   * @param params OAuth fetch parameters sent from makeRequest
   * @param tokenStore storage for long lived tokens.
   */
  public function __construct($tokenStore, $oauthCrypter, $fetcher, $authToken, OAuthRequestParams $params) {
    $this->fetcher = $fetcher;
    $this->oauthCrypter = $oauthCrypter;
    $this->authToken = $authToken;
    $this->bypassSpecCache = $params->getBypassSpecCache();
    $this->requestParams = $params;
    $this->newClientState = null;
    $this->aznUrl = null;
    $this->error = null;
    $this->errorText = null;
    $origClientState = $params->getOrigClientState();
    if ($origClientState != null && strlen($origClientState) > 0) {
      try {
        $this->origClientState = $this->oauthCrypter->unwrap($origClientState, self::$CLIENT_STATE_MAX_AGE_SECS);
      } catch (BlobCrypterException $e) {// Probably too old, pretend we never saw it at all.
}
    }
    if ($this->origClientState == null) {
      $this->origClientState = array();
    }
    $this->tokenStore = $tokenStore;
  }

  private function buildErrorResponse(Exception $e) {
    if ($this->error == null) {
      $this->error = OAuthError::$UNKNOWN_PROBLEM;
    }
    // Take a giant leap of faith and assume that the exception message
    // will be useful to a gadget developer.  Also include the exception
    // stack trace, in case the problem report makes it to someone who knows
    // enough to do something useful with the stack.
    $errorBuf = '';
    $errorBuf .= $e->getMessage();
    $errorBuf .= "\n\n";
    $this->errorText = $errorBuf;
    return $this->buildNonDataResponse();
  }

  /**
   * @return RemoteContentRequest
   */
  private function buildNonDataResponse() {
    $response = new RemoteContentRequest($this->realRequest->getUrl());
    $this->addResponseMetadata($response);
    self::setStrictNoCache($response);
    return $response;
  }

  /**
   * Retrieves metadata from our persistent store.
   *
   * @throws GadgetException
   */
  protected function lookupOAuthMetadata() {
    $tokenKey = $this->buildTokenKey();
    $this->accessorInfo = $this->tokenStore->getOAuthAccessor($tokenKey, $this->bypassSpecCache);
    // The persistent data store may be out of sync with reality; we trust
    // the state we stored on the client to be accurate.
    $accessor = $this->accessorInfo->getAccessor();
    if (isset($this->origClientState[self::$REQ_TOKEN_KEY])) {
      $accessor->requestToken = $this->origClientState[self::$REQ_TOKEN_KEY];
      $accessor->tokenSecret = $this->origClientState[self::$REQ_TOKEN_SECRET_KEY];
    } else if (isset($this->origClientState[self::$ACCESS_TOKEN_KEY])) {
      $accessor->accessToken = $this->origClientState[self::$ACCESS_TOKEN_KEY];
      $accessor->tokenSecret = $this->origClientState[self::$ACCESS_TOKEN_SECRET_KEY];
    } else if ($accessor->accessToken == null && $this->requestParams->getRequestToken() != null) {
      // We don't have an access token yet, but the client sent us a
      // (hopefully) preapproved request token.
      $accessor->requestToken = $this->requestParams->getRequestToken();
      $accessor->tokenSecret = $this->requestParams->getRequestTokenSecret();
    }
  }

  private function buildTokenKey() {
    $tokenKey = new TokenKey();
    // need to URLDecode so when comparing with the ProviderKey it goes thought
    $tokenKey->setGadgetUri(urldecode($this->authToken->getAppUrl()));
    $tokenKey->setModuleId($this->authToken->getModuleId());
    $tokenKey->setServiceName($this->requestParams->getServiceName());
    $tokenKey->setTokenName($this->requestParams->getTokenName());
    // At some point we might want to let gadgets specify whether to use OAuth
    // for the owner, the viewer, or someone else. For now always using the
    // owner identity seems reasonable.
    $tokenKey->setUserId($this->authToken->getOwnerId());
    return $tokenKey;
  }

  public function fetch($request) {
    try {
      $this->lookupOAuthMetadata();
    } catch (Exception $e) {
      $this->error = OAuthError::$BAD_OAUTH_CONFIGURATION;
      return $this->buildErrorResponse($e);
    }
    $this->realRequest = $request;
    $response = $this->fetchRequest($request);
    return $response;
  }

  /**
   * @return RemoteContentRequest
   */
  public function fetchRequest(RemoteContentRequest $request) {
    if ($this->needApproval()) {
      // This is section 6.1 of the OAuth spec.
      $this->checkCanApprove();
      $this->fetchRequestToken($request);
      // This is section 6.2 of the OAuth spec.
      $this->buildClientApprovalState();
      $this->buildAznUrl();
      // break out of the content fetching chain, we need permission from
      // the user to do this
      return $this->buildOAuthApprovalResponse();
    } elseif ($this->needAccessToken()) {
      // This is section 6.3 of the OAuth spec
      $this->checkCanApprove();
      $this->exchangeRequestToken($request);
      $this->saveAccessToken();
      $this->buildClientAccessState();
    }
    return $this->fetchData();
  }

  private function buildOAuthApprovalResponse() {
    return $this->buildNonDataResponse();
  }

  /**
   * Do we need to get the user's approval to access the data?
   */
  private function needApproval() {
    if ($this->accessorInfo == NULL) {
      return true;
    } else {
      return ($this->accessorInfo->getAccessor()->requestToken == null && $this->accessorInfo->getAccessor()->accessToken == null);
    }
  }

  /**
   * Make sure the user is authorized to approve access tokens.  At the moment
   * we restrict this to page owner's viewing their own pages.
   *
   * @throws GadgetException
   */
  private function checkCanApprove() {
    $pageOwner = $this->authToken->getOwnerId();
    $pageViewer = $this->authToken->getViewerId();
    $stateOwner = @$this->origClientState[self::$OWNER_KEY];
    if (! $pageOwner) {
      throw new GadgetException('Unauthenticated');
    }
    if ($pageOwner != $pageViewer) {
      throw new GadgetException("Only page owners can grant OAuth approval");
    }
    if ($stateOwner != null && ! $stateOwner == $pageOwner) {
      throw new GadgetException("Client state belongs to a different person.");
    }
  }

  /**
   *
   * @throws GadgetException
   */
  private function fetchRequestToken(RemoteContentRequest $request) {
    try {
      $accessor = $this->accessorInfo->getAccessor();
      //TODO The implementations of oauth differs from the one in JAVA. Fix the type OAuthMessage
      $url = $accessor->consumer->callback_url->requestTokenURL;
      $msgParams = array();
      self::addIdentityParams($msgParams, $request->getToken());
      $request = $this->newRequestMessageParams($url->url, $msgParams);
      $reply = $this->sendOAuthMessage($request);
      $reply->requireParameters(array(OAuth::$OAUTH_TOKEN, OAuth::$OAUTH_TOKEN_SECRET));
      $accessor->requestToken = $reply->get_parameter(OAuth::$OAUTH_TOKEN);
      $accessor->tokenSecret = $reply->get_parameter(OAuth::$OAUTH_TOKEN_SECRET);
    } catch (Exception $e) {
      // It's unfortunate the OAuth libraries throw a generic Exception.
      throw new GadgetException($e);
    }
  }

  /**
   * @return OAuthRequest
   */
  private function newRequestMessageMethod($method, $url, $params) {
    if (! isset($params)) {
      throw new Exception("params was null in " . "newRequestMessage " . "Use newRequesMessage if you don't have a params to pass");
    }
    switch ($this->accessorInfo->getSignatureType()) {
      case OAuth::$RSA_SHA1:
        $params[OAuth::$OAUTH_SIGNATURE_METHOD] = OAuth::$RSA_SHA1;
        break;
      case "PLAINTEXT":
        $params[OAuth::$OAUTH_SIGNATURE_METHOD] = "PLAINTEXT";
        break;
      default:
        $params[OAuth::$OAUTH_SIGNATURE_METHOD] = OAuth::$HMAC_SHA1;
    }
    $accessor = $this->accessorInfo->getAccessor();
    return $accessor->newRequestMessage($method, $url, $params);
  }

  /*
   * @deprecated (All outgoing messages must send additional params
   * like XOAUTH_APP_URL, so use newRequestMessageParams instead)
   */
  private function newRequestMessageUrlOnly($url) {
    $params = array();
    return $this->newRequestMessageParams($url, $params);
  }

  /**
   * @return OAuthRequest
   */
  private function newRequestMessageParams($url, $params) {
    $method = "POST";
    if ($this->accessorInfo->getHttpMethod() == OAuthStoreVars::$HttpMethod['GET']) {
      $method = "GET";
    }
    return $this->newRequestMessageMethod($method, $url, $params);
  }

  private function newRequestMessage($url = null, $method = null, $params = null) {
    if (isset($method) && isset($url) && isset($params)) {
      return $this->newRequestMessageMethod($method, $url, $params);
    } else if (isset($url) && isset($params)) {
      return $this->newRequestMessageParams($url, $params);
    } else if (isset($url)) {
      return $this->newRequestMessageUrlOnly($url);
    }
  }

  private function getAuthorizationHeader($oauthParams) {
    $result = "OAuth ";
    $first = true;
    foreach ($oauthParams as $key => $val) {
      if (! $first) {
        $result .= ", ";
      } else {
        $first = false;
      }
      $result .= OAuthUtil::urlencodeRFC3986($key) . "=\"" . OAuthUtil::urlencodeRFC3986($val) . '"';
    }
    return $result;
  }

  /**
   * @return RemoteContentRequest
   */
  private function createRemoteContentRequest($oauthParams, $method, $url, $headers, $contentType, $postBody, $options) {
    $paramLocation = $this->accessorInfo->getParamLocation();
    $newHeaders = array();
    // paramLocation could be overriden by a run-time parameter to fetchRequest
    switch ($paramLocation) {
      case OAuthStoreVars::$OAuthParamLocation['AUTH_HEADER']:
        if ($headers != null) {
          $newHeaders = $headers;
        }
        $authHeader = array();
        $authHeader = $this->getAuthorizationHeader($oauthParams);
        $newHeaders["Authorization"] = $authHeader;
        break;

      case OAuthStoreVars::$OAuthParamLocation['POST_BODY']:
        if (! OAuthUtil::isFormEncoded($contentType)) {
          throw new GadgetException("Invalid param: OAuth param location can only " . "be post_body if post body if of type x-www-form-urlencoded");
        }
        if (! isset($postBody) || count($postBody) == 0) {
          $postBody = OAuthUtil::getPostBodyString($oauthParams);
        } else {
          $postBody = $postBody . "&" . OAuthUtil::getPostBodyString($oauthParams);
        }
        break;

      case OAuthStoreVars::$OAuthParamLocation['URI_QUERY']:
        $url = OAuthUtil::addParameters($url, $oauthParams);
        break;
    }
    $postBodyBytes = ($postBody == null) ? null : null; //$postBody->getBytes("UTF-8"); //See what can we do with this?
    $rcr = new RemoteContentRequest($url);
    $rcr->createRemoteContentRequest($method, $url, $newHeaders, $postBodyBytes, $options);
    return $rcr;
  }

  /**
   * Sends OAuth request token and access token messages.
   */
  private function sendOAuthMessage(OAuthRequest $request) {
    $rcr = $this->createRemoteContentRequest($this->filterOAuthParams($request), $request->get_normalized_http_method(), $request->get_url(), null, RemoteContentRequest::$DEFAULT_CONTENT_TYPE, null, RemoteContentRequest::getDefaultOptions());
    $rcr->setToken($this->authToken);
    $fetcher = new BasicRemoteContentFetcher();
    $content = $fetcher->fetchRequest($rcr);
    $reply = OAuthRequest::from_request();
    $params = OAuthUtil::decodeForm($content->getResponseContent());
    $reply->set_parameters($params);
    return $reply;
  }

  /**
   * Builds the data we'll cache on the client while we wait for approval.
   */
  private function buildClientApprovalState() {
    try {
      $accessor = $this->accessorInfo->getAccessor();
      $oauthState = array();
      $oauthState[self::$REQ_TOKEN_KEY] = $accessor->requestToken;
      $oauthState[self::$REQ_TOKEN_SECRET_KEY] = $accessor->tokenSecret;
      $oauthState[self::$OWNER_KEY] = $this->authToken->getOwnerId();
      $this->newClientState = $this->oauthCrypter->wrap($oauthState);
    } catch (BlobCrypterException $e) {
      throw new GadgetException("INTERNAL SERVER ERROR: " . $e);
    }
  }

  /**
   * Builds the URL the client needs to visit to approve access.
   */
  private function buildAznUrl() {
    // At some point we can be clever and use a callback URL to improve
    // the user experience, but that's too complex for now.
    $accessor = $this->accessorInfo->getAccessor();
    $azn = $accessor->consumer->callback_url->userAuthorizationURL;
    $authUrl = $azn->url;
    if (strstr($authUrl, "?") == FALSE) {
      $authUrl .= "?";
    } else {
      $authUrl .= "&";
    }
    $authUrl .= OAuth::$OAUTH_TOKEN;
    $authUrl .= "=";
    $authUrl .= OAuthUtil::urlencodeRFC3986($accessor->requestToken);
    $this->aznUrl = $authUrl;
  }

  /**
   * Do we need to exchange a request token for an access token?
   */
  private function needAccessToken() {
    return ($this->accessorInfo->getAccessor()->requestToken != null && $this->accessorInfo->getAccessor()->accessToken == null);
  }

  /**
   * Implements section 6.3 of the OAuth spec.
   */
  private function exchangeRequestToken(RemoteContentRequest $request) {
    try {
      $accessor = $this->accessorInfo->getAccessor();
      $url = $accessor->consumer->callback_url->accessTokenURL;
      $msgParams = array();
      $msgParams[OAuth::$OAUTH_TOKEN] = $accessor->requestToken;
      self::addIdentityParams($msgParams, $request->getToken());
      $request = $this->newRequestMessageParams($url->url, $msgParams);
      $reply = $this->sendOAuthMessage($request);
      $reply->requireParameters(array(OAuth::$OAUTH_TOKEN, OAuth::$OAUTH_TOKEN_SECRET));
      $accessor->accessToken = $reply->get_parameter(OAuth::$OAUTH_TOKEN);
      $accessor->tokenSecret = $reply->get_parameter(OAuth::$OAUTH_TOKEN_SECRET);
    } catch (Exception $e) {
      // It's unfortunate the OAuth libraries throw a generic Exception.
      throw new GadgetException("INTERNAL SERVER ERROR: " . $e);
    }
  }

  /**
   * Save off our new token and secret to the persistent store.
   *
   * @throws GadgetException
   */
  private function saveAccessToken() {
    $accessor = $this->accessorInfo->getAccessor();
    $tokenKey = $this->buildTokenKey();
    $tokenInfo = new TokenInfo($accessor->accessToken, $accessor->tokenSecret);
    $this->tokenStore->storeTokenKeyAndSecret($tokenKey, $tokenInfo);
  }

  /**
   * Builds the data we'll cache on the client while we make requests.
   */
  private function buildClientAccessState() {
    try {
      $oauthState = array();
      $accessor = $this->accessorInfo->getAccessor();
      $oauthState[self::$ACCESS_TOKEN_KEY] = $accessor->accessToken;
      $oauthState[self::$ACCESS_TOKEN_SECRET_KEY] = $accessor->tokenSecret;
      $oauthState[self::$OWNER_KEY] = $this->authToken->getOwnerId();
      $this->newClientState = $this->oauthCrypter->wrap($oauthState);
    } catch (BlobCrypterException $e) {
      throw new GadgetException("INTERNAL SERVER ERROR: " . $e);
    }
  }

  /**
   * Get honest-to-goodness user data.
   */
  private function fetchData() {
    try {
      $msgParams = OAuthUtil::isFormEncoded($this->realRequest->getContentType()) ? OAuthUtil::urldecodeRFC3986($this->realRequest->getPostBody()) : array();
      $method = $this->realRequest->getMethod();
      $msgParams[self::$XOAUTH_APP_URL] = $this->authToken->getAppUrl();
      // Build and sign the message.
      $oauthRequest = $this->newRequestMessageMethod($method, $this->realRequest->getUrl(), $msgParams);
      $rcr = $this->createRemoteContentRequest($this->filterOAuthParams($oauthRequest), $this->realRequest->getMethod(), $this->realRequest->getUrl(), $this->realRequest->getHeaders(), $this->realRequest->getContentType(), $this->realRequest->getPostBody(), $this->realRequest->getOptions());
      //TODO is there a better way to detect an SP error?
      $fetcher = new BasicRemoteContentFetcher();
      $content = $fetcher->fetchRequest($rcr);
      $statusCode = $content->getHttpCode();
      if ($statusCode >= 400 && $statusCode < 500) {
        $message = $this->parseAuthHeader(null, $content);
        if ($message->get_parameter(OAuth::$OAUTH_PROBLEM) != null) {
          throw new OAuthProtocolException($message);
        }
      }
      // Track metadata on the response
      $this->addResponseMetadata($content);
      return $content;
    } catch (Exception $e) {
      throw new GadgetException("INTERNAL SERVER ERROR: " . $e);
    }
  }

  /**
   * Parse OAuth WWW-Authenticate header and either add them to an existing
   * message or create a new message.
   *
   * @param msg
   * @param resp
   * @return the updated message.
   */
  private function parseAuthHeader(OAuthRequest $msg = null, RemoteContentRequest $resp) {
    if ($msg == null) {
      $msg = OAuthRequest::from_request();
    }
    $authHeaders = $resp->getResponseHeader("WWW-Authenticate");
    if ($authHeaders != null) {
      $msg->set_parameters(OAuthUtil::decodeAuthorization($authHeaders));
    }
    return $msg;
  }

  /**
   * Extracts only those parameters from an OAuthMessage that are OAuth-related.
   * An OAuthMessage may hold a whole bunch of non-OAuth-related parameters
   * because they were all needed for signing. But when constructing a request
   * we need to be able to extract just the OAuth-related parameters because
   * they, and only they, may have to be put into an Authorization: header or
   * some such thing.
   *
   * @param message the OAuthMessage object, which holds non-OAuth parameters
   * such as foo=bar (which may have been in the original URI query part, or
   * perhaps in the POST body), as well as OAuth-related parameters (such as
   * oauth_timestamp or oauth_signature).
   *
   * @return a list that contains only the oauth_related parameters.
   *
   * @throws IOException
   */
  private function filterOAuthParams($message) {
    $result = array();
    foreach ($message->get_parameters() as $key => $value) {
      if (strstr(strtolower($key), "oauth") != - 1 || strstr(strtolower($key), "xoauth") != - 1) {
        $result[$key] = $value;
      }
    }
    return $result;
  }

  public function getResponseMetadata() {
    return $this->responseMetadata;
  }

  /**
   * @var RemoteContentRequest $response
   */
  public function addResponseMetadata(RemoteContentRequest $response) {
    $response->setHttpCode(200);
    if ($this->newClientState != null) {
      $this->responseMetadata[self::$CLIENT_STATE] = $this->newClientState;
      $response->setMetadata(self::$CLIENT_STATE, $this->newClientState);
    }
    if ($this->aznUrl != null) {
      $this->responseMetadata[self::$APPROVAL_URL] = $this->aznUrl;
      $response->setMetadata(self::$APPROVAL_URL, $this->aznUrl);
    }
    if ($this->error != null) {
      $this->responseMetadata[self::$ERROR_CODE] = $this->error;
      $response->setMetadata(self::$ERROR_CODE, $this->error);
    }
    if ($this->errorText != null) {
      $this->responseMetadata[self::$ERROR_TEXT] = $this->errorText;
      $response->setMetadata(self::$ERROR_TEXT, $this->errorText);
    }
  }

  public function multiFetchRequest(Array $requests) {// Do nothing
}

  private static function addIdentityParams(array & $params, SecurityToken $token) {
    $params['opensocial_owner_id'] = $token->getOwnerId();
    $params['opensocial_viewer_id'] = $token->getViewerId();
    $params['opensocial_app_id'] = $token->getAppId();
    $params['opensocial_app_url'] = $token->getAppUrl();
  }

  private static function setStrictNoCache(RemoteContentRequest $response) {
    $response->setResponseHeader('Pragma', 'no-cache');
    $response->setResponseHeader('Cache-Control', 'no-cache');
  }
}
