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
 * Bundles information about a proxy request that requires OAuth
 */
class OAuthRequestParams {
  public static $SERVICE_PARAM = "OAUTH_SERVICE_NAME";
  public static $TOKEN_PARAM = "OAUTH_TOKEN_NAME";
  public static $REQUEST_TOKEN_PARAM = "OAUTH_REQUEST_TOKEN";
  public static $REQUEST_TOKEN_SECRET_PARAM = "OAUTH_REQUEST_TOKEN_SECRET";
  public static $CLIENT_STATE_PARAM = "oauthState";
  public static $BYPASS_SPEC_CACHE_PARAM = "bypassSpecCache";
  protected $serviceName;
  protected $tokenName;
  protected $requestToken;
  protected $requestTokenSecret;
  protected $origClientState;
  protected $bypassSpecCache;

  public function __construct(array $arguments) {
    $this->serviceName = self::getParam($arguments, self::$SERVICE_PARAM, "");
    $this->tokenName = self::getParam($arguments, self::$TOKEN_PARAM, "");
    $this->requestToken = self::getParam($arguments, self::$REQUEST_TOKEN_PARAM, null);
    $this->requestTokenSecret = self::getParam($arguments, self::$REQUEST_TOKEN_SECRET_PARAM, null);
    $this->origClientState = self::getParam($arguments, self::$CLIENT_STATE_PARAM, null);
    $this->bypassSpecCache = '1' == self::getParam($arguments, self::$BYPASS_SPEC_CACHE_PARAM, null);
  }

  private static function getParam(array $arguments, $name, $defaultValue) {
    if (isset($arguments[$name])) {
      return $arguments[$name];
    } else {
      return $defaultValue;
    }
  }

  public function getBypassSpecCache() {
    return $this->bypassSpecCache;
  }

  public function getRequestToken() {
    return $this->requestToken;
  }

  public function getRequestTokenSecret() {
    return $this->requestTokenSecret;
  }

  public function getServiceName() {
    return $this->serviceName;
  }

  public function getTokenName() {
    return $this->tokenName;
  }

  public function getOrigClientState() {
    return $this->origClientState;
  }
}
