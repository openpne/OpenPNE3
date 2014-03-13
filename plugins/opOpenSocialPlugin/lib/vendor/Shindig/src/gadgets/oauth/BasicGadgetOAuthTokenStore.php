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
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

class BasicGadgetOAuthTokenStore extends GadgetOAuthTokenStore {

  /** default location for consumer keys and secrets */
  private $OAUTH_CONFIG = "oauth.json";
  private $CONSUMER_SECRET_KEY = "consumer_secret";
  private $CONSUMER_KEY_KEY = "consumer_key";
  private $KEY_TYPE_KEY = "key_type";

  public function __construct($store, $fetcher) {
    parent::__construct($store, $fetcher);
    $this->OAUTH_CONFIG = Shindig_Config::get('container_path') . $this->OAUTH_CONFIG;
  }

  public function initFromConfigFile($fetcher) {
    // Read our consumer keys and secrets from config/oauth.js
    // This actually involves fetching gadget specs
    try {
      $oauthConfigStr = file_get_contents($this->OAUTH_CONFIG);
      // remove all comments because this confuses the json parser
      // note: the json parser also crashes on trailing ,'s in records so please don't use them
      $contents = preg_replace('@/\\*.*?\\*/@s', '', $oauthConfigStr);
      $oauthConfig = json_decode($contents, true);
      if ($oauthConfig == $contents) {
        throw new GadgetException("OAuth configuration json failed to parse.");
      }
      foreach ($oauthConfig as $gadgetUri => $value) {
        $this->storeConsumerInfos($gadgetUri, $value);
      }
    } catch (Exception $e) {
      throw new GadgetException($e);
    }
  }

  private function storeConsumerInfos($gadgetUri, $oauthConfig) {
    foreach ($oauthConfig as $key => $value) {
      $serviceName = $key;
      $consumerInfo = $value;
      $this->storeConsumerInfo($gadgetUri, $serviceName, $consumerInfo);
    }
  }

  private function storeConsumerInfo($gadgetUri, $serviceName, $consumerInfo) {
    if (! isset($consumerInfo[$this->CONSUMER_SECRET_KEY]) || ! isset($consumerInfo[$this->CONSUMER_KEY_KEY]) || ! isset($consumerInfo[$this->KEY_TYPE_KEY])) {
      throw new Exception("Invalid configuration in oauth.json");
    }
    $consumerSecret = $consumerInfo[$this->CONSUMER_SECRET_KEY];
    $consumerKey = $consumerInfo[$this->CONSUMER_KEY_KEY];
    $keyTypeStr = $consumerInfo[$this->KEY_TYPE_KEY];
    $keyType = 'HMAC_SYMMETRIC';
    if ($keyTypeStr == "RSA_PRIVATE") {
      $keyType = 'RSA_PRIVATE';
      $cache = Cache::createCache(Shindig_Config::get('data_cache'), 'OAuthToken');
      if (($cachedRequest = $cache->get(md5("RSA_KEY_" . $serviceName))) !== false) {
        $consumerSecret = $cachedRequest;
      } else {
        $key = $consumerInfo[$this->CONSUMER_SECRET_KEY];
        if (empty($key)) {
          throw new Exception("Invalid key");
        }
        if (strpos($key, "-----BEGIN") === false) {
          $strip_this = array(" ", "\n", "\r");
          //removes breaklines and trim.
          $rsa_private_key = trim(str_replace($strip_this, "", $key));
          $consumerSecret = OAuth::$BEGIN_PRIVATE_KEY . "\n";
          $chunks = str_split($rsa_private_key, 64);
          foreach ($chunks as $chunk) {
            $consumerSecret .= $chunk . "\n";
          }
          $consumerSecret .= OAuth::$END_PRIVATE_KEY;
        } else {
          $consumerSecret = $key;
        }
        $cache->set(md5("RSA_KEY_" . $serviceName), $consumerSecret);
      }
    }
    $kas = new ConsumerKeyAndSecret($consumerKey, $consumerSecret, $keyType);
    $this->storeConsumerKeyAndSecret($gadgetUri, $serviceName, $kas);
  }
}
