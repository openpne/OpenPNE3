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
 * see
 * http://www.opensocial.org/Technical-Resources/opensocial-spec-v081/opensocial-reference#opensocial.Name
 *
 */
class Name implements ComplexField {
  public $additionalName;
  public $familyName;
  public $givenName;
  public $honorificPrefix;
  public $honorificSuffix;
  public $formatted;

  public function __construct($formatted) {
    $this->formatted = $formatted;
  }

  public function getFormatted() {
    return $this->formatted;
  }

  public function setFormatted($formatted) {
    $this->formatted = $formatted;
  }

  public function getAdditionalName() {
    return $this->additionalName;
  }

  public function setAdditionalName($additionalName) {
    $this->additionalName = $additionalName;
  }

  public function getFamilyName() {
    return $this->familyName;
  }

  public function setFamilyName($familyName) {
    $this->familyName = $familyName;
  }

  public function getGivenName() {
    return $this->givenName;
  }

  public function setGivenName($givenName) {
    $this->givenName = $givenName;
  }

  public function getHonorificPrefix() {
    return $this->honorificPrefix;
  }

  public function setHonorificPrefix($honorificPrefix) {
    $this->honorificPrefix = $honorificPrefix;
  }

  public function getHonorificSuffix() {
    return $this->honorificSuffix;
  }

  public function setHonorificSuffix($honorificSuffix) {
    $this->honorificSuffix = $honorificSuffix;
  }

  public function getPrimarySubValue() {
    return $this->getFormatted();
  }
}
