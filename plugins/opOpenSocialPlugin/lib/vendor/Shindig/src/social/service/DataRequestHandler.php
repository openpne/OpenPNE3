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

abstract class DataRequestHandler {
  protected $service;
  
  public function __construct($serviceName) {
    try {
      $service = trim(Shindig_Config::get($serviceName));
      if (!empty($service)) {
        $this->service = new $service();
      }
    } catch (ConfigException $e) {
      // Do nothing. If service name is not specified in the config file.
      // All the requests to the handler will throw not implemented exception.
      // The handler function should invoke checkService method before serving. 
    }
  }
  
  private static $GET_SYNONYMS = array("get");
  private static $CREATE_SYNONYMS = array("post", "create");
  private static $UPDATE_SYNONYMS = array("put", "update");
  private static $DELETE_SYNONYMS = array("delete");

  public function handleItem(RequestItem $requestItem) {
    try {
      $token = $requestItem->getToken();
      $method = strtolower($requestItem->getMethod());
      if ($token->isAnonymous() && ! in_array($method, self::$GET_SYNONYMS)) {
        // Anonymous requests are only allowed to GET data (not create/edit/delete)
        throw new SocialSpiException("[$method] not allowed for anonymous users", ResponseError::$BAD_REQUEST);
      } elseif (in_array($method, self::$GET_SYNONYMS)) {
        $parameters = $requestItem->getParameters();
        if (in_array("@supportedFields", $parameters)) {
          $response = $this->getSupportedFields($parameters);
        } else {
          $response = $this->handleGet($requestItem);
        }
      } elseif (in_array($method, self::$UPDATE_SYNONYMS)) {
        $response = $this->handlePut($requestItem);
      } elseif (in_array($method, self::$DELETE_SYNONYMS)) {
        $response = $this->handleDelete($requestItem);
      } elseif (in_array($method, self::$CREATE_SYNONYMS)) {
        $response = $this->handlePost($requestItem);
      } else {
        throw new SocialSpiException("Unserviced Http method type", ResponseError::$BAD_REQUEST);
      }
    } catch (SocialSpiException $e) {
      $response = new ResponseItem($e->getCode(), $e->getMessage());
    } catch (Exception $e) {
      $response = new ResponseItem(ResponseError::$INTERNAL_ERROR, "Internal error: " . $e->getMessage());
    }
    return $response;
  }

  static public function getAppId($appId, SecurityToken $token) {
    if ($appId == '@app') {
      return $token->getAppId();
    } else {
      return $appId;
    }
  }

  static public function convertToObject($string) {
    //TODO should detect if it's atom/xml or json here really. assuming json for now
    $decoded = json_decode($string);
    if ($decoded == $string) {
      throw new Exception("Invalid JSON syntax");
    }
    return $decoded;
  }

  /**
   *  To support people/@supportedFields and activity/@supportedFields
   *  @param parameters url parameters to get request type(people/activity)
   */
  public function getSupportedFields($parameters) {
    $context = new GadgetContext('GADGET');
    $container = $context->getContainer();
    $containerConfig = new ContainerConfig(Shindig_Config::get('container_path'));
    $config = $containerConfig->getConfig($container, 'gadgets.features');
    $version = $this->getOpenSocialVersion($config);
    $supportedFields = $config[$version]['supportedFields'];
    if (in_array('people', $parameters)) {
      $ret = $supportedFields['person'];
    } else {
      $ret = $supportedFields['activity'];
    }
    return new ResponseItem(null, null, $ret);
  }

  /**
   *  To get OpenSocial version for getting supportedFields
   *  @param config configuration values from container's js files
   */
  private function getOpenSocialVersion($config) {
    $str = "opensocial-";
    $version = array();
    foreach ($config as $key => $value) {
      if (substr($str, 0, strlen($key)) == $str) {
        $version[] = $key;
      }
    }
    if (! count($version)) {
      throw new Exception("Invalid container configuration, opensocial-x.y key not found");
    }
    rsort($version);
    return $version[0];
  }
  
  /**
   * Checks whether the service is initialized.
   */
  protected function checkService() {
    if (!$this->service) {
      throw new SocialSpiException("Not Implemented.", ResponseError::$NOT_IMPLEMENTED);
    }
  }

  abstract public function handleDelete(RequestItem $requestItem);

  abstract public function handleGet(RequestItem $requestItem);

  abstract public function handlePost(RequestItem $requestItem);

  abstract public function handlePut(RequestItem $requestItem);
}
