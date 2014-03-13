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
require 'src/gadgets/GadgetContext.php';
require 'src/gadgets/ProxyBase.php';
require 'src/gadgets/ProxyHandler.php';
require 'src/common/RemoteContentRequest.php';
require 'src/common/RemoteContent.php';
require 'src/common/Cache.php';
require 'src/common/RemoteContentFetcher.php';

class ProxyServlet extends HttpServlet {

  public function doGet() {
    try {
      // Make sure the HttpServlet doesn't overwrite our headers
      $this->noHeaders = true;
      $context = new GadgetContext('GADGET');
      $url = (isset($_GET['url']) ? $_GET['url'] : (isset($_POST['url']) ? $_POST['url'] : false));
      $url = urldecode($url);
      if (! $url) {
        header("HTTP/1.0 400 Bad Request", true);
        echo "<html><body><h1>400 - Missing url parameter</h1></body></html>";
      }
      $proxyHandler = new ProxyHandler($context);
      $proxyHandler->fetch($url);
    } catch (Exception $e) {
      // catch all exceptions and give a 500 server error
      header("HTTP/1.0 500 Internal Server Error");
      echo "<h1>Internal server error</h1><p>" . $e->getMessage() . "</p>";
    }
  }

  public function doPost() {
    $this->doGet();
  }
}
