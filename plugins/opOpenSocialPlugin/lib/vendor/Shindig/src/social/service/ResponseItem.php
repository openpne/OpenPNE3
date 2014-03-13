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
 * Represents the response items that get handed back as json within the
 * DataResponse
 */
class ResponseItem {
  public $error;
  public $errorMessage;
  public $response;

  public function __construct($error = null, $errorMessage = null, $response = null) {
    $this->error = $error;
    $this->errorMessage = $errorMessage;
    $this->response = $this->trimResponse($response);
    if ($this->error === null && $this->errorMessage === null) {
      // trim null values of self too
      unset($this->error);
      unset($this->errorMessage);
    }
  }

  /**
   * the json_encode function does not trim null values,
   * so we do this manually
   *
   * @param mixed $object
   */
  private function trimResponse(&$object) {
    if (is_array($object)) {
      foreach ($object as $key => $val) {
        // binary compare, otherwise false == 0 == null too
        if ($val === null) {
          unset($object[$key]);
        } elseif (is_array($val) || is_object($val)) {
          $object[$key] = $this->trimResponse($val);
        }
      }
    } elseif (is_object($object)) {
      $vars = get_object_vars($object);
      foreach ($vars as $key => $val) {
        if ($val === null) {
          unset($object->$key);
        } elseif (is_array($val) || is_object($val)) {
          $object->$key = $this->trimResponse($val);
        }
      }
    }
    return $object;
  }

  public function getError() {
    return isset($this->error) ? $this->error : null;
  }

  public function setError($error) {
    $this->error = $error;
  }

  public function getErrorMessage() {
    return $this->errorMessage;
  }

  public function setErrorMessage($errorMessage) {
    $this->errorMessage = $errorMessage;
  }

  public function getResponse() {
    return $this->response;
  }

  public function setResponse($response) {
    $this->response = $response;
  }
}
