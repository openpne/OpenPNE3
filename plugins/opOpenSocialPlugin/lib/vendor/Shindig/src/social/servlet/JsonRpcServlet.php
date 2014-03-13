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
 * JSON-RPC handler servlet.
 */
class JsonRpcServlet extends ApiServlet {

  /**
   * Single request through GET
   * http://api.example.org/rpc?method=people.get&id=myself&userid=@me&groupid=@self
   */
  public function doGet() {
    $token = $this->getSecurityToken();
    if ($token == null) {
      $this->sendSecurityError();
      return;
    }
    // Request object == GET params
    $request = $_GET;
    $this->dispatch($request, $token);
  }

  /**
   * RPC Post request
   */
  public function doPost() {
    $token = $this->getSecurityToken();
    if ($token == null || $token == false) {
      $this->sendSecurityError();
      return;
    }
    if (isset($GLOBALS['HTTP_RAW_POST_DATA']) || isset($_POST['request'])) {
      $requestParam = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : (get_magic_quotes_gpc() ? stripslashes($_POST['request']) : $_POST['request']);
      $request = json_decode($requestParam, true);
      if ($request == $requestParam) {
        throw new InvalidArgumentException("Malformed json string");
      }
    } else {
      throw new InvalidArgumentException("Missing POST data");
    }
    if ((strpos($requestParam, '[') !== false) && strpos($requestParam, '[') < strpos($requestParam, '{')) {
      // Is a batch
      $this->dispatchBatch($request, $token);
    } else {
      $this->dispatch($request, $token);
    }
  }

  public function dispatchBatch($batch, $token) {
    $responses = array();
    // Gather all Futures.  We do this up front so that
    // the first call to get() comes after all futures are created,
    // which allows for implementations that batch multiple Futures
    // into single requests.
    for ($i = 0; $i < count($batch); $i ++) {
      $batchObj = $batch[$i];
      $requestItem = new RpcRequestItem($batchObj, $token);
      $responses[$i] = $this->handleRequestItem($requestItem);
    }
    // Resolve each Future into a response.
    // TODO: should use shared deadline across each request
    $result = array();
    for ($i = 0; $i < count($batch); $i ++) {
      $batchObj = $batch[$i];
      $key = isset($batchObj["id"]) ? $batchObj["id"] : null;
      $responseItem = $this->getJSONResponse($key, $this->getResponseItem($responses[$i]));
      $result[] = $responseItem;
    }
    echo json_encode($result);
  }

  public function dispatch($request, $token) {
    $key = null;
    if (isset($request["id"])) {
      $key = $request["id"];
    }
    $requestItem = new RpcRequestItem($request, $token);
    // Resolve each Future into a response.
    // TODO: should use shared deadline across each request
    $response = $this->getResponseItem($this->handleRequestItem($requestItem));
    $result = $this->getJSONResponse($key, $response);
    echo json_encode($result);
  }

  private function getJSONResponse($key, ResponseItem $responseItem) {
    $result = array();
    if ($key != null) {
      $result["id"] = $key;
    }
    if ($responseItem->getError() != null) {
      $result["error"] = $this->getErrorJson($responseItem);
    } else {
      $response = $responseItem->getResponse();
      $converted = $response;
      if ($response instanceof RestfulCollection) {
        // FIXME this is a little hacky because of the field names in the RestfulCollection
        $converted->list = $converted->entry;
        unset($converted->entry);
        $result['data'] = $converted;
      } elseif ($response instanceof DataCollection) {
        $result["data"] = $converted->getEntry();
      } else {
        $result["data"] = $converted;
      }
    }
    return $result;
  }

  // TODO(doll): Refactor the responseItem so that the fields on it line up with this format.
  // Then we can use the general converter to output the response to the client and we won't
  // be harcoded to json.
  private function getErrorJson(ResponseItem $responseItem) {
    $error = array();
    $error["code"] = $responseItem->getError();
    $error["message"] = $responseItem->getErrorMessage();
    return $error;
  }

  public function sendError(ResponseItem $responseItem) {
    $error = $this->getErrorJson($responseItem);
    echo json_encode($error);
  }

  private function sendBadRequest($t, $response) {
    $this->sendError($response, new ResponseItem(ResponseError::$BAD_REQUEST, "Invalid batch - " + $t->getMessage()));
  }
}
