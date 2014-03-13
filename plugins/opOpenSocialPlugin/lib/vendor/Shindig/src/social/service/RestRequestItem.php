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
 * Represents the request items that come from the restful request.
 */
class RestRequestItem extends RequestItem {
  private $url;
  private $params;
  
  /**
   * @var InputConverter
   */
  private $inputConverter;
  
  /**
   * @var OutputConverter
   */
  private $outputConverter;
  private $postData;

  public function __construct($service, $method, SecurityToken $token, $inputConverter, $outputConverter) {
    parent::__construct($service, $method, $token);
    $this->inputConverter = $inputConverter;
    $this->outputConverter = $outputConverter;
  }

  /**
   * @return RestRequestItem
   */
  public static function createWithRequest($servletRequest, $token, $inputConverter, $outputConverter) {
    $restfulRequestItem = new RestRequestItem(self::getServiceFromPath($servletRequest['url']), self::getMethod(), $token, $inputConverter, $outputConverter);
    $restfulRequestItem->setUrl($servletRequest['url']);
    if (isset($servletRequest['params'])) {
      $restfulRequestItem->setParams($servletRequest['params']);
    } else {
      $paramPieces = parse_url($restfulRequestItem->url);
      if (isset($paramPieces['query'])) {
        $params = array();
        parse_str($paramPieces['query'], $params);
        $restfulRequestItem->setParams($params);
      }
    }
    if (isset($servletRequest['postData'])) {
      $restfulRequestItem->setPostData($servletRequest['postData']);
    }
    return $restfulRequestItem;
  }

  public function setUrl($url) {
    $this->url = $url;
  }

  public function setParams($params) {
    $this->params = $params;
  }

  public function setPostData($postData) {
    $this->postData = $postData;
    $service = $this->getServiceFromPath($this->url);
    switch ($service) {
      case DataServiceServlet::$PEOPLE_ROUTE:
        // in our current implementation this will throw a SocialSPIException since we don't support 
        // adding people/friendships in our API yet, but this might be added some day
        $data = $this->inputConverter->convertPeople($this->postData);
        break;
      case DataServiceServlet::$ACTIVITY_ROUTE:
        $data = $this->inputConverter->convertActivities($this->postData);
        $this->params['activity'] = $data;
        break;
      case DataServiceServlet::$APPDATA_ROUTE:
        $data = $this->inputConverter->convertAppData($this->postData);
        $this->params['data'] = $data;
        break;
      case DataServiceServlet::$MESSAGE_ROUTE:
        $data = $this->inputConverter->convertMessages($this->postData);
        // 'entity' may be a message or a message collection.
        $this->params['entity'] = $data;
        break;
      case DataServiceServlet::$INVALIDATE_ROUTE:
        $this->params = json_decode($this->postData, true);
        break;
      case DataServiceServlet::$ALBUM_ROUTE:
        $data = $this->inputConverter->convertAlbums($this->postData);
        $this->params['album'] = $data;
        break;
      case DataServiceServlet::$MEDIA_ITEM_ROUTE:
        $data = $this->inputConverter->convertMediaItems($this->postData);
        $this->params['mediaItem'] = $data;
        break;
      default:
        throw new Exception("Invalid or unknown service endpoint: $service");
        break;
    }
  
  }
  
  /**
   * '/people/@me/@self' => 'people'
   * '/invalidate?invalidationKey=1' => 'invalidate'
   */
  static function getServiceFromPath($pathInfo) {
    $pathInfo = substr($pathInfo, 1);
    $indexOfNextPathSeparator = strpos($pathInfo, '/');
    $indexOfNextQuestionMark = strpos($pathInfo, '?');
    if ($indexOfNextPathSeparator !== false && $indexOfNextQuestionMark !== false) {
      return substr($pathInfo, 0, min($indexOfNextPathSeparator, $indexOfNextQuestionMark));
    }
    if ($indexOfNextPathSeparator !== false) {
      return substr($pathInfo, 0, $indexOfNextPathSeparator);
    }
    if ($indexOfNextQuestionMark !== false) {
      return substr($pathInfo, 0, $indexOfNextQuestionMark);
    }
    return $pathInfo;
  }

  static function getMethod() {
    if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
      return $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
    } else {
      return $_SERVER['REQUEST_METHOD'];
    }
  }

  /**
   * This could definitely be cleaner..
   * TODO: Come up with a cleaner way to handle all of this code.
   *
   * @param urlTemplate The template the url follows
   */
  public function applyUrlTemplate($urlTemplate) {
    $paramPieces = @parse_url($this->url);
    $actualUrl = explode("/", $paramPieces['path']);
    $expectedUrl = explode("/", $urlTemplate);
    for ($i = 1; $i < count($actualUrl); $i ++) {
      $actualPart = isset($actualUrl[$i]) ? $actualUrl[$i] : null;
      $expectedPart = isset($expectedUrl[$i]) ? $expectedUrl[$i] : null;
      if (strpos($expectedPart, "{") !== false) {
        $this->params[preg_replace('/\{|\}/', '', $expectedPart)] = explode(',', $actualPart);
      } elseif (strpos($actualPart, ',') !== false) {
        throw new IllegalArgumentException("Cannot expect plural value " + $actualPart + " for singular field " + $expectedPart + " in " + $this->url);
      } else {
        $this->params[$expectedPart] = $actualPart;
      }
    }
  }

  public function getParameters() {
    return $this->params;
  }

  public function setParameter($paramName, $paramValue) {
    // Ignore nulls
    if ($paramValue == null) {
      return;
    }
    $this->params[$paramName] = $paramValue;
  }

  /**
   * Return a single param value
   */
  public function getParameter($paramName, $defaultValue = null) {
    $paramValue = isset($this->params[$paramName]) ? $this->params[$paramName] : null;
    if ($paramValue != null && ! empty($paramValue)) {
      return $paramValue;
    }
    return $defaultValue;
  }

  /**
   * Return a list param value
   */
  public function getListParameter($paramName) {
    $stringList = isset($this->params[$paramName]) ? $this->params[$paramName] : null;
    if ($stringList == null) {
      return array();
    } elseif (is_array($stringList)) {
      // already converted to array, return straight away
      return $stringList;
    }
    if (strpos($stringList, ',') !== false) {
      $stringList = explode(',', $stringList);
    } else {
      // Allow up-conversion of non-array to array params.
      $stringList = array($stringList);
    }
    $this->params[$paramName] = $stringList;
    return $stringList;
  }
}
