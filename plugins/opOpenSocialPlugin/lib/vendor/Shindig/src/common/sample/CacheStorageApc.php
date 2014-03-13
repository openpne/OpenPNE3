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

class CacheStorageApc extends CacheStorage {
  private $prefix = null;

  public function __construct($name) {
    $this->prefix = $name;
  }

  public function store($key, $value) {
    return @apc_store($this->storageKey($key), $value);
  }

  public function fetch($key) {
    return @apc_fetch($this->storageKey($key));
  }

  public function delete($key) {
    return @apc_delete($this->storageKey($key));
  }

  public function isLocked($key) {
    if ((@apc_fetch($this->storageKey($key) . '.lock')) === false) {
      return false;
    }
    return true;
  }

  public function lock($key) {
    // the interesting thing is that this could fail if the lock was created in the meantime..
    // but we'll ignore that out of convenience
    @apc_add($this->storageKey($key) . '.lock', '', 5);
  }

  public function unlock($key) {
    // suppress all warnings, if some other process removed it that's ok too
    @apc_delete($this->storageKey($key) . '.lock');
  }

  private function storageKey($key) {
    return $this->prefix . '_' . $key;
  }
}