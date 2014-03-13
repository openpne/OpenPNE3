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
 * http://www.opensocial.org/Technical-Resources/opensocial-spec-v081/opensocial-reference#opensocial.BodyType
 */
class BodyType implements ComplexField {
  public $build;
  public $eyeColor;
  public $hairColor;
  public $height;
  public $weight;

  public function getBuild() {
    return $this->build;
  }

  public function setBuild($build) {
    $this->build = $build;
  }

  public function getEyeColor() {
    return $this->eyeColor;
  }

  public function setEyeColor($eyeColor) {
    $this->eyeColor = $eyeColor;
  }

  public function getHairColor() {
    return $this->hairColor;
  }

  public function setHairColor($hairColor) {
    $this->hairColor = $hairColor;
  }

  public function getHeight() {
    return $this->height;
  }

  public function setHeight($height) {
    $this->height = $height;
  }

  public function getWeight() {
    return $this->weight;
  }

  public function setWeight($weight) {
    $this->weight = $weight;
  }

  public function getPrimarySubValue() {
    // FIXME: is primary sub-field specified for bodyType in the spec??
    return $this->getBuild();
  }
}
