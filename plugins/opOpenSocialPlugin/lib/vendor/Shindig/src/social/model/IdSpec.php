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

class IdSpec {
  public static $types = array('VIEWER', 'OWNER', 'VIEWER_FRIENDS', 'OWNER_FRIENDS', 'USER_IDS');
  
  public $jsonSpec;
  public $type;

  public function __construct($jsonSpec, $type) {
    $this->jsonSpec = $jsonSpec;
    $this->type = $type;
  }

  static public function fromJson($jsonIdSpec) {
    if (! empty($jsonIdSpec) && in_array((string)$jsonIdSpec, idSpec::$types)) {
      $idSpecEnum = (string)$jsonIdSpec;
    } elseif (! empty($jsonIdSpec)) {
      $idSpecEnum = 'USER_IDS';
    } else {
      throw new Exception("The json request had a bad idSpec");
    }
    return new IdSpec($jsonIdSpec, $idSpecEnum);
  }

  /**
   * Only valid for IdSpecs of type USER_IDS
   * @return A list of the user ids in the id spec
   *
   */
  public function fetchUserIds() {
    $userIdArray = $this->jsonSpec;
    if (! is_array($userIdArray)) {
      $userIdArray = array($userIdArray);
    }
    $userIds = array();
    foreach ($userIdArray as $id) {
      $userIds[] = (string)$id;
    }
    return $userIds;
  }

  public function getType() {
    return $this->type;
  }
}
