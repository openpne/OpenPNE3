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
 * Locale class doesn't exist in php, so to allow the code base to be closer to the java and it's spec
 * interpretation one, we created our own
 */
class Locale {
  public $language;
  public $country;

  public function __construct($language, $country) {
    $this->language = $language;
    $this->country = $country;
  }

  public function equals($obj) {
    if (! ($obj instanceof Locale)) {
      return false;
    }
    return ($obj->language == $this->language && $obj->country == $this->country);
  }

  public function getLanguage() {
    return $this->language;
  }

  public function getCountry() {
    return $this->country;
  }

}