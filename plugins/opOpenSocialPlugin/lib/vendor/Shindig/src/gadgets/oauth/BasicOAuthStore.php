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

class OAuthNoDataException extends Exception {
}

class BasicOAuthStore implements OAuthStore {
  
  private $consumerInfos = array();
  private $tokens = array();
  
  private $defaultConsumerKey;
  private $defaultConsumerSecret;

  public function __construct($consumerKey = null, $privateKey = null) {
    $this->defaultConsumerKey = $consumerKey;
    $this->defaultConsumerSecret = $privateKey;
  }

  public function setHashMapsForTesting($consumerInfos, $tokens) {
    $this->consumerInfos = $consumerInfos;
    $this->tokens = $tokens;
  }

  public function getOAuthAccessorTokenKey(TokenKey $tokenKey, ProviderInfo $provInfo) {
    $provKey = new ProviderKey();
    $provKey->setGadgetUri($tokenKey->getGadgetUri());
    $provKey->setServiceName($tokenKey->getServiceName());
    //AccesorInfo
    $result = $this->getOAuthAccessorProviderKey($provKey, $provInfo);
    //TokenInfo
    $accessToken = $this->getTokenInfo($tokenKey);
    if ($accessToken != null) {
      // maybe convert into methods
      $result->getAccessor()->accessToken = $accessToken->getAccessToken();
      $result->getAccessor()->tokenSecret = $accessToken->getTokenSecret();
    }
    return $result;
  }

  public function getOAuthAccessorProviderKey(ProviderKey $providerKey, ProviderInfo $provInfo) {
    if ($provInfo == null) {
      throw new OAuthNoDataException("must pass non-null provider info to getOAuthAccessor");
    }
    //AccesorInfo
    $result = new AccesorInfo();
    $result->setHttpMethod($provInfo->getHttpMethod());
    $result->setParamLocation($provInfo->getParamLocation());
    //ConsumerKeyAndSecret
    $key = md5(serialize($providerKey));
    $consumerKeyAndSecret = null;
    if (isset($this->consumerInfos[$key])) {
      $consumerKeyAndSecret = $this->consumerInfos[$key];
    } else {
      throw new OAuthNoDataException("The Key was invalid for consumerInfos, maybe your oauth.json configuration is wrong.");
    }
    if ($consumerKeyAndSecret == null) {
      if ($this->defaultConsumerKey == null || $this->defaultConsumerSecret == null) {
        throw new OAuthNoDataException("ConsumerKeyAndSecret was null in oauth store");
      } else {
        $consumerKeyAndSecret = new ConsumerKeyAndSecret($this->defaultConsumerKey, $this->defaultConsumerSecret, OAuthStoreVars::$KeyType['RSA_PRIVATE']);
      }
    }
    //OAuthServiceProvider
    $oauthProvider = $provInfo->getProvider();
    if (! isset($oauthProvider)) {
      throw new OAuthNoDataException("OAuthService provider was null in provider info");
    }
    // Accesing the class
    $usePublicKeyCrypto = ($consumerKeyAndSecret->getKeyType() == OAuthStoreVars::$KeyType['RSA_PRIVATE']);
    //OAuthConsumer
    $consumer = ($usePublicKeyCrypto) ? new OAuthConsumer($consumerKeyAndSecret->getConsumerKey(), null, $oauthProvider) : new OAuthConsumer($consumerKeyAndSecret->getConsumerKey(), $consumerKeyAndSecret->getConsumerSecret(), $oauthProvider);
    if ($usePublicKeyCrypto) {
      $consumer->setProperty(OAuthSignatureMethod_RSA_SHA1::$PRIVATE_KEY, $consumerKeyAndSecret->getConsumerSecret());
      $result->setSignatureType(OAuthStoreVars::$SignatureType['RSA_SHA1']);
    } else {
      $result->setSignatureType(OAuthStoreVars::$SignatureType['HMAC_SHA1']);
    }
    
    $result->setAccessor(new OAuthAccessor($consumer));
    return $result;
  }

  public function setOAuthConsumerKeyAndSecret($providerKey, $keyAndSecret) {
    $key = md5(serialize($providerKey));
    $this->consumerInfos[$key] = $keyAndSecret;
  }

  public function setTokenAndSecret($tokenKey, $tokenInfo) {
    $this->tokens[md5(serialize($tokenKey))] = $tokenInfo;
  }

  private function getTokenInfo($tokenKey) {
    $key = md5(serialize($tokenKey));
    return isset($this->tokens[$key]) ? $this->tokens[$key] : null;
  }
}