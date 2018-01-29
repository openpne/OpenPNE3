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

class CacheStorageMemcache extends CacheStorage {
  /**
   * @var Memcache
   */
  private static $memcache = null;

  private $prefix = null;

  public function __construct($name) {
    $this->prefix = $name;
    if (!self::$memcache) {
      self::$memcache = new Memcache();
      $host = Shindig_Config::get('cache_host');
      $port = Shindig_Config::get('cache_port');
      if (Shindig_Config::get('cache_memcache_pconnect')) {
        if (!self::$memcache->pconnect($host, $port)) {
          throw new CacheException("Couldn't connect to memcache server");
        }
      } else {
        if (!self::$memcache->connect($host, $port)) {
          throw new CacheException("Couldn't connect to memcache server");
        }
      }
    }
  }

  public function isLocked($key) {
    if ((@self::$memcache->get($this->storageKey($key) . '.lock')) === false) {
      return false;
    }
    return true;
  }

  public function lock($key) {
    // the interesting thing is that this could fail if the lock was created in the meantime..
    // but we'll ignore that out of convenience
    @self::$memcache->add($this->storageKey($key) . '.lock', '', 0, 2);
  }

  public function unlock($key) {
    // suppress all warnings, if some other process removed it that's ok too
    @self::$memcache->delete($this->storageKey($key) . '.lock');
  }

  public function store($key, $value) {
    return self::$memcache->set($this->storageKey($key), $value, false, 0);
  }

  public function fetch($key) {
    return self::$memcache->get($this->storageKey($key));
  }

  public function delete($key) {
    if (!@self::$memcache->delete($this->storageKey($key))) {
      throw new CacheException("Cache memcache could not be deleted");
    }
  }

  private function storageKey($key) {
    return $this->prefix . '_' . $key;
  }
}