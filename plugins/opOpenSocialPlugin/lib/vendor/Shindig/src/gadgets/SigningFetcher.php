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
 * Implements signed fetch based on the OAuth request signing algorithm.
 *
 * Subclasses can override signMessage to use their own crypto if they don't
 * like the oauth.net code for some reason.
 *
 * Instances of this class are only accessed by a single thread at a time,
 * but instances may be created by multiple threads.
 */
class SigningFetcher extends RemoteContentFetcher {

  protected static $OPENSOCIAL_OWNERID = "opensocial_owner_id";
  protected static $OPENSOCIAL_VIEWERID = "opensocial_viewer_id";
  protected static $OPENSOCIAL_APPID = "opensocial_app_id";
  protected static $OPENSOCIAL_APPURL = "opensocial_app_url";
  protected static $XOAUTH_PUBLIC_KEY = "xoauth_signature_publickey";
  protected static $ALLOWED_PARAM_NAME = '^[-_[:alnum:]]+$';

  /**
   * Private key we pass to the OAuth RSA_SHA1 algorithm.This can be a
   * PrivateKey object, or a PEM formatted private key, or a DER encoded byte
   * array for the private key.(No, really, they accept any of them.)
   */
  protected $privateKeyObject;

  /**
   * The name of the key, included in the fetch to help with key rotation.
   */
  protected $keyName;

  /**
   * @var RemoteContentFetcher
   */
  private $fetcher;

  /**
   * Constructor based on signing with the given PrivateKey object.
   *
   * @param RemoteContentFetcher $fetcher
   * @param keyName name of the key to include in the request
   * @param privateKey the key to use for the signing
   * @return SigningFetcher
   */
  public static function makeFromPrivateKey(RemoteContentFetcher $fetcher, $keyName, $privateKey) {
    return new SigningFetcher($fetcher, $keyName, $privateKey);
  }

  /**
   * Constructor based on signing with the given PrivateKey object.
   *
   * @param RemoteContentFetcher $fetcher
   * @param keyName name of the key to include in the request
   * @param privateKey base64 encoded private key
   * @return SigningFetcher
   */
  public static function makeFromB64PrivateKey(RemoteContentFetcher $fetcher, $keyName, $privateKey) {
    return new SigningFetcher($fetcher, $keyName, $privateKey);
  }

  /**
   * Constructor based on signing with the given PrivateKey object.
   *
   * @param RemoteContentFetcher $fetcher
   * @param keyName name of the key to include in the request
   * @param privateKey DER encoded private key
   * @return SigningFetcher
   */
  public static function makeFromPrivateKeyBytes(RemoteContentFetcher $fetcher, $keyName, $privateKey) {
    return new SigningFetcher($fetcher, $keyName, $privateKey);
  }

  protected function __construct(RemoteContentFetcher $fetcher, $keyName, $privateKeyObject) {
    $this->fetcher = $fetcher;
    $this->keyName = $keyName;
    $this->privateKeyObject = $privateKeyObject;
  }

  public function fetchRequest(RemoteContentRequest $request) {
    $this->signRequest($request);
    return $this->fetcher->fetchRequest($request);
  }

  public function multiFetchRequest(Array $requests) {
    foreach ($requests as $request) {
      $this->signRequest($request);
    }
    return $this->fetcher->multiFetchRequest($requests);
  }

  private function signRequest(RemoteContentRequest $request) {
    $url = $request->getUrl();
    $method = $request->getMethod();
    try {
      // Parse the request into parameters for OAuth signing, stripping out
      // any OAuth or OpenSocial parameters injected by the client
      $parsedUri = parse_url($url);
      $resource = $url;
      $queryParams = array();
      if (isset($parsedUri['query'])) {
        parse_str($parsedUri['query'], $queryParams);
        // strip out all opensocial_* and oauth_* params so they can't be spoofed by the client
        foreach ($queryParams as $key => $val) {
          if ((strtolower(substr($key, 0, strlen('opensocial_'))) == 'opensocial_') || (strtolower(substr($key, 0, strlen('oauth_'))) == 'oauth_')) {
            unset($queryParams[$key]);
          }
        }
        $queryParams = $this->sanitize($queryParams);
      }
      $contentType = $request->getHeader('Content-Type');
      $signBody = (stripos($contentType, 'application/x-www-form-urlencoded') !== false || $contentType == null);
      if ($request->getPostBody()) {
        if ($signBody) {
          $postParams = array();
          // on normal application/x-www-form-urlencoded type post's encode and parse the post vars
          parse_str($request->getPostBody(), $postParams);
          $postParams = $this->sanitize($postParams);
        } else {
          // on any other content-type of post (application/{json,xml,xml+atom}) use the body signing hash
          // see http://oauth.googlecode.com/svn/spec/ext/body_hash/1.0/drafts/4/spec.html for details
          $queryParams['oauth_body_hash'] = base64_encode(sha1($request->getPostBody(), true));
        }
      }
      $msgParams = array();
      $msgParams = array_merge($msgParams, $queryParams);
      if ($signBody && isset($postParams)) {
        $msgParams = array_merge($msgParams, $postParams);
      }
      $this->addOpenSocialParams($msgParams, $request->getToken());
      $this->addOAuthParams($msgParams, $request->getToken());
      $consumer = new OAuthConsumer(NULL, NULL, NULL);
      $consumer->setProperty(OAuthSignatureMethod_RSA_SHA1::$PRIVATE_KEY, $this->privateKeyObject);
      $signatureMethod = new OAuthSignatureMethod_RSA_SHA1();
      $req_req = OAuthRequest::from_consumer_and_token($consumer, NULL, $method, $resource, $msgParams);
      $req_req->sign_request($signatureMethod, $consumer, NULL);
      // Rebuild the query string, including all of the parameters we added.
      // We have to be careful not to copy POST parameters into the query.
      // If post and query parameters share a name, they end up being removed
      // from the query.
      $forPost = array();
      $postData = false;
      if ($method == 'POST' && $signBody) {
        foreach ($postParams as $key => $param) {
          $forPost[$key] = $param;
          if ($postData === false) {
            $postData = array();
          }
          $postData[] = OAuthUtil::urlencodeRFC3986($key) . "=" . OAuthUtil::urlencodeRFC3986($param);
        }
        if ($postData !== false) {
          $postData = implode("&", $postData);
        }
      }
      $newQuery = '';
      foreach ($req_req->get_parameters() as $key => $param) {
        if (! isset($forPost[$key])) {
          $newQuery .= urlencode($key) . '=' . urlencode($param) . '&';
        }
      }
      // and stick on the original query params too
      if (isset($parsedUri['query']) && ! empty($parsedUri['query'])) {
        $oldQuery = array();
        parse_str($parsedUri['query'], $oldQuery);
        foreach ($oldQuery as $key => $val) {
          $newQuery .= urlencode($key) . '=' . urlencode($val) . '&';
        }
      }
      // Careful here; the OAuth form encoding scheme is slightly different than
      // the normal form encoding scheme, so we have to use the OAuth library
      // formEncode method.
      $url = $parsedUri['scheme'] . '://' . $parsedUri['host'] . (isset($parsedUri['port']) ? ':' . $parsedUri['port'] : '') . (isset($parsedUri['path']) ? $parsedUri['path'] : '') . '?' . $newQuery;
      $request->setUri($url);
      if ($signBody) {
        $request->setPostBody($postData);
      }
    } catch (Exception $e) {
      throw new GadgetException($e);
    }
  }

  private function addOpenSocialParams(&$msgParams, SecurityToken $token) {
    $owner = $token->getOwnerId();
    if ($owner != null) {
      $msgParams[SigningFetcher::$OPENSOCIAL_OWNERID] = $owner;
    }
    $viewer = $token->getViewerId();
    if ($viewer != null) {
      $msgParams[SigningFetcher::$OPENSOCIAL_VIEWERID] = $viewer;
    }
    $app = $token->getAppId();
    if ($app != null) {
      $msgParams[SigningFetcher::$OPENSOCIAL_APPID] = $app;
    }
    $url = $token->getAppUrl();
    if ($url != null) {
      $msgParams[SigningFetcher::$OPENSOCIAL_APPURL] = $url;
    }
  }

  private function addOAuthParams(&$msgParams, SecurityToken $token) {
    $msgParams[OAuth::$OAUTH_TOKEN] = '';
    $domain = $token->getDomain();
    if ($domain != null) {
      $msgParams[OAuth::$OAUTH_CONSUMER_KEY] = $domain;
    }
    if ($this->keyName != null) {
      $msgParams[SigningFetcher::$XOAUTH_PUBLIC_KEY] = $this->keyName;
    }
    $nonce = OAuthRequest::generate_nonce();
    $msgParams[OAuth::$OAUTH_NONCE] = $nonce;
    $timestamp = time();
    $msgParams[OAuth::$OAUTH_TIMESTAMP] = $timestamp;
    $msgParams[OAuth::$OAUTH_SIGNATURE_METHOD] = OAuth::$RSA_SHA1;
  }

  /**
   * Strip out any owner or viewer id passed by the client.
   */
  private function sanitize($params) {
    $list = array();
    foreach ($params as $key => $p) {
      if ($this->allowParam($key)) {
        $list[$key] = $p;
      }
    }
    return $list;
  }

  private function allowParam($paramName) {
    $canonParamName = strtolower($paramName);
    // Exclude the fields which are only used to tell the proxy what to do
    // and the fields which should be added by signing the request later on
    if ($canonParamName == "output" || $canonParamName == "httpmethod" || $canonParamName == "authz" || $canonParamName == "st" || $canonParamName == "headers" || $canonParamName == "url" || $canonParamName == "contenttype" || $canonParamName == "postdata" || $canonParamName == "numentries" || $canonParamName == "getsummaries" || $canonParamName == "signowner" || $canonParamName == "signviewer" || $canonParamName == "gadget" || $canonParamName == "bypassspeccache" || substr($canonParamName, 0, 5) == "oauth" || substr($canonParamName, 0, 6) == "xoauth" || substr($canonParamName, 0, 9) == "opensocial" || $canonParamName == "container") {
      return false;
    }
    return true;
  }
}
