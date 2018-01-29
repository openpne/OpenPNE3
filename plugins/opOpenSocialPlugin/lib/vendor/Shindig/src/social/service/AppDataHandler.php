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

class AppDataHandler extends DataRequestHandler {
  private static $APP_DATA_PATH = "/appdata/{userId}/{groupId}/appId";

  public function __construct() {
    parent::__construct('app_data_service');
  }

  /**
   * /people/{userId}/{groupId}/{appId}
   * - fields={field1, field2}
   *
   * examples:
   * /appdata/john.doe/@friends/app?fields=count
   * /appdata/john.doe/@self/app
   *
   */
  public function handleDelete(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$APP_DATA_PATH);
    $userIds = $requestItem->getUsers();
    if (count($userIds) < 1) {
      throw new InvalidArgumentException("No userId specified");
    } elseif (count($userIds) > 1) {
      throw new InvalidArgumentException("Multiple userIds not supported");
    }
    return $this->service->deletePersonData($userIds[0], $requestItem->getGroup(), $requestItem->getAppId(), $requestItem->getFields(), $requestItem->getToken());
  }

  /**
   * /appdata/{userId}/{groupId}/{appId}
   * - fields={field1, field2}
   *
   * examples:
   * /appdata/john.doe/@friends/app?fields=count
   * /appdata/john.doe/@self/app
   */
  public function handleGet(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$APP_DATA_PATH);
    $userIds = $requestItem->getUsers();
    if (count($userIds) < 1) {
      throw new InvalidArgumentException("No userId(s) specified");
    }
    return $this->service->getPersonData($userIds[0], $requestItem->getGroup(), $requestItem->getAppId(), $requestItem->getFields(), $requestItem->getToken());
  }

  /**
   * /appdata/{userId}/{groupId}/{appId}
   * - fields={field1, field2}
   *
   * examples:
   * /appdata/john.doe/@friends/app?fields=count
   * /appdata/john.doe/@self/app
   *
   * The post data should be a regular json object. All of the fields vars will
   * be pulled from the values and set on the person object. If there are no
   * fields vars then all of the data will be overridden.
   */
  public function handlePost(RequestItem $requestItem) {
    $this->checkService();
    $requestItem->applyUrlTemplate(self::$APP_DATA_PATH);
    $userIds = $requestItem->getUsers();
    if (count($userIds) < 1) {
      throw new InvalidArgumentException("No userId specified");
    } elseif (count($userIds) > 1) {
      throw new InvalidArgumentException("Multiple userIds not supported");
    }
    $values = $requestItem->getParameter("data");
    // this used to be $requestItem->getFields() instead of using the fields, but that makes no sense to me
    // better to detect the fields depending on input right?
    $fields = array();
    foreach (array_keys($values) as $key) {
      $fields[] = $key;
      if (! $this->isValidKey($key)) {
        throw new SocialSpiException("One or more of the app data keys are invalid: " . $key, ResponseError::$BAD_REQUEST);
      }
    }
    $this->service->updatePersonData($userIds[0], $requestItem->getGroup(), $requestItem->getAppId(), $fields, $values, $requestItem->getToken());
  }

  /**
   * /appdata/{userId}/{groupId}/{appId}
   * - fields={field1, field2}
   *
   * examples:
   * /appdata/john.doe/@friends/app?fields=count
   * /appdata/john.doe/@self/app
   *
   * The post data should be a regular json object. All of the fields vars will
   * be pulled from the values and set on the person object. If there are no
   * fields vars then all of the data will be overridden.
   */
  public function handlePut(RequestItem $requestItem) {
    return $this->handlePost($requestItem);
  }

  /**
   * Determines whether the input is a valid key.
   *
   * @param key the key to validate.
   * @return true if the key is a valid appdata key, false otherwise.
   */
  public static function isValidKey($key) {
    if (empty($key)) {
      return false;
    }
    for ($i = 0; $i < strlen($key); ++ $i) {
      $c = substr($key, $i, 1);
      if (($c >= 'a' && $c <= 'z') || ($c >= 'A' && $c <= 'Z') || ($c >= '0' && $c <= '9') || ($c == '-') || ($c == '_') || ($c == '.')) {
        continue;
      }
      return false;
    }
    return true;
  }
}
