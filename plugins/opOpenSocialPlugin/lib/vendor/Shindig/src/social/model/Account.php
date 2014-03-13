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
 *
 */
class Account implements ComplexField {
  public $domain;
  public $userid;
  public $username;
  public $primary;

  public function __construct($domain, $userid, $username, $primary = null) {
    $this->domain = $domain;
    $this->userid = $userid;
    $this->username = $username;
    $this->primary = $primary;
  }

  public function getDomain() {
    return $this->domain;
  }

  public function setDomain($domain) {
    $this->domain = $domain;
  }

  public function getUserid() {
    return $this->userid;
  }

  public function setUserid($userid) {
    $this->userid = $userid;
  }

  public function getUsername() {
    return $this->username;
  }

  public function setUsername($username) {
    $this->username = $username;
  }

  public function getPrimary() {
    return $this->primary;
  }

  public function setPrimary($primary) {
    $this->primary = $primary;
  }

  public function getPrimarySubValue() {
    return $this->getDomain();
  }
}
