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
 * Basic example blacklist class. This class takes a text file with regex
 * rules against which URL's are tested.
 * The default file location is {$base_path}/blacklist.txt
 *
 */
class BasicGadgetBlacklist implements GadgetBlacklist {
  private $rules = array();

  public function __construct($file = false) {
    if (! $file) {
      $file = Shindig_Config::get('base_path') . '/blacklist.txt';
    }
    if (Shindig_File::exists($file)) {
      $this->rules = explode("\n", @file_get_contents($file));
    }
  }

  /**
   * Check the URL against the blacklist rules
   *
   * @param string $url
   * @return boolean is blacklisted or not?
   */
  function isBlacklisted($url) {
    foreach ($this->rules as $rule) {
      if (! empty($rule) && preg_match($rule, $url)) {
        return true;
      }
    }
    return false;
  }
}
