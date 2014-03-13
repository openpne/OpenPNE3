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
 * The ProxyHandler class does the actual proxy'ing work. it deals both with
 * GET and POST based input, and peforms a request based on the input, headers and
 * httpmethod params.
 *
 */
class ProxyHandler extends ProxyBase {

  /**
   * Fetches the content and returns it as-is using the headers as returned
   * by the remote host.
   *
   * @param string $url the url to retrieve
   */
  public function fetch($url) {
    $url = $this->validateUrl($url);
    $request = $this->buildRequest($url, 'GET');
    $request->getOptions()->ignoreCache = $this->context->getIgnoreCache();
    $result = $this->context->getHttpFetcher()->fetch($request);
    $httpCode = (int)$result->getHttpCode();
    $isShockwaveFlash = false;
    foreach ($result->getResponseHeaders() as $key => $val) {
      if (! in_array($key, $this->disallowedHeaders)) {
        header("$key: $val", true);
      }
      if ($key == 'Content-Type' && strtolower($val) == 'application/x-shockwave-flash') {
        // We're skipping the content disposition header for flash due to an issue with Flash player 10
        // This does make some sites a higher value phishing target, but this can be mitigated by
        // additional referer checks.
        $isShockwaveFlash = true;
      }
    }
    if (! $isShockwaveFlash) {
      header('Content-Disposition: attachment;filename=p.txt');
    }
    $lastModified = $result->getResponseHeader('Last-Modified') != null ? $result->getResponseHeader('Last-Modified') : gmdate('D, d M Y H:i:s', $result->getCreated()) . ' GMT';
    $notModified = false;
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $lastModified && ! isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
      $if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
      // Use the request's Last-Modified, otherwise fall back on our internal time keeping (the time the request was created)
      $lastModified = strtotime($lastModified);
      if ($lastModified <= $if_modified_since) {
        $notModified = true;
      }
    }
    if ($httpCode == 200) {
      // only set caching headers if the result was 'OK'
      $this->setCachingHeaders($lastModified);
      // was the &gadget=<gadget url> specified in the request? if so parse it and check the rewrite settings
      if (isset($_GET['gadget'])) {
        $this->rewriteContent($_GET['gadget'], $result);
      }
    }
    // If the cached file time is within the refreshInterval params value, return not-modified
    if ($notModified) {
      header('HTTP/1.0 304 Not Modified', true);
      header('Content-Length: 0', true);
    } else {
      header("HTTP/1.1 $httpCode ".$result->getHttpCodeMsg());
      // then echo the content
      echo $result->getResponseContent();
    }
  }

  private function rewriteContent($gadgetUrl, RemoteContentRequest &$result) {
    try {
      // At the moment we're only able to rewrite CSS files, so check the content type and/or the file extension before rewriting
      $headers = $result->getResponseHeaders();
      $isCss = false;
      if (isset($headers['Content-Type']) && strtolower($headers['Content-Type'] == 'text/csss')) {
        $isCss = true;
      } else {
        $ext = substr($_GET['url'], strrpos($_GET['url'], '.') + 1);
        $isCss = strtolower($ext) == 'css';
      }
      if ($isCss) {
        $gadget = $this->createGadget($gadgetUrl);
        $rewrite = $gadget->gadgetSpec->rewrite;
        if (is_array($rewrite)) {
          $contentRewriter = new ContentRewriter($this->context, $gadget);
          $result->setResponseContent($contentRewriter->rewriteCSS($result->getResponseContent()));
        }
      }
    } catch (Exception $e) {
      // ignore, not being able to rewrite anything isn't fatal
    }

  }

  /**
   * Uses the GadgetFactory to instrance the specified gadget
   *
   * @param string $gadgetUrl
   */
  private function createGadget($gadgetUrl) {
    // Only include these files if appropiate, else it would slow down the entire proxy way to much
    require_once 'src/gadgets/GadgetSpecParser.php';
    require_once 'src/gadgets/GadgetBlacklist.php';
    require_once 'src/gadgets/sample/BasicGadgetBlacklist.php';
    require_once 'src/gadgets/GadgetContext.php';
    require_once 'src/gadgets/GadgetFactory.php';
    require_once 'src/gadgets/GadgetSpec.php';
    require_once 'src/gadgets/Gadget.php';
    require_once 'src/gadgets/GadgetException.php';
    require_once 'src/gadgets/rewrite/GadgetRewriter.php';
    require_once 'src/gadgets/rewrite/DomRewriter.php';
    require_once 'src/gadgets/rewrite/ContentRewriter.php';
    // make sure our context returns the gadget url and not the proxied document url
    $this->context->setUrl($gadgetUrl);
    // and create & return the gadget
    $gadgetSpecFactory = new GadgetFactory($this->context, null);
    $gadget = $gadgetSpecFactory->createGadget();
    return $gadget;
  }
}
