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
 * SecurityToken derived from a successful OAuth validation.
 */
class OAuthSecurityToken extends SecurityToken {
  private $userId;
  private $appUrl;
  private $appId;
  private $domain;
  
  private $authenticationMode;

  public function __construct($userId, $appUrl, $appId, $domain) {
    $this->userId = $userId;
    $this->appUrl = $appUrl;
    $this->appId = $appId;
    $this->domain = $domain;
  }

  public function isAnonymous() {
    return ($this->userId == null);
  }

  public function getOwnerId() {
    return $this->userId;
  }

  public function getViewerId() {
    return $this->userId;
  }

  public function getAppId() {
    return $this->appId;
  }

  public function getDomain() {
    return $this->domain;
  }

  public function getAppUrl() {
    return $this->appUrl;
  }

  public function getModuleId() {
    return null;
  }

  public function toSerialForm() {
    return "OAuthSecurityToken[userId=$userId,appUrl=$appUrl,appId=$appId,domain=$domain]";
  }
  
  public function getAuthenticationMode() {
    return $this->authenticationMode;
  }
  
  public function setAuthenticationMode($mode) {
    $this->authenticationMode = $mode;
  }
}
