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

class ConfigException extends Exception {
}

/**
 * Configuration class. It uses the keys/values from config/container.php
 * and (if the file exists) config/local.php.
 */
class Shindig_Config {
  private static $config = false;

  static private function loadConfig() {
    global $shindigConfig;
    if (! self::$config) {
      // load default configuration
      include_once 'config/container.php';
      self::$config = $shindigConfig;
      if (file_exists(dirname(__FILE__).'/../../config/local.php')) {
        // include local.php if it exists and merge the config arrays.
        // the second array values overwrites the first one's
        include_once 'config/local.php';
        self::$config = array_merge(self::$config, $shindigConfig);
      }
    }
  }

  static function get($key) {
    if (! self::$config) {
      self::loadConfig();
    }
    if (isset(self::$config[$key])) {
      return self::$config[$key];
    } else {
      throw new ConfigException("Invalid Config Key");
    }
  }
}
