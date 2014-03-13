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

class CacheException extends Exception {
}

class RequestTime {

  public function getRequestTime() {
    return $_SERVER['REQUEST_TIME'];
  }
}

class Cache {
  /**
   * @var RequestTime
   */
  private $time = null;
  
  /**
   * @var CacheStorage
   */
  private $storage = null;

  /**
   * @return Cache
   */
  static public function createCache($cacheClass, $name, RequestTime $time = null) {
    return new Cache($cacheClass, $name, $time);
  }

  private function __construct($cacheClass, $name, RequestTime $time = null) {
    $this->storage = new $cacheClass($name);
    if ($time == null) {
      $this->time = new RequestTime();
    } else {
      $this->time = $time;
    }
  }

  public function get($key) {
    if ($this->storage->isLocked($key)) {
      $this->storage->waitForLock($key);
    }
    $data = $this->storage->fetch($key);
    if ($data) {
      $data = unserialize($data);
      if ($data['valid'] && ($this->time->getRequestTime() - $data['time']) < $data['ttl']) {
        return $data['data'];
      }
    }
    return false;
  }

  public function expiredGet($key) {
    if ($this->storage->isLocked($key)) {
      $this->storage->waitForLock($key);
    }
    $data = $this->storage->fetch($key);
    if ($data) {
      $data = unserialize($data);
      return array_merge(array('found' => true), $data);
    }
    return array('found' => false);
  }

  public function set($key, $value, $ttl = false) {
    if (! $ttl) {
      $ttl = Shindig_Config::Get('cache_time');
    }
    if ($this->storage->isLocked($key)) {
      $this->storage->waitForLock($key);
    }
    $data = serialize(array('time' => $this->time->getRequestTime(), 'ttl' => $ttl, 'valid' => true, 'data' => $value));
    $this->storage->lock($key);
    try {
      $this->storage->store($key, $data);
      $this->storage->unlock($key);
    } catch (Exception $e) {
      $this->storage->unlock($key);
      throw $e;
    }
  }

  public function delete($key) {
    if ($this->storage->isLocked($key)) {
      $this->storage->waitForLock($key);
    }
    $this->storage->lock($key);
    $this->storage->delete($key);
    $this->storage->unlock($key);
  }

  public function invalidate($key) {
    if ($this->storage->isLocked($key)) {
      $this->storage->waitForLock($key);
    }
    $this->storage->lock($key);
    try {
      $data = $this->storage->fetch($key);
      if ($data) {
        $data = unserialize($data);
        $data = serialize(array('time' => $data['time'], 'ttl' => $data['ttl'], 'valid' => false, 'data' => $data['data']));
        $this->storage->store($key, $data);
      }
      $this->storage->unlock($key);
    } catch (Exception $e) {
      $this->storage->unlock($key);
      throw $e;
    }
  }
}