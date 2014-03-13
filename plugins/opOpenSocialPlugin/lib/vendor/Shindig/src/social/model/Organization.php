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
 * http://www.opensocial.org/Technical-Resources/opensocial-spec-v081/opensocial-reference#opensocial.Organization
 *
 */
class Organization implements ComplexField {
  public $address;
  public $description;
  public $endDate;
  public $field;
  public $name;
  public $primary;
  public $salary;
  public $startDate;
  public $subField;
  public $title;
  public $type;
  public $webpage;

  public function __construct($name, $primary = null) {
    $this->name = $name;
    $this->primary = $primary;
  }

  public function getAddress() {
    return $this->address;
  }

  public function setAddress($address) {
    $this->address = $address;
  }

  public function getDescription() {
    return $this->description;
  }

  public function setDescription($description) {
    $this->description = $description;
  }

  public function getEndDate() {
    return $this->endDate;
  }

  public function setEndDate($endDate) {
    $this->endDate = $endDate;
  }

  public function getField() {
    return $this->field;
  }

  public function setField($field) {
    $this->field = $field;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getSalary() {
    return $this->salary;
  }

  public function setSalary($salary) {
    $this->salary = $salary;
  }

  public function getStartDate() {
    return $this->startDate;
  }

  public function setStartDate($startDate) {
    $this->startDate = $startDate;
  }

  public function getSubField() {
    return $this->subField;
  }

  public function setSubField($subField) {
    $this->subField = $subField;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    $this->title = $title;
  }

  public function getType() {
    return $this->type;
  }

  public function setType($type) {
    $this->type = $type;
  }

  public function getWebpage() {
    return $this->webpage;
  }

  public function setWebpage($webpage) {
    $this->webpage = $webpage;
  }

  public function getPrimary() {
    return $this->primary;
  }

  public function setPrimary($primary) {
    $this->primary = $primary;
  }

  public function getPrimarySubValue() {
    return $this->getName();
  }
}
