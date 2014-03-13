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

//TODO verify os:HttpRequest

class DataPipelining {

  /**
   * Parses the data-pipelining tags of from a html/href view, or from a os-data script tag and returns a
   * normalized array of requests to perform (which can be used to call DataPipelining::fetch)
   *
   * @param DOMNodeList $dataTags
   */
  static public function parse(DOMElement &$viewNode) {
    $dataTags = $viewNode->getElementsByTagName('*');
    if ($dataTags->length > 0) {
      $dataPipeliningTags = array();
      $namespaceErrorTags = array('httprequest', 'datarequest', 'peoplerequest', 'viewerrequest', 'ownerrequest', 'activitiesrequest');
      foreach ($dataTags as $dataTag) {
        $tag = array();
        $tag['type'] = $dataTag->tagName;
        $supportedDataAttributes = array('key', 'method', 'userId', 'groupId', 'fields', 'startIndex', 'count', 'sortBy', 'sortOrder', 'filterBy', 'filterOp', 'filterValue', 'activityIds', 'href', 'params');
        foreach ($supportedDataAttributes as $dataAttribute) {
          $val = $dataTag->getAttribute($dataAttribute);
          if (! empty($val)) {
            $tag[$dataAttribute] = $val;
          }
        }

        // Make sure the proper name space decleration was used, either parsing would fail miserably
        if (in_array(strtolower($tag['type']), $namespaceErrorTags)) {
        	throw new ExpressionException("Invalid os-data namespace, please use xmlns:os=\"http://ns.opensocial.org/2008/markup\" in the script tag");
        }

        // normalize the methods so that os:PeopleRequest becomes a os:DataRequest with a people.get method, and os:ViewerRequest becomes a people.get with a userId = @viewer & groupId = @self, this
        // makes it a whole lot simpler to implement the actual data fetching in the renderer
        switch ($tag['type']) {
          case 'os:PeopleRequest':
            $tag['type'] = 'os:DataRequest';
            $tag['method'] = 'people.get';
            break;
          case 'os:ViewerRequest':
          case 'os:OwnerRequest':
            $tag['type'] = 'os:DataRequest';
            $tag['method'] = 'people.get';
            $tag['userId'] = $tag['type'] == 'osViewerRequest' ? '@viewer' : '@owner';
            $tag['groupId'] = '@self';
            break;
          case 'os:ActivitiesRequest':
            $tag['type'] = 'os:DataRequest';
            $tag['method'] = 'activities.get';
            break;
        }
        $dataPipeliningTags[] = $tag;
      }
      return $dataPipeliningTags;
    }
    return null;
  }

  /**
   * Fetches the requested data-pipeling info
   *
   * @param array $dataPipelining contains the parsed data-pipelining tags
   * @param GadgetContext $context context to use for fetching
   * @param array $dataContext the data context to use while resolving expressions in requests (it'll use the combined results + context to resolve)
   * @return array result
   */
  static public function fetch($dataPipeliningRequests, GadgetContext $context, $dataContext = array()) {
    $result = array();
    if (is_array($dataPipeliningRequests) && count($dataPipeliningRequests)) {
      do {
        // See which requests we can batch together, that either don't use dynamic tags or who's tags are resolvable
        $requestQueue = array();
        foreach ($dataPipeliningRequests as $key => $request) {
          if (($resolved = self::resolveRequest($request, $result)) !== false) {
            $requestQueue[] = $resolved;
            unset($dataPipeliningRequests[$key]);
          }
        }
        if (count($requestQueue)) {
          $returnedResults = self::performRequests($requestQueue, $context);
          if (is_array($returnedResults)) {
            $dataContext = self::addResultToContext($returnedResults, $dataContext);
            $result = array_merge($returnedResults, $result);
          }
        }
      } while (count($requestQueue));
    }
    return $result;
  }

  /**
   * Adds the fetched results to the data context, used by the fetch() function to
   * add the performed requests to the data context that's used to resolve expressions
   *
   * @param array $returnedResults
   * @param array $dataContext
   */
  static private function addResultToContext($returnedResults, $dataContext) {
    foreach ($returnedResults as $val) {
      // we really only accept entries with a request id, otherwise it can't be referenced by context anyhow
      if (isset($val['id'])) {
        $key = $val['id'];
        // Pick up only the actual data part of the response, so we can do direct variable resolution
        if (isset($val['data']['list'])) {
          $dataContext[$key] = $val['data']['list'];
        } elseif (isset($val['data']['entry'])) {
          $dataContext[$key] = $val['data']['entry'];
        } elseif (isset($val['data'])) {
          $dataContext[$key] = $val['data'];
        }
      }
    }
    return $dataContext;
  }

  /**
   * Peforms the actual http fetching of the data-pipelining requests, all social requests
   * are made to $_SERVER['SERVER_NAME'] (the virtual host name of this server) / (optional) web_prefix / social / rpc, and
   * the httpRequest's are made to $_SERVER['SERVER_NAME'] (the virtual host name of this server) / (optional) web_prefix / gadgets / makeRequest
   * both request types use the current security token ($_GET['st']) when performing the requests so they happen in the correct context
   *
   * @param array $requests
   * @return array response
   */
  static private function performRequests($requests, $context) {
    $jsonRequests = array();
    $httpRequests = array();
    $decodedResponse = array();
    // Using the same gadget security token for all social & http requests so everything happens in the right context
    if (!isset($_GET['st'])) {
    	throw new ExpressionException("No security token set, required for data-pipeling");
    }
    $securityToken = $_GET['st'];
    foreach ($requests as $request) {
      switch ($request['type']) {
        case 'os:DataRequest':
          // Add to the social request batch
          $id = $request['key'];
          $method = $request['method'];
          // remove our internal fields so we can use the remainder as params
          unset($request['key']);
          unset($request['method']);
          unset($request['type']);
          $jsonRequests[] = array('method' => $method, 'id' => $id, 'params' => $request);
          break;
        case 'os:HttpRequest':
          $id = $request['key'];
          $url = $request['href'];
          unset($request['key']);
          unset($request['type']);
          unset($request['href']);
          $httpRequests[] = array('id' => $id, 'url' => $url, 'queryStr' => implode('&', $request));
          break;
      }
    }
    if (count($jsonRequests)) {
      // perform social api requests
      $request = new RemoteContentRequest('http://'.$_SERVER['SERVER_NAME'] . Shindig_Config::get('web_prefix') . '/social/rpc?st=' . urlencode($securityToken) . '&format=json', "Content-Type: application/json\n", json_encode($jsonRequests));
      $request->setMethod('POST');
      $basicFetcher = new BasicRemoteContentFetcher();
      $basicRemoteContent = new BasicRemoteContent($basicFetcher);
      $response = $basicRemoteContent->fetch($request);
      $decodedResponse = json_decode($response->getResponseContent(), true);
    }
    if (count($httpRequests)) {
      $requestQueue = array();
      foreach ($httpRequests as $request) {
        $req = new RemoteContentRequest($_SERVER['SERVER_NAME'] . Shindig_Config::get('web_prefix') . '/gadgets/makeRequest?url=' . urlencode($request['url']) . '&st=' . urlencode($securityToken) . (! empty($request['queryStr']) ? '&' . $request['queryStr'] : ''));
        $req->getOptions()->ignoreCache = $context->getIgnoreCache();
        $req->setNotSignedUri($request['url']);
        $requestQueue[] = $req;
      }
      $basicRemoteContent = new BasicRemoteContent();
      $resps = $basicRemoteContent->multiFetch($requestQueue);
      foreach ($resps as $response) {
        // strip out the UNPARSEABLE_CRUFT (see makeRequestHandler.php) on assigning the body
        $resp = json_decode(str_replace("throw 1; < don't be evil' >", '', $response->getResponseContent()), true);
        if (is_array($resp)) {
          //FIXME: make sure that this is the format that java-shindig produces as well, the spec doesn't really state
          $decodedResponse = array_merge($resp, $decodedResponse);
        }
      }
    }
    return $decodedResponse;
  }

  /**
   * If a request (data-pipelining tag) doesn't include any dynamic tags, it's returned as is. If
   * however it does contain said tag, this function will attempt to resolve it using the $result
   * array, returning the parsed request on success, or FALSE on failure to resolve.
   *
   * @param array $request
   */
  static private function resolveRequest($request, $result) {
    $dataContext = self::makeContextData($result);
    foreach ($request as $key => $val) {
      $expressions = array();
      preg_match_all('/\$\{(.*)\}/imxsU', $val, $expressions);
      $expressionCount = count($expressions[0]);
      if ($expressionCount) {
        for ($i = 0; $i < $expressionCount; $i ++) {
          $toReplace = $expressions[0][$i];
          $expression = $expressions[1][$i];
          try {
            $expressionResult = ExpressionParser::evaluate($expression, $dataContext);
            $request[$key] = str_replace($toReplace, $expressionResult, $request[$key]);
          } catch (ExpressionException $e) {
            // ignore, maybe on the next pass we can resolve this
            return false;
          }
        }
      }
    }
    return $request;
  }

  /**
   * Makes a data context array out of the current data pipelining results that can be used
   * by the expression parser to resolve the request attributes
   *
   * @param array $array current data pipelining results
   * @return array $dataContext a dataContext array
   */
  static private function makeContextData($array) {
    $result = array();
    foreach ($array as $val) {
      if (isset($val['id'])) {
        $key = $val['id'];
        if (isset($val['data']['list'])) {
          $result[$key] = $val['data']['list'];
        } elseif (isset($val['data']['entry'])) {
          $result[$key] = $val['data']['entry'];
        } elseif (isset($val['data'])) {
          $result[$key] = $val['data'];
        }
      }
    }
    $dataContext = array();
    $dataContext['Top'] = $result;
    $dataContext['Cur'] = array();
    $dataContext['My'] = array();
    $dataContext['Context'] = array('UniqueId' => uniqid());
    return $dataContext;
  }
}
