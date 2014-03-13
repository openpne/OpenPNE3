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

require_once 'src/gadgets/ContainerConfig.php';

class SystemHandler extends DataRequestHandler {

  public function __construct() {  // do nothing, listMethods doesn't have a service implementation since it depends on the container.js configuration
  }

  public function handleItem(RequestItem $requestItem) {
    try {
      $method = strtolower($requestItem->getMethod());
      $method = 'handle' . ucfirst($method);
      $response = $this->$method($requestItem);
    } catch (SocialSpiException $e) {
      $response = new ResponseItem($e->getCode(), $e->getMessage());
    } catch (Exception $e) {
      $response = new ResponseItem(ResponseError::$INTERNAL_ERROR, "Internal error: " . $e->getMessage());
    }
    return $response;
  }

  public function handleDelete(RequestItem $request) {
    throw new SocialSpiException("Http delete not allowed for invalidation service", ResponseError::$BAD_REQUEST);
  }

  public function handlePut(RequestItem $request) {
    throw new SocialSpiException("Http put not allowed for invalidation service", ResponseError::$BAD_REQUEST);
  }

  public function handlePost(RequestItem $request) {
    throw new SocialSpiException("Http put not allowed for invalidation service", ResponseError::$BAD_REQUEST);
  }

  public function handleGet(RequestItem $request) {
    return $this->handleListMethods($request);
  }

  public function handleListMethods(RequestItem $request) {
  	$containerConfig = new ContainerConfig(Shindig_Config::get('container_path'));
  	$gadgetConfig = $containerConfig->getConfig('default', 'gadgets.features');
    if (! isset($gadgetConfig['osapi.services']) || count($gadgetConfig['osapi.services']) == 1) {
      // this should really be set in config/container.js, but if not, we build a complete default set so at least most of it works out-of-the-box
      $gadgetConfig['osapi.services'] = array(
          'gadgets.rpc' => array('container.listMethods'),
          'http://%host%/social/rpc' => array("messages.update", "albums.update",
              "activities.delete", "activities.update",
              "activities.supportedFields", "albums.get",
              "activities.get", "mediaitems.update",
              "messages.get", "appdata.get",
              "system.listMethods", "people.supportedFields",
              "messages.create", "mediaitems.delete",
              "mediaitems.create", "people.get", "people.create",
              "albums.delete", "messages.delete",
              "appdata.update", "activities.create",
              "mediaitems.get", "albums.create",
              "appdata.delete", "people.update",
              "appdata.create"),
          'http://%host%/gadgets/api/rpc' => array('cache.invalidate'));
    }
	  return $gadgetConfig['osapi.services'];
  }
}
