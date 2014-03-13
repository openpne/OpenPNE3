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

class Substitutions {
  private $types = array('MESSAGE' => 'MSG', 'BIDI' => 'BIDI', 'USER_PREF' => 'UP', 'MODULE' => 'MODULE');
  
  private $substitutions = array();

  public function __construct() {
    foreach ($this->types as $type) {
      $this->substitutions[$type] = array();
    }
  }

  public function addSubstitution($type, $key, $value) {
    $this->substitutions[$type]["__{$type}_{$key}__"] = $value;
  }

  public function addSubstitutions($type, $array) {
    foreach ($array as $key => $value) {
      $this->addSubstitution($type, $key, $value);
    }
  }

  public function substitute($input) {
    foreach ($this->types as $type) {
      $input = $this->substituteType($type, $input);
    }
    return $input;
  }

  public function substituteType($type, $input) {
    if (empty($this->substitutions[$type])) {
      return $input;
    }
    return str_replace(array_keys($this->substitutions[$type]), array_values($this->substitutions[$type]), $input);
  }

  /**
   * Substitutes a uri
   * @param type The type to substitute, or null for all types.
   * @param uri
   * @return The substituted uri, or a dummy value if the result is invalid.
   */
  public function substituteUri($type, $uri) {
    if (empty($uri)) {
      return null;
    }
    try {
      if (! empty($type)) {
        return $this->substituteType($type, $uri);
      } else {
        return $this->substitute($uri);
      }
    } catch (Exception $e) {
      return "";
    }
  }
}