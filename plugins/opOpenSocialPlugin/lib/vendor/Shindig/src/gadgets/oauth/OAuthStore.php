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

interface OAuthStore {

  public function setOAuthConsumerKeyAndSecret($providerKey, $keyAndSecret);

  public function setTokenAndSecret($tokenKey, $tokenInfo);

  /**
   * Retrieve an OAuthAccessor that is ready to sign OAuthMessages for
   * resource access.
   * @param tokenKey a structure uniquely identifying the token: a userId,
   *                 a gadgetId, a moduleId (in case there are more than one
   *                 gadget of the same type on a page), a tokenName (which
   *                 distinguishes this token from others that the same gadget
   *                 might hold for the same service provider) and a serviceName
   *                 (which is the same as the service name in the ProviderKey
   *                 structure).
   * @param provInfo provider information. The store combines information stored
   *                 in the store (consumer key/secret, token, token secret,
   *                 etc.) with the provider information (access URL, request
   *                 URL etc.) passed in here to create an AccessorInfo object.
   *                 If no information can be found in the
   *                 store, it may use default keys that identify the container,
   *                 as opposed to consumer keys and secrets that are specific
   *                 to this gadget.
   * @return an OAuthAccessor object than can be passed to an OAuthMessage.sign
   *         method.
   */
  public function getOAuthAccessorTokenKey(TokenKey $tokenKey, ProviderInfo $provInfo);

  public function getOAuthAccessorProviderKey(ProviderKey $providerKey, ProviderInfo $provInfo);

}

class OAuthStoreVars {
  public static $HttpMethod = array('GET' => 'GET', 'POST' => 'POST');
  public static $SignatureType = array('HMAC_SHA1' => 'HMAC_SHA1', 'RSA_SHA1' => 'RSA_SHA1',
      'PLAINTEXT' => 'PLAINTEXT');
  public static $KeyType = array('HMAC_SYMMETRIC' => 'HMAC_SYMMETRIC', 'RSA_PRIVATE' => 'RSA_PRIVATE');
  public static $OAuthParamLocation = array('AUTH_HEADER' => 'auth-header', 'POST_BODY' => 'post-body',-
      'URI_QUERY' => 'uri-query');
}

class AccesorInfo {
  /**
   * @var OAuthAccessor
   */
  public $accessor;
  public $httpMethod;
  public $signatureType;
  public $paramLocation;

  public function getParamLocation() {
    return $this->paramLocation;
  }

  public function setParamLocation($paramLocation) {
    $this->paramLocation = $paramLocation;
  }

  /**
   * @return OAuthAccessor
   */
  public function getAccessor() {
    return $this->accessor;
  }

  public function setAccessor($accessor) {
    $this->accessor = $accessor;
  }

  public function getHttpMethod() {
    return $this->httpMethod;
  }

  public function setHttpMethod($httpMethod) {
    $this->httpMethod = $httpMethod;
  }

  public function getSignatureType() {
    return $this->signatureType;
  }

  public function setSignatureType($signatureType) {
    $this->signatureType = $signatureType;
  }
}

class ConsumerKeyAndSecret {
  private $consumerKey;
  private $consumerSecret;
  private $keyType;

  public function ConsumerKeyAndSecret($key, $secret, $type) {
    $this->consumerKey = $key;
    $this->consumerSecret = $secret;
    $this->keyType = $type;
  }

  public function getConsumerKey() {
    return $this->consumerKey;
  }

  public function getConsumerSecret() {
    return $this->consumerSecret;
  }

  public function getKeyType() {
    return $this->keyType;
  }
}

class ProviderKey {
  private $gadgetUri;
  private $serviceName;

  public function getGadgetUri() {
    return $this->gadgetUri;
  }

  public function setGadgetUri($gadgetUri) {
    $this->gadgetUri = $gadgetUri;
  }

  public function getServiceName() {
    return $this->serviceName;
  }

  public function setServiceName($serviceName) {
    $this->serviceName = $serviceName;
  }
}

class ProviderInfo {
  private $provider;
  private $httpMethod;
  private $signatureType;
  private $paramLocation;

  // this can be null if we have not negotiated a consumer key and secret
  // yet with the provider, or if we decided that we want to use a global
  // public key
  private $keyAndSecret;

  public function getParamLocation() {
    return $this->paramLocation;
  }

  public function setParamLocation($paramLocation) {
    $this->paramLocation = $paramLocation;
  }

  public function getKeyAndSecret() {
    return $this->keyAndSecret;
  }

  public function setKeyAndSecret($keyAndSecret) {
    $this->keyAndSecret = $keyAndSecret;
  }

  public function getProvider() {
    return $this->provider;
  }

  public function setProvider(OAuthServiceProvider $provider) {
    $this->provider = $provider;
  }

  public function getHttpMethod() {
    return $this->httpMethod;
  }

  public function setHttpMethod($httpMethod) {
    $this->httpMethod = $httpMethod;
  }

  public function getSignatureType() {
    return $this->signatureType;
  }

  public function setSignatureType($signatureType) {
    $this->signatureType = $signatureType;
  }
}

class TokenKey {
  private $userId;
  private $gadgetUri;
  private $moduleId;
  private $tokenName;
  private $serviceName;

  public function getUserId() {
    return $this->userId;
  }

  public function setUserId($userId) {
    $this->userId = $userId;
  }

  public function getGadgetUri() {
    return $this->gadgetUri;
  }

  public function setGadgetUri($gadgetUri) {
    $this->gadgetUri = $gadgetUri;
  }

  public function getModuleId() {
    return $this->moduleId;
  }

  public function setModuleId($moduleId) {
    $this->moduleId = $moduleId;
  }

  public function getTokenName() {
    return $this->tokenName;
  }

  public function setTokenName($tokenName) {
    $this->tokenName = $tokenName;
  }

  public function getServiceName() {
    return $this->serviceName;
  }

  public function setServiceName($serviceName) {
    $this->serviceName = $serviceName;
  }
}

class TokenInfo {
  private $accessToken;
  private $tokenSecret;

  public function __construct($token, $secret) {
    $this->accessToken = $token;
    $this->tokenSecret = $secret;
  }

  public function getAccessToken() {
    return $this->accessToken;
  }

  public function getTokenSecret() {
    return $this->tokenSecret;
  }
}
