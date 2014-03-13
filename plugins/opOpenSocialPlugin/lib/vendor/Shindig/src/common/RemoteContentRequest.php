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

class RemoteContentRequest {
  // these are used for making the request
  private $uri = '';
  // to get real url after signed requests
  private $notSignedUri = '';
  private $method = '';
  private $headers = array();
  private $postBody = false;
  // these fields are filled in once the request has completed
  private $responseContent = false;
  private $responseSize = false;
  private $responseHeaders = array();
  private $metadata = array();
  private $httpCode = false;
  private $httpCodeMsg = '';
  private $contentType = null;
  private $created;
  private $refreshInterval;
  private static $SC_OK = 200; //Please, use only for testing!
  public $handle = false;
  public static $DEFAULT_CONTENT_TYPE = "application/x-www-form-urlencoded; charset=utf-8";

  /**
   * @var Options
   */
  private $options;

  /**
   * @var SecurityToken
   */
  private $token;

  /**
   * @var string
   */
  private $invalidation;

  public static $AUTH_NONE = 'none';
  public static $AUTH_SIGNED = 'signed';
  public static $AUTH_OAUTH = 'oauth';

  /**
   * @var string
   */
  private $authType;

  /**
   * @var OAuthRequestParams
   */
  private $oauthParams = null;

  public function __construct($uri, $headers = false, $postBody = false) {
    $this->uri = $uri;
    $this->notSignedUri = $uri;
    $this->headers = $headers;
    $this->postBody = $postBody;
    $this->created = time();
    $this->authType = self::$AUTH_NONE;
  }

  public function createRemoteContentRequest($method, $uri, $headers, $postBody, $options) {
    $this->method = $method;
    $this->uri = $uri;
    $this->options = $options;
    // Copy the headers
    if (! isset($headers)) {
      $this->headers = '';
    } else {
      $setPragmaHeader = false;
      $tmpHeaders = '';
      foreach ($headers as $key => $value) {
        // Proxies should be bypassed with the Pragma: no-cache check.
        if ($key == "Pragma" && $options->ignoreCache) {
          $value = "no-cache";
          $setPragmaHeader = true;
        }
        $tmpHeaders .= $key . ":" . $value . "\n";
      }
      // Bypass caching in proxies as well.
      if (! $setPragmaHeader && $options->ignoreCache) {
        $tmpHeaders .= "Pragma: no-cache\n";
      }
      $this->headers = $tmpHeaders;
    }
    if (! isset($postBody)) {
      $this->postBody = '';
    } else {
      $this->postBody = array_merge($postBody, $this->postBody);
    }
    $type = $this->getHeader("Content-Type");
    if (! isset($type)) {
      $this->contentType = RemoteContentRequest::$DEFAULT_CONTENT_TYPE;
    } else {
      $this->contentType = $type;
    }
  }

  /**
   * Basic GET request.
   *
   * @param uri
   */
  public function createRemoteContentRequestWithUri($uri) {
    $this->createRemoteContentRequest("GET", $uri, null, null, RemoteContentRequest::getDefaultOptions());
  }

  /**
   * Returns a hash code which identifies this request, used for caching, takes method, url, authType and headers
   * into account for constructing the md5 checksum
   * NOTE: the postBody is excluded so that the GadgetHrefRenderer can use cached requests without having to
   *       fetch the data-pipeling social data first
   * @return string md5 checksum
   */
  public function toHash() {
    return md5($this->method . $this->uri . $this->authType . $this->headers);
  }

  public static function getDefaultOptions() {
    return new Options();
  }

  public function getContentType() {
    return $this->contentType;
  }

  public function getHttpCode() {
    return $this->httpCode;
  }

  public function getHttpCodeMsg() {
    return $this->httpCodeMsg;
  }

  public function getResponseContent() {
    return $this->responseContent;
  }

  public function getResponseHeaders() {
    return $this->responseHeaders;
  }

  public function getResponseSize() {
    return $this->responseSize;
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function isPost() {
    return ($this->postBody != false);
  }

  public function hasHeaders() {
    return ! empty($this->headers);
  }

  public function getPostBody() {
    return $this->postBody;
  }

  public function getUrl() {
    return $this->uri;
  }

  public function getNotSignedUrl() {
    return $this->notSignedUri;
  }

  public function getMethod() {
    if ($this->method) {
      return $this->method;
    }
    if ($this->postBody) {
      return 'POST';
    } else {
      return 'GET';
    }
  }

  public function setMethod($method) {
    $this->method = $method;
  }

  /**
   * @return Options
   */
  public function getOptions() {
    if (empty($this->options)) {
      $this->options = new Options();
    }
    return $this->options;
  }

  public function setContentType($type) {
    $this->contentType = $type;
  }

  public function setHttpCode($code) {
    $this->httpCode = intval($code);
  }

  public function setHttpCodeMsg($msg) {
    $this->httpCodeMsg = $msg;
  }

  public function setResponseContent($content) {
    $this->responseContent = $content;
  }

  public function setResponseHeader($headerName, $headerValue) {
    $this->responseHeaders[$headerName] = $headerValue;
  }

  public function setResponseHeaders($headers) {
    $this->responseHeaders = $headers;
  }

  public function setResponseSize($size) {
    $this->responseSize = intval($size);
  }

  public function setHeaders($headers) {
    $this->headers = $headers;
  }

  //FIXME: Find a better way to do this
  // The headers can be an array of elements.
  public function getHeader($headerName) {
    $headers = explode("\n", $this->headers);
    foreach ($headers as $header) {
      $key = explode(":", $header, 2);
      if (strtolower(trim($key[0])) == strtolower($headerName)) return trim($key[1]);
    }
    return null;
  }

  public function getResponseHeader($headerName) {
    return isset($this->responseHeaders[$headerName]) ? $this->responseHeaders[$headerName] : null;
  }

  public function getCreated() {
    return $this->created;
  }

  public function setPostBody($postBody) {
    $this->postBody = $postBody;
  }

  public function setUri($uri) {
    $this->uri = $uri;
  }

  public function setNotSignedUri($uri) {
    $this->notSignedUri = $uri;
  }

  public function setInvalidation($invalidation) {
    $this->invalidation = $invalidation;
  }

  public function getInvalidation() {
    return $this->invalidation;
  }

  /**
   * Sets the security token to use (used if the request has authorization set (signed, oauth))
   * @param SecurityToken $token
   */
  public function setToken($token) {
    $this->token = $token;
  }

  /**
   * Returns the SecurityToken for this request
   *
   * @return SecurityToken
   */
  public function getToken() {
    return $this->token;
  }

  public function setOAuthRequestParams(OAuthRequestParams $params) {
    $this->oauthParams = $params;
  }

  /**
   * @return OAuthRequestParams
   */
  public function getOAuthRequestParams() {
    return $this->oauthParams;
  }

  /**
   * Sets the authorization type for this request, can be one of
   * - none, no signing or authorization
   * - signed, sign the request with an oauth_signature
   * - oauth, logges in to the remote oauth service and uses it as base for signing the requests
   *
   * @param string $type ('none', 'signed', 'oauth')
   */
  public function setAuthType($type) {
    $this->authType = $type;
  }

  /**
   * Returns the auth type of the request
   *
   * @return string ('none', 'signed', 'oauth')
   */
  public function getAuthType() {
    return $this->authType;
  }

  /**
   * Sets the cache refresh interval to use for this request
   *
   * @param int $refreshInterval (in seconds)
   */
  public function setRefreshInterval($refreshInterval) {
    $this->refreshInterval = $refreshInterval;
  }

  /**
   * Returns the cache's refresh interval for this request
   *
   * @return int refreshInterval (in seconds)
   */
  public function getRefreshInterval() {
    return $this->refreshInterval;
  }

  public function setMetadata($key, $value) {
    $this->metadata[$key] = $value;
  }

  public function getMetadatas() {
    return $this->metadata;
  }

  public function isStrictNoCache() {
    $cacheControl = $this->getResponseHeader('Cache-Control');
    if ($cacheControl != null) {
      $directives = explode(',', $cacheControl);
      foreach ($directives as $directive) {
        if (strcasecmp($directive, 'no-cache') == 0
            || strcasecmp($directive, 'no-store') == 0
            || strcasecmp($directive, 'private') == 0) {
          return true;
        }
      }
    }
    $progmas = $this->getResponseHeader('Progma');
    if ($progmas != null) {
      foreach ($progmas as $progma) {
        if (strcasecmp($progma, 'no-cache') == 0) {
          return true;
        }
      }
    }
    return false;
  }
}

/**
 * Bag of options for making a request.
 *
 * This object is mutable to keep us sane. Don't mess with it once you've
 * sent it to RemoteContentRequest or bad things might happen.
 */
class Options {
  public $ignoreCache = false;
  public $ownerSigned = true;
  public $viewerSigned = true;

  public function __construct() {}

  /**
   * Copy constructor
   */
  public function copyOptions(Options $copyFrom) {
    $this->ignoreCache = $copyFrom->ignoreCache;
    $this->ownerSigned = $copyFrom->ownerSigned;
    $this->viewerSigned = $copyFrom->viewerSigned;
  }
}
