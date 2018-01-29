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
 * http://www.opensocial.org/Technical-Resources/opensocial-spec-v081/opensocial-reference#opensocial.Enum
 *
 * Base class for all Enum objects. This class allows containers to use constants
 * for fields that have a common set of values.
 *
 */

abstract class Enum implements ComplexField {
  public $displayValue;
  public $key;
  public $values = array();

  public function __construct($key, $displayValue = '') {
    if (! empty($key) && ! isset($this->values[$key])) {
      if (in_array($key, $this->values)) {
        // case of mixing key <> display value, correct it
        $key = array_search($key, $this->values);
      } else {
        $this->displayValue = $displayValue;
        //throw new Exception("Invalid Enum key: $key\n". print_r(debug_backtrace(), true));
      }
    }
    $this->key = $key;
    $this->displayValue = ! empty($displayValue) ? $displayValue : (isset($this->values[$key]) ? $this->values[$key] : '');
    unset($this->values);
  }

  public function getDisplayValue() {
    return $this->displayValue;
  }

  public function setDisplayValue($displayValue) {
    $this->displayValue = $displayValue;
  }

  public function toString() {
    return $this->jsonString;
  }

  public function getPrimarySubValue() {
    return $this->key;
  }
}

/**
 * public Enum for opensocial.Enum.Drinker
 */
class EnumDrinker extends Enum {
  public $values = array('HEAVILY' => "Heavily", 'NO' => "No", 'OCCASIONALLY' => "Occasionally",
      'QUIT' => "Quit", 'QUITTING' => "Quitting", 'REGULARLY' => "Regularly",
      'SOCIALLY' => "Socially", 'YES' => "Yes");
}

/**
 * public Enum for opensocial.Enum.Gender
 */
class EnumGender extends Enum {
  public $values = array('FEMALE' => "Female", 'MALE' => "Male");
}

/**
 * public Enum for opensocial.Enum.LookingFor
 */
class EnumLookingFor extends Enum {
  public $values = array('ACTIVITY_PARTNERS' => 'Activity Partners', 'DATING' => 'Dating',
      'FRIENDS' => 'Friends', 'NETWORKING' => 'Networking', 'RANDOM' => 'Random',
      'RELATIONSHIP' => 'Relationship');
}

/**
 * public Enum for opensocial.Enum.Smoker
 */
class EnumSmoker extends Enum {
  public $values = array('HEAVILY' => "Heavily", 'NO' => "No", 'OCCASIONALLY' => "Ocasionally", 'QUIT' => "Quit",
      'QUITTING' => "Quitting", 'REGULARLY' => "Regularly", 'SOCIALLY' => "Socially",
      'YES' => "Yes");
}

/**
 * public Enum for opensocial.Enum.Presence
 */
class EnumPresence extends Enum {
  public $values = array('AWAY' => "Away", 'CHAT' => "Chat", 'DND' => "Do Not Disturb", 'OFFLINE' => "Offline",
      'ONLINE' => "Online", 'XA' => "Extended Away");
}
