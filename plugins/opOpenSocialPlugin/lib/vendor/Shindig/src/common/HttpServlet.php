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


/*
 * This is a somewhat liberal interpretation of the HttpServlet class
 * Mixed with some essentials to make propper http header handling
 * happen in php.
 */
class HttpServlet {
  private $lastModified = false;
  private $contentType = 'text/html';
  private $charset = 'UTF-8';
  private $noCache = false;
  private $cacheTime;
  public $noHeaders = false;

  /**
   * Enables output buffering so we can do correct header handling in the destructor
   *
   */
  public function __construct() {
    // set our default cache time (config cache time defaults to 24 hours aka 1 day)
    $this->cacheTime = Shindig_Config::get('cache_time');
    // to do our header magic, we need output buffering on
    ob_start();
  }

  /**
   * Code ran after the event handler, adds headers etc to the request
   * If noHeaders is false, it adds all the correct http/1.1 headers to the request
   * and deals with modified/expires/e-tags/etc. This makes the server behave more like
   * a real http server.
   */
  public function __destruct() {
    if (! $this->noHeaders) {
      header("Content-Type: $this->contentType" . (! empty($this->charset) ? "; charset={$this->charset}" : ''));
      header('Accept-Ranges: bytes');
      if ($this->noCache) {
        header("Cache-Control: no-cache, must-revalidate", true);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT", true);
      } else {
        // attempt at some propper header handling from php
        // this departs a little from the shindig code but it should give is valid http protocol handling
        header('Cache-Control: public,max-age=' . $this->cacheTime, true);
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + $this->cacheTime) . " GMT", true);
        // Obey browsers (or proxy's) request to send a fresh copy if we recieve a no-cache pragma or cache-control request
        if (! isset($_SERVER['HTTP_PRAGMA']) || ! strstr(strtolower($_SERVER['HTTP_PRAGMA']), 'no-cache') && (! isset($_SERVER['HTTP_CACHE_CONTROL']) || ! strstr(strtolower($_SERVER['HTTP_CACHE_CONTROL']), 'no-cache'))) {
          if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $this->lastModified && ! isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            $if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
            if ($this->lastModified <= $if_modified_since) {
              header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $this->lastModified) . ' GMT', true);
              header("HTTP/1.1 304 Not Modified", true);
              header('Content-Length: 0', true);
              ob_end_clean();
              die();
            }
          }
          header('Last-Modified: ' . gmdate('D, d M Y H:i:s', ($this->lastModified ? $this->lastModified : time())) . ' GMT', true);
        }
      }
    }
  }

  public function getCharset() {
    return $this->charset;
  }

  public function setCharset($charset) {
    $this->charset = $charset;
  }

  /**
   * Sets the time in seconds that the browser's cache should be
   * considered out of date (through the Expires header)
   *
   * @param int $time time in seconds
   */
  public function setCacheTime($time) {
    $this->cacheTime = $time;
  }

  /**
   * Returns the time in seconds that the browser is allowed to cache the content
   *
   * @return int $time
   */
  public function getCacheTime() {
    return $this->cacheTime;
  }

  /**
   * Sets the content type of this request (forinstance: text/html or text/javascript, etc)
   *
   * @param string $type content type header to use
   */
  public function setContentType($type) {
    $this->contentType = $type;
  }

  /**
   * Returns the current content type
   *
   * @return string content type string
   */
  public function getContentType() {
    return $this->contentType;
  }

  /**
   * returns the current last modified time stamp
   *
   * @return int timestamp
   */
  public function getLastModified() {
    return $this->lastModified;
  }

  /**
   * Sets the last modified timestamp. It automaticly checks if this timestamp
   * is larger then its current timestamp, and if not ignores the call
   *
   * @param int $modified timestamp
   */
  public function setLastModified($modified) {
    $this->lastModified = max($this->lastModified, $modified);
  }

  /**
   * Sets the noCache boolean. If its set to true, no-caching headers will be send
   * (pragma no cache, expiration in the past)
   *
   * @param boolean $cache send no-cache headers?
   */
  public function setNoCache($cache = false) {
    $this->noCache = $cache;
  }

  /**
   * returns the noCache boolean
   *
   * @return boolean
   */
  public function getNoCache() {
    return $this->noCache;
  }
}
