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
 * Convert Atom representations to the internal data structure representation
 */
class InputAtomConverter extends InputConverter {

  public function convertPeople($requestParam) {
    throw new SocialSpiException("Operation not supported", ResponseError::$NOT_IMPLEMENTED);
  }

  public function convertActivities($requestParam) {
    $xml = InputBasicXmlConverter::loadString($requestParam);
    return InputBasicXmlConverter::convertActivities($xml, $xml->content->activity);
  }

  public function convertAppData($requestParam) {
    $xml = InputBasicXmlConverter::loadString($requestParam);
    if (! isset($xml->content) || ! isset($xml->content->appdata)) {
      throw new Exception("Mallformed AppData xml");
    }
    $data = array();
    foreach (get_object_vars($xml->content->appdata) as $key => $val) {
      $data[trim($key)] = trim($val);
    }
    return $data;
  }

  public function convertMessages($requestParam) {
    $xml = InputBasicXmlConverter::loadString($requestParam);
    return InputBasicXmlConverter::convertMessages($requestParam, $xml, $xml->content);
  }
  
  public function convertAlbums($requestParam) {
    $xml = InputBasicXmlConverter::loadString($requestParam);
    return InputBasicXmlConverter::convertAlbums($xml, $xml->content->album);
  }
  
  public function convertMediaItems($requestParam) {
    $xml = InputBasicXmlConverter::loadString($requestParam);
    return InputBasicXmlConverter::convertMediaItems($xml, $xml->content->mediaItem);
  }
}
