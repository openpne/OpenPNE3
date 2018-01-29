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

class ContainerConfig {
  public $default_container = 'default';
  public $container_key = 'gadgets.container';
  private $config = array();

  public function __construct($defaultContainer) {
    if (! empty($defaultContainer)) {
      $this->loadContainers($defaultContainer);
    }
  }

  private function loadContainers($containers) {
    if (! Shindig_File::exists($containers)) {
      throw new Exception("Invalid container path");
    }
    foreach (glob("$containers/*.js") as $file) {
      if (! Shindig_File::readable($file)) {
        throw new Exception("Could not read container config: $file");
      }
      if (is_dir($file)) {
        // support recursive loading of sub directories
        $this->loadContainers($file);
      } else {
        $this->loadFromFile($file);
      }
    }
  }

  private function loadFromFile($file) {
    $contents = file_get_contents($file);
    $contents = self::removeComments($contents);
    $config = json_decode($contents, true);
    if ($config == $contents) {
      throw new Exception("Failed to json_decode the container configuration");
    }
    if (! isset($config[$this->container_key][0])) {
      throw new Exception("No gadgets.container value set for current container");
    }
    $container = $config[$this->container_key][0];
    $this->config[$container] = array();
    foreach ($config as $key => $val) {
      $this->config[$container][$key] = $val;
    }
  }

  public static function removeComments($str) {
    // remove /* */ style comments
    $str = preg_replace('@/\\*.*?\\*/@s', '', $str);
    // remove // style comments, but keep 'http://' 'https://' and '"//'
    // for example: "gadgets.oauthGadgetCallbackTemplate" : "//%host%/gadgets/oauthcallback"
    $str = preg_replace('/[^http:\/\/|^https:\/\/|"\/\/]\/\/.*$/m', '', $str);
    return $str;
  }

  public function getConfig($container, $name) {
    $config = array();
    if (isset($this->config[$container]) && isset($this->config[$container][$name])) {
      $config = $this->config[$container][$name];
    }
    if ($container != $this->default_container && isset($this->config[$this->default_container]) && isset($this->config[$this->default_container][$name])) {
      $config = $this->mergeConfig($this->config[$this->default_container][$name], $config);
    }
    return $config;
  }

  // Code sniplet borrowed from: http://nl.php.net/manual/en/function.array-merge-recursive.php#81409
  // default array merge recursive doesn't overwrite values, but creates multiple elementents for that key,
  // which is not what we want here, we want array_merge like behavior
  private function mergeConfig() // $array1, $array2, etc
{
    $arrays = func_get_args();
    $narrays = count($arrays);
    for ($i = 0; $i < $narrays; $i ++) {
      if (! is_array($arrays[$i])) {
        trigger_error('Argument #' . ($i + 1) . ' is not an array - trying to merge array with scalar! Returning null!', E_USER_WARNING);
        return null;
      }
    }
    $ret = $arrays[0];
    for ($i = 1; $i < $narrays; $i ++) {
      foreach ($arrays[$i] as $key => $value) {
        if (((string)$key) === ((string)intval($key))) { // integer or string as integer key - append
          $ret[] = $value;
        } else {
          if (is_array($value) && isset($ret[$key])) {
            $ret[$key] = $this->mergeConfig($ret[$key], $value);
          } else {
            $ret[$key] = $value;
          }
        }
      }
    }
    return $ret;
  }
}
