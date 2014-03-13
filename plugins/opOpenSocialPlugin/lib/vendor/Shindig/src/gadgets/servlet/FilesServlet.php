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

require 'src/common/HttpServlet.php';

/**
 * This class serves files from the shindig_root/javascript directory, it was created
 * so that the shindig examples and javascript files would work out of the box with
 * the php version too
 */
class FilesServlet extends HttpServlet {

  /**
   * Handles the get file request, if the file exists and is in the correct
   * location it's echo'd to the browser (with a basic content type guessing
   * based on the file extention, ie .js becomes text/javascript).
   * If the file location falls outside of the shindig/javascript root a
   * 400 Bad Request is returned, and if the file is inside of the root
   * but doesn't exist a 404 error is returned
   */
  public function doGet() {
    $file = str_replace(Shindig_Config::get('web_prefix') . '/gadgets/files/', '', $_SERVER["REQUEST_URI"]);
    $file = Shindig_Config::get('javascript_path') . $file;
    // make sure that the real path name is actually in the javascript_path, so people can't abuse this to read
    // your private data from disk .. otherwise this would be a huge privacy and security issue 
    if (substr(realpath($file), 0, strlen(realpath(Shindig_Config::get('javascript_path')))) != realpath(Shindig_Config::get('javascript_path'))) {
      header("HTTP/1.0 400 Bad Request", true);
      echo "<html><body><h1>400 - Bad Request</h1></body></html>";
      die();
    }
    // if the file doesn't exist or can't be read, give a 404 error
    if (! file_exists($file) || ! is_readable($file) || ! is_file($file)) {
      header("HTTP/1.0 404 Not Found", true);
      echo "<html><body><h1>404 - Not Found</h1></body></html>";
      die();
    }
    $dot = strrpos($file, '.');
    if ($dot) {
      $ext = strtolower(substr($file, $dot + 1));
      if ($ext == 'html' || $ext == 'htm') {
        $this->setContentType('text/html');
      } elseif ($ext == 'js') {
        $this->setContentType('text/javascript');
      } elseif ($ext == 'css') {
        $this->setContentType('text/css');
      } elseif ($ext == 'xml') {
        $this->setContentType('text/xml');
      } elseif ($ext == 'png') {
        $this->setContentType('image/png');
      } elseif ($ext == 'gif') {
        $this->setContentType('image/gif');
      } elseif ($ext == 'jpg' || $ext == 'jpeg') {
        $this->setContentType('image/jpeg');
      }
    }
    $this->setCharset('');
    $this->setLastModified(filemtime($file));
    readfile($file);
  }
}
