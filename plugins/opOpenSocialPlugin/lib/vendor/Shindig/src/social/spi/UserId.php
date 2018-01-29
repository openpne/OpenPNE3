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

class UserId {
  public static $types = array('me', 'viewer', 'owner', 'userId');
  private $type;
  private $userId;

  public function __construct($type, $userId) {
    $this->type = $type;
    $this->userId = $userId;
  }

  static public function fromJson($jsonId) {
    if (in_array(substr($jsonId, 1), UserId::$types)) {
      return new UserId(substr($jsonId, 1), null);
    }
    return new UserId('userId', $jsonId);
  }

  public function getUserId(SecurityToken $token) {
    switch ($this->type) {
      case 'viewer':
      case 'me':
        return $token->getViewerId();
        break;
      case 'owner':
        return $token->getOwnerId();
        break;
      case 'userId':
        return $this->userId;
        break;
      default:
        throw new Exception("The type field is not a valid enum: {$this->type}");
        break;
    }
  }

  public function getType() {
    return $this->type;
  }
}