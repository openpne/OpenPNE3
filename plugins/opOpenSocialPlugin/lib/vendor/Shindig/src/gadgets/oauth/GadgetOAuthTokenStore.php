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

class OAuthStoreException extends GadgetException {
}

/**
 * Higher-level interface that allows callers to store and retrieve
 * OAuth-related data directly from {@code GadgetSpec}s, {@code GadgetContext}s,
 * etc. See {@link OAuthStore} for a more detailed explanation of the OAuth
 * Data Store.
 */
class GadgetOAuthTokenStore {

  // we use POST if no HTTP method is specified for access and request URLs
  // (user authorization always uses GET)
  public static $DEFAULT_HTTP_METHOD = "POST";

  /**
   * @var OAuthStore
   */
  private $store;

  /**
   * @var GadgetSpec
   */
  private $gadgetSpec;

  /**
   * @var BasicGadgetSpecFactory
   */
  private $specFactory;

  /**
   * Public constructor.
   *
   * @param store an {@link OAuthStore} that can store and retrieve OAuth
   *              tokens, as well as information about service providers.
   */
  public function __construct($store, $specFactory) {
    $this->specFactory = $specFactory;
    $this->store = $store;
  }

  /**
   * Stores a negotiated consumer key and secret in the gadget store.
   * The "secret" can either be a consumer secret in the strict OAuth sense,
   * or it can be a PKCS8-then-Base64 encoded private key that we'll be using
   * with this service provider.
   *
   * @param gadgetUrl the URL of the gadget
   * @param serviceName the service provider with whom we have negotiated a
   *                    consumer key and secret.
   */
  public function storeConsumerKeyAndSecret($gadgetUrl, $serviceName, $keyAndSecret) {
    $providerKey = new ProviderKey();
    $providerKey->setGadgetUri($gadgetUrl);
    $providerKey->setServiceName($serviceName);
    $this->store->setOAuthConsumerKeyAndSecret($providerKey, $keyAndSecret);
  }

  /**
   * Stores an access token in the OAuth Data Store.
   * @param tokenKey information about the Gadget storing the token.
   * @param tokenInfo the TokenInfo to be stored in the OAuth data store.
   */
  public function storeTokenKeyAndSecret($tokenKey, $tokenInfo) {
    $getGadgetUri = $tokenKey->getGadgetUri();
    if (empty($getGadgetUri)) {
      throw new Exception("found empty gadget URI in TokenKey");
    }
    $getUserId = $tokenKey->getUserId();
    if (empty($getUserId)) {
      throw new Exception("found empty userId in TokenKey");
    }
    $this->store->setTokenAndSecret($tokenKey, $tokenInfo);
  }

  /**
   * Retrieve an OAuthAccessor that is ready to sign OAuthMessages.
   *
   * @param tokenKey information about the gadget retrieving the accessor.
   *
   * @return an OAuthAccessorInfo containing an OAuthAccessor (whic can be
   *         passed to an OAuthMessage.sign method), as well as httpMethod and
   *         signatureType fields.
   */
  public function getOAuthAccessor(TokenKey $tokenKey, $ignoreCache) {
    $gadgetUri = $tokenKey->getGadgetUri();
    if (empty($gadgetUri)) {
      throw new OAuthStoreException("found empty gadget URI in TokenKey");
    }
    $getUserId = $tokenKey->getUserId();
    if (empty($getUserId)) {
      throw new OAuthStoreException("found empty userId in TokenKey");
    }
    $gadgetSpec = $this->specFactory->getGadgetSpecUri($gadgetUri, $ignoreCache);
    $provInfo = $this->getProviderInfo($gadgetSpec, $tokenKey->getServiceName());
    return $this->store->getOAuthAccessorTokenKey($tokenKey, $provInfo);
  }

  /**
   * Reads OAuth provider information out of gadget spec.
   * @param spec
   * @return a GadgetInfo
   */
  public static function getProviderInfo(GadgetSpec $spec, $serviceName) {
    $oauthSpec = $spec->oauth;
    if ($oauthSpec == null) {
      $message = "gadget spec is missing /ModulePrefs/OAuth section";
      throw new GadgetException($message);
    }
    $service = null;
    if (isset($oauthSpec[$serviceName])) {
      $service = $oauthSpec[$serviceName];
    }
    if ($service == null) {
      $message = '';
      $message .= "Spec does not contain OAuth service '";
      $message .= $serviceName;
      $message .= "'.  Known services: ";
      foreach ($services as $key => $value) {
        $message .= "'";
        $message .= $key;
        $message .= "'";
        $message .= ", ";
      }
      throw new GadgetException($message);
    }
    $provider = new OAuthServiceProvider($service->getRequestUrl(), $service->getAuthorizationUrl(), $service->getAccessUrl());
    $httpMethod = null;
    switch ($service->getRequestUrl()->method) {
      case "GET":
        $httpMethod = OAuthStoreVars::$HttpMethod['GET'];
        break;
      case "POST":
      default:
        $httpMethod = OAuthStoreVars::$HttpMethod['POST'];
        break;
    }
    $paramLocation = null;
    switch ($service->getRequestUrl()->location) {
      case OAuthStoreVars::$OAuthParamLocation['URI_QUERY']:
      case OAuthStoreVars::$OAuthParamLocation['POST_BODY']:
      case OAUthStoreVars::$OAuthParamLocation['AUTH_HEADER']:
        $paramLocation = $service->getRequestUrl()->location;
        break;
      default:
        $paramLocation = OAuthStoreVars::$OAuthParamLocation['AUTH_HEADER'];
        break;
    }
    $provInfo = new ProviderInfo();
    $provInfo->setHttpMethod($httpMethod);
    $provInfo->setParamLocation($paramLocation);
    // TODO: for now, we'll just set the signature type to HMAC_SHA1
    // as this will be ignored later on when retrieving consumer information.
    // There, if we find a negotiated HMAC key, we will use HMAC_SHA1. If we
    // find a negotiated RSA key, we will use RSA_SHA1. And if we find neither,
    // we may use RSA_SHA1 with a default signing key.
    $provInfo->setSignatureType(OAuthStoreVars::$SignatureType['HMAC_SHA1']);
    $provInfo->setProvider($provider);
    return $provInfo;
  }

  /**
   * Extracts a single oauth-related parameter from a key-value map,
   * throwing an exception if the parameter could not be found (unless the
   * parameter is optional, in which case null is returned).
   *
   * @param params the key-value map from which to pull the value (parameter)
   * @param paramName the name of the parameter (key).
   * @param isOptional if it's optional, don't throw an exception if it's not
   *                   found.
   * @return the value corresponding to the key (paramName)
   * @throws GadgetException if the parameter value couldn't be found.
   */
  static function getOAuthParameter($params, $paramName, $isOptional) {
    $param = @$params[$paramName];
    if ($param == null && ! $isOptional) {
      $message = "parameter '" . $paramName . "' missing in oauth feature section of gadget spec";
      throw new GadgetException($message);
    }
    return ($param == null) ? null : trim($param);
  }
}

class GadgetInfo {
  private $serviceName;
  private $providerInfo;

  public function getServiceName() {
    return $this->serviceName;
  }

  public function setServiceName($serviceName) {
    $this->serviceName = $serviceName;
  }

  public function getProviderInfo() {
    return $this->providerInfo;
  }

  public function setProviderInfo(ProviderInfo $providerInfo) {
    $this->providerInfo = $providerInfo;
  }
}
