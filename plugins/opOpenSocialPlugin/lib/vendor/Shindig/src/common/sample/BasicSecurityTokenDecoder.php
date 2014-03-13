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


class BasicSecurityTokenDecoder extends SecurityTokenDecoder {
  private $OWNER_INDEX = 0;
  private $VIEWER_INDEX = 1;
  private $APP_ID_INDEX = 2;
  private $DOMAIN_INDEX = 3;
  private $APP_URL_INDEX = 4;
  private $MODULE_ID_INDEX = 5;
  private $CONTAINER_INDEX = 6;

  /**
   * {@inheritDoc}
   *
   * Returns a token with some faked out values.
   */
  public function createToken($stringToken) {
    if (empty($stringToken) && ! empty($_GET['authz'])) {
      throw new GadgetException('INVALID_GADGET_TOKEN');
    }
    try {
      //TODO remove this once we have a better way to generate a fake token
      // in the example files
      if (Shindig_Config::get('allow_plaintext_token') && count(explode(':', $stringToken)) == 7) {
        $tokens = explode(":", $stringToken);
        return new BasicSecurityToken(null, null, urldecode($tokens[$this->OWNER_INDEX]), urldecode($tokens[$this->VIEWER_INDEX]), urldecode($tokens[$this->APP_ID_INDEX]), urldecode($tokens[$this->DOMAIN_INDEX]), urldecode($tokens[$this->APP_URL_INDEX]), urldecode($tokens[$this->MODULE_ID_INDEX]), urldecode($tokens[$this->CONTAINER_INDEX]));
      } else {
        return BasicSecurityToken::createFromToken($stringToken, Shindig_Config::get('token_max_age'));
      }
    } catch (Exception $e) {
      throw new GadgetException('INVALID_GADGET_TOKEN');
    }
  }
}
