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
 * The OAuth preferences located in the gadget xml inside ModulePrefs.
 * Now, it only has 1 property, but this class was designed to be reusable
 **/
class OAuthSpec {
  
  private $oAuthServices = array();

  public function __construct($services) {
    foreach ($services as $service) {
      $oauthService = new OAuthService($service);
      $this->oAuthServices[$oauthService->getName()] = $oauthService;
    }
  }

  public function getServices() {
    return $this->oAuthServices;
  }

}