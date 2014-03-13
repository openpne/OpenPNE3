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

class OAuthAccessor {
  public $consumer;
  public $requestToken;
  public $accessToken;
  public $tokenSecret;
  private $properties = array();

  public function __construct(OAuthConsumer $consumer) {
    $this->consumer = $consumer;
    $this->requestToken = null;
    $this->accessToken = null;
    $this->tokenSecret = null;
  }

  public function getProperty($name) {
    return $this->properties[$name];
  }

  public function setProperty($name, $value) {
    $this->properties[$name] = $value;
  }

  /**
   * @return OAuthRequest
   */
  public function newRequestMessage($method, $url, $parameters) {
    if (! isset($method)) {
      $method = $this->getProperty("httpMethod");
      if ($method == null) {
        $method = $this->consumer->getProperty("httpMethod");
        if ($method == null) {
          $method = "GET";
        }
      }
    }
    $message = OAuthRequest::from_consumer_and_token($this->consumer, $this->accessToken, $method, $url, $parameters);
    $signatureMethod = null;
    if ($parameters[OAuth::$OAUTH_SIGNATURE_METHOD] == OAuth::$RSA_SHA1) {
      $signatureMethod = new OAuthSignatureMethod_RSA_SHA1();
    } else if ($parameters[OAuth::$OAUTH_SIGNATURE_METHOD] == OAuth::$HMAC_SHA1) {
      $signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
    } else { //PLAINTEXT
      $signatureMethod = new OAuthSignatureMethod_PLAINTEXT();
    }
    $message->sign_request($signatureMethod, $this->consumer, $this->tokenSecret);
    return $message;
  }

}