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
 * The OAuth service located in the gadget xml inside ModulePrefs -> OAuth.
 **/
class OAuthService {
  
  private static $URL_ATTR = "url";
  private static $PARAM_LOCATION_ATTR = "param_location";
  private static $METHOD_ATTR = "method";
  
  private $name;
  
  /**
   * @var EndPoint
   */
  private $requestUrl;
  
  /**
   * @var EndPoint
   */
  private $authorizationUrl;
  
  /**
   * @var EndPoint
   */
  private $accessUrl;

  public function __construct(DOMElement $service) {
    $this->name = (string)$service->getAttribute('name');
    $elements = $service->getElementsByTagName('*');
    foreach ($elements as $element) {
      $type = $element->tagName;
      if ($type == 'Request') {
        if ($this->requestUrl) {
          throw new SpecParserException("Multiple OAuth/Service/Request elements");
        }
        $this->requestUrl = $this->parseEndPoint($element);
      } else if ($type == 'Authorization') {
        if ($this->authorizationUrl) {
          throw new SpecParserException("Multiple OAuth/Service/Authorization elements");
        }
        $this->authorizationUrl = $this->parseEndPoint($element);
      } else if ($type == 'Access') {
        if ($this->accessUrl) {
          throw new SpecParserException("Multiple OAuth/Service/Access elements");
        }
        $this->accessUrl = $this->parseEndPoint($element);
      }
    }
    if ($this->requestUrl == null) {
      throw new SpecParserException("/OAuth/Service/Request is required");
    }
    if ($this->accessUrl == null) {
      throw new SpecParserException("/OAuth/Service/Access is required");
    }
    if ($this->authorizationUrl == null) {
      throw new SpecParserException("/OAuth/Service/Authorization is required");
    }
    if ($this->requestUrl->location != $this->accessUrl->location) {
      throw new SpecParserException(
          "Access@location must be identical to Request@location");
    }
    if ($this->requestUrl->method != $this->accessUrl->method) {
      throw new SpecParserException(
          "Access@method must be identical to Request@method");
    }
    if ($this->requestUrl->location == Location::$body &&
        $this->requestUrl->method == Method::$GET) {
      throw new SpecParserException("Incompatible parameter location, cannot" +
          "use post-body with GET requests");
    }
  }

  private function parseEndPoint($element) {
    $url = trim($element->getAttribute(OAuthService::$URL_ATTR));
    if (empty($url)) {
      throw new SpecParserException("Not an HTTP url");
    }
    $location = Location::$header;
    $locationString = trim($element->getAttribute(OAuthService::$PARAM_LOCATION_ATTR));
    if (! empty($locationString)) {
      $location = $locationString;
    }
    $method = Method::$GET;
    $methodString = trim($element->getAttribute(OAuthService::$METHOD_ATTR));
    if (! empty($methodString)) {
      $method = $methodString;
    }
    return new EndPoint($url, $method, $location);
  }

  public function getName() {
    return $this->name;
  }

  public function getRequestUrl() {
    return $this->requestUrl;
  }

  public function getAuthorizationUrl() {
    return $this->authorizationUrl;
  }

  public function getAccessUrl() {
    return $this->accessUrl;
  }
}

/**
 * Method to use for requests to an OAuth request token or access token URL.
 */
class Method {
  public static $GET = "GET";
  public static $POST = "POST";
}

/**
 * Location for OAuth parameters in requests to an OAuth request token,
 * access token, or resource URL.  (Lowercase to match gadget spec schema)
 */
class Location {
  public static $header = "auth-header";
  public static $url = "url-query";
  public static $body = "post-body";
}

/**
 * Description of an OAuth request token or access token URL.
 */
class EndPoint {
  public $url;
  public $method;
  public $location;

  public function __construct($url, $method, $location) {
    $this->url = $url;
    $this->method = $method;
    $this->location = $location;
  }
}
