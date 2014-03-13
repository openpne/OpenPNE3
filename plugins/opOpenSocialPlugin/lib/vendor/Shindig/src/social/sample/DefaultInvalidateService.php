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

class DefaultInvalidateService implements InvalidateService {

  /**
   * @var Cache
   */
  private $invalidationEntry;
  
  /**
   * @var Cache
   */
  private $cache;
  
  private static $marker = null;
  
  /**
   * @var Cache
   */
  private static $makerCache = null;
  
  private static $TOKEN_PREFIX = 'INV_TOK_';
  
  public function __construct(Cache $cache) {
    $this->cache = $cache;
    $this->invalidationEntry = Cache::createCache(Shindig_Config::get('data_cache'), 'InvalidationEntry');
    if (self::$makerCache == null) {
      self::$makerCache = Cache::createCache(Shindig_Config::get('data_cache'), 'MarkerCache');
      $value = self::$makerCache->expiredGet('marker');
      if ($value['found']) {
        self::$marker = $value['data'];
      } else {
        self::$marker = 0;
        self::$makerCache->set('marker', self::$marker);
      }
    }
  }
  /**
   * Invalidate a set of cached resources that are part of the application specification itself.
   * This includes gadget specs, manifests and message bundles
   * @param uris of content to invalidate
   * @param token identifying the calling application
   */
  function invalidateApplicationResources(Array $uris, SecurityToken $token) {
    foreach($uris as $uri) {
      $request = new RemoteContentRequest($uri);
      $this->cache->invalidate($request->toHash());
    }
  }

  /**
   * Invalidate all cached resources where the specified user ids were used as either the
   * owner or viewer id when a signed or OAuth request was made for the content by the application
   * identified in the security token.
   * @param opensocialIds Set of user ids to invalidate authenticated/signed content for
   * @param token identifying the calling application
   */
  function invalidateUserResources(Array $opensocialIds, SecurityToken $token) {
    foreach($opensocialIds as $opensocialId) {
      ++self::$marker;
      self::$makerCache->set('marker', self::$marker);
      $this->invalidationEntry->set($this->getKey($opensocialId, $token), self::$marker);
    }
  }

  /**
   * Is the specified request still valid. If the request is signed or authenticated
   * has its content been invalidated by a call to invalidateUserResource subsequent to the
   * response being cached.
   */
  function isValid(RemoteContentRequest $request) {
    if ($request->getAuthType() == RemoteContentRequest::$AUTH_NONE) {
      return true;
    }
    return $request->getInvalidation() == $this->getInvalidationMark($request);
  }

  /**
   * Mark the request prior to caching it so that subsequent calls to isValid can detect
   * if it has been invalidated.
   */
  function markResponse(RemoteContentRequest $request) {
    $mark = $this->getInvalidationMark($request);
    if ($mark) {
      $request->setInvalidation($mark);
    }
  }
  
  /**
   * @return string
   */
  private function getKey($userId, SecurityToken $token) {
    $pos = strrpos($userId, ':');
    if ($pos !== false) {
      $userId = substr($userId, $pos + 1);
    }
    
    if ($token->getAppId()) {
      return DefaultInvalidateService::$TOKEN_PREFIX . $token->getAppId() . '_' . $userId;
    }
  }
  
  private function getInvalidationMark(RemoteContentRequest $request) {
    $token = $request->getToken();
    if (!$token) {
      return null;
    }
    $currentInvalidation = '';
    if ($token->getOwnerId()) {
      $ownerKey = $this->getKey($token->getOwnerId(), $token);
      $cached = $this->invalidationEntry->expiredGet($ownerKey);
      $ownerStamp = $cached['found'] ? $cached['data'] : false;
    }
    if ($token->getViewerId()) {
      $viewerKey = $this->getKey($token->getViewerId(), $token);
      $cached = $this->invalidationEntry->expiredGet($viewerKey);
      $viewerStamp = $cached['found'] ? $cached['data'] : false;
    }
    if (isset($ownerStamp)) {
      $currentInvalidation = $currentInvalidation . 'o=' . $ownerStamp . ';'; 
    }
    if (isset($viewerStamp)) {
      $currentInvalidation = $currentInvalidation . 'v=' . $viewerStamp . ';'; 
    }
    return $currentInvalidation;
  }
}
