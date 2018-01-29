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
 * Basic remote content fetcher, uses curl_multi to fetch multiple resources at the same time
 */
class BasicRemoteContentFetcher extends RemoteContentFetcher {
  private $requests = array();
  private $disallowedHeaders = array('Keep-Alive', 'Host', 'Accept-Encoding', 'Set-Cookie', 'Content-Length', 'Content-Encoding', 'ETag', 'Last-Modified', 'Accept-Ranges', 'Vary', 'Expires', 'Date', 'Pragma', 'Cache-Control', 'Transfer-Encoding', 'If-Modified-Since');
  const USER_AGENT = 'Apache Shindig';

  /**
   * Performs a single (RemoteContentRequest) request and fills in the response
   * in the $request object
   *
   * @param RemoteContentRequest $request
   * @return RemoteContentRequest $request
   */
  public function fetchRequest(RemoteContentRequest $request) {
    $request->handle = $this->initCurlHandle($request->getUrl());
    $this->setHeaders($request);
    // Execute the request
    $content = @curl_exec($request->handle);
    $this->parseResult($request, $content);
    curl_close($request->handle);
    unset($request->handle);
    return $request;
  }

  /**
   * Performs multiple (array of RemoteContentRequest) requests and fills in the responses
   * in the $request objects
   *
   * @param Array of RemoteContentRequest's $requests
   * @return $requests
   */
  public function multiFetchRequest(Array $requests) {
    $mh = curl_multi_init();
    foreach ($requests as $request) {
      $request->handle = $this->initCurlHandle($request->getUrl());
      // Set this so the multihandler will return data
      curl_setopt($request->handle, CURLOPT_RETURNTRANSFER, 1);
      $this->setHeaders($request);
      curl_multi_add_handle($mh, $request->handle);
    }
    $running = null;
    do {
      curl_multi_exec($mh, $running);
    } while ($running > 0);
    foreach ($requests as $request) {
      // Execute the request
      $content = curl_multi_getcontent($request->handle);
      $this->parseResult($request, $content);
      curl_multi_remove_handle($mh, $request->handle);
      unset($request->handle);
    }
    curl_multi_close($mh);
    unset($mh);
    return $requests;
  }

  /**
   * Parses the result content into the headers and body, and retrieves the http code and content type
   *
   * @param RemoteContentRequest $request
   * @param string $content
   */
  private function parseResult(RemoteContentRequest $request, $content) {
    $headers = '';
    $body = '';
    // on redirects and such we get multiple headers back from curl it seems, we really only want the last one
    while (substr($content, 0, strlen('HTTP')) == 'HTTP' && strpos($content, "\r\n\r\n") !== false) {
      $headers = substr($content, 0, strpos($content, "\r\n\r\n"));
      $content = $body = substr($content, strlen($headers) + 4);
    }
    $headers = explode("\n", $headers);
    $parsedHeaders = array();
    foreach ($headers as $header) {
      if (strpos($header, ':')) {
        $key = trim(substr($header, 0, strpos($header, ':')));
        $key = str_replace(' ', '-', ucwords(str_replace('-', ' ', $key)));
        $val = trim(substr($header, strpos($header, ':') + 1));
        $parsedHeaders[$key] = $val;
      }
    }
    $httpCode = curl_getinfo($request->handle, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($request->handle, CURLINFO_CONTENT_TYPE);
    if (! $httpCode) {
      $httpCode = '404';
    }
    $request->setHttpCode($httpCode);
    $request->setHttpCodeMsg($this->resolveHttpCode($httpCode));
    $request->setContentType($contentType);
    $request->setResponseHeaders($parsedHeaders);
    $request->setResponseContent($body);
    $request->setResponseSize(strlen($content));
  }

  /**
   * Misc function to resolve http status codes to a matching http code message
   * since curl strips those, but we do need'm in the proxy handler
   * @param $httpCode
   * @return string
   */
  private function resolveHttpCode($httpCode) {
    switch ((int)$httpCode) {
      case 100: return "Continue";
      case 101: return "Switching Protocols";
      case 200: return "OK";
      case 201: return "Created";
      case 202: return "Accepted";
      case 203: return "Non-Authoritative Information";
      case 204: return "No Content";
      case 205: return "Reset Content";
      case 206: return "Partial Content";
      case 300: return "Multiple Choices";
      case 301: return "Moved Permanently";
      case 302: return "Found";
      case 303: return "See Other";
      case 304: return "Not Modified";
      case 305: return "Use Proxy";
      case 306: return "(Unused)";
      case 307: return "Temporary Redirect";
      case 400: return "Bad Request";
      case 401: return "Unauthorized";
      case 402: return "Payment Required";
      case 403: return "Forbidden";
      case 404: return "Not Found";
      case 405: return "Method Not Allowed";
      case 406: return "Not Acceptable";
      case 407: return "Proxy Authentication Required";
      case 408: return "Request Timeout";
      case 409: return "Conflict";
      case 410: return "Gone";
      case 411: return "Length Required";
      case 412: return "Precondition Failed";
      case 413: return "Request Entity Too Large";
      case 414: return "Request-URI Too Long";
      case 415: return "Unsupported Media Type";
      case 416: return "Requested Range Not Satisfiable";
      case 417: return "Expectation Failed";
      case 500: return "Internal Server Error";
      case 501: return "Not Implemented";
      case 502: return "Bad Gateway";
      case 503: return "Service Unavailable";
      case 504: return "Gateway Timeout";
      case 505: return "HTTP Version Not Supported";
      default : return "Unknown Error";
    }
  }

  /**
   * Sets the headers and post body for the request if they are specified
   *
   * @param RemoteContentRequest $request
   */
  private function setHeaders(RemoteContentRequest $request) {
    if ($request->hasHeaders()) {
      $headers = explode("\n", $request->getHeaders());
      $outHeaders = array();
      foreach ($headers as $header) {
        if (strpos($header, ':')) {
          $key = trim(substr($header, 0, strpos($header, ':')));
          $key = str_replace(' ', '-', ucwords(str_replace('-', ' ', $key)));
          $val = trim(substr($header, strpos($header, ':') + 1));
          if (! in_array($key, $this->disallowedHeaders)) {
            $outHeaders[] = "$key: $val";
          }
        }
      }
      $outHeaders[] = "User-Agent: " . BasicRemoteContentFetcher::USER_AGENT;
      curl_setopt($request->handle, CURLOPT_HTTPHEADER, $outHeaders);
    }
    if ($request->isPost()) {
      curl_setopt($request->handle, CURLOPT_POST, 1);
      curl_setopt($request->handle, CURLOPT_POSTFIELDS, $request->getPostBody());
    }
  }

  /**
   * Initializes a curl handle for making a request
   * This will set the timeout based on the 'curl_connection_timeout configuration', and
   * set a proxy server to use if the 'proxy' config string is not empty
   *
   * @param string $url
   * @return curl handle
   */
  private function initCurlHandle($url) {
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($handle, CURLOPT_AUTOREFERER, 1);
    curl_setopt($handle, CURLOPT_MAXREDIRS, 10);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, Shindig_Config::get('curl_connection_timeout'));
    curl_setopt($handle, CURLOPT_TIMEOUT, Shindig_Config::get('curl_connection_timeout'));
    curl_setopt($handle, CURLOPT_HEADER, 1);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
    $proxy = Shindig_Config::get('proxy');
    if (! empty($proxy)) {
      curl_setopt($handle, CURLOPT_PROXY, $proxy);
    }
    return $handle;
  }
}
