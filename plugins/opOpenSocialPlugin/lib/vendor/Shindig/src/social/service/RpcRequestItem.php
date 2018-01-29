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
 * A JSON-RPC specific implementation of RequestItem
 */
class RpcRequestItem extends RequestItem {

  private $data;

  public function __construct($rpc, SecurityToken $token) {
    if (empty($rpc['method'])) {
      throw new SocialSpiException("Missing method in RPC call");
    }
    parent::__construct($rpc['method'], $rpc['method'], $token);
    if (isset($rpc['params'])) {
      $this->data = $rpc['params'];
    } else {
      $this->data = array();
    }
  }

  public function getService($rpcMethod = null) {
    if ($rpcMethod != null) {
      return substr($rpcMethod, 0, strpos($rpcMethod, '.'));
    } else {
      return substr($this->service, 0, strpos($this->service, '.'));
    }
  }

  public function getOperation($rpcMethod = null) {
    if ($rpcMethod != null) {
      $op = substr($rpcMethod, strpos($rpcMethod, '.') + 1);
    } else {
      $op = substr($this->operation, strpos($this->operation, '.') + 1);
    }
    return $op;
  }

  public function getMethod($rpcMethod = null) {
    return $this->getOperation($rpcMethod);
  }

  public function getParameters() {
    return $this->data;
  }

  public function getParameter($paramName, $defaultValue = null) {
    if (isset($this->data[$paramName])) {
      return $this->data[$paramName];
    } else {
      return $defaultValue;
    }
  }

  public function getListParameter($paramName) {
    if (isset($this->data[$paramName])) {
      if (is_array($this->data[$paramName])) {
        return $this->data[$paramName];
      } else {
        // Allow up-conversion of non-array to array params.
        return array($this->data[$paramName]);
      }
    } else {
      return array();
    }
  }

  public function applyUrlTemplate($urlTemplate) {  // No params in the URL
  }
}
