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
 * Convert json representations to the internal data structure representation
 */
class InputJsonConverter extends InputConverter {

  public function convertPeople($requestParam) {
    throw new Exception("Opperation not supported");
  }

  public function convertActivities($requestParam) {
    $ret = json_decode($requestParam, true);
    if ($ret == $requestParam) {
      throw new Exception("Mallformed activity json string");
    }
    return $ret;
  }

  public function convertAppData($requestParam) {
    $ret = json_decode($requestParam, true);
    if ($ret == $requestParam) {
      throw new Exception("Mallformed app data json string");
    }
    return $ret;
  }

  public function convertJsonBatch($requestParam) {
    $ret = json_decode($requestParam, true);
    if ($ret == $requestParam) {
      throw new Exception("Mallformed json batch string");
    }
    return $ret;
  }

  public function convertMessages($requestParam) {
    $ret = json_decode($requestParam, true);
    if ($ret == $requestParam) {
      throw new Exception("Mallformed message string");
    }
    return $ret;
  }
  
  public function convertAlbums($requestParam) {
    $ret = json_decode($requestParam, true);
    if ($ret == $requestParam) {
      throw new Exception("Mallformed album json string. " . $requestParam);
    }
    return $ret;
  }
  
  public function convertMediaItems($requestParam) {
    $ret = json_decode($requestParam, true);
    if ($ret == $requestParam) {
      throw new Exception("Mallformed album json string. " . $requestParam);
    }
    return $ret;
  }
}
