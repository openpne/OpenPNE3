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
 * Produces Signing content fetchers for input tokens.
 */
class SigningFetcherFactory {
  private $keyName;
  private $privateKey;

  /**
   * Produces a signing fetcher that will sign requests and delegate actual
   * network retrieval to the {@code networkFetcher}
   *
   * @param RemoteContentFetcher $networkFetcher The fetcher that will be doing actual work.
   * @return SigningFetcher
   * @throws GadgetException
   */
  public function getSigningFetcher(RemoteContentFetcher $networkFetcher) {
    return SigningFetcher::makeFromB64PrivateKey($networkFetcher, $this->keyName, $this->privateKey);
  }

  /**
   * @param keyFile The file containing your private key for signing requests.
   */
  public function __construct($keyFile = null) {
    $this->keyName = 'http://' . $_SERVER["HTTP_HOST"] . Shindig_Config::get('web_prefix') . '/public.cer';
    if (! empty($keyFile)) {
      $rsa_private_key = false;
      $privateKey = null;
      try {
        if (Shindig_File::exists($keyFile)) {
          if (Shindig_File::readable($keyFile)) {
            $rsa_private_key = @file_get_contents($keyFile);
          } else {
            throw new Exception("Could not read keyfile ($keyFile), check the file name and permission");
          }
        }
        if (! $rsa_private_key) {
          $rsa_private_key = '';
        } else {
          $phrase = Shindig_Config::get('private_key_phrase') != '' ? (Shindig_Config::get('private_key_phrase')) : null;
          if (strpos($rsa_private_key, "-----BEGIN") === false) {
            $privateKey .= "-----BEGIN PRIVATE KEY-----\n";
            $chunks = str_split($rsa_private_key, 64);
            foreach ($chunks as $chunk) {
              $privateKey .= $chunk . "\n";
            }
            $privateKey .= "-----END PRIVATE KEY-----";
          } else {
            $privateKey = $rsa_private_key;
          }
          if (! $rsa_private_key = @openssl_pkey_get_private($privateKey, $phrase)) {
            throw new Exception("Could not create the key");
          }
        }
      } catch (Exception $e) {
        throw new Exception("Error loading private key: " . $e);
      }
      $this->privateKey = $rsa_private_key;
    }
  }
}
