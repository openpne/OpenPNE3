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
 * TODO Dynamically evaluate the limited EL subset expressions on the following tags:
 * Any attribute on os:DataRequest other than @key and @method
 * @userId
 * @groupId
 * @fields
 * @startIndex
 * @count
 * @sortBy
 * @sortOrder
 * @filterBy
 * @filterOp
 * @filterValue
 * @activityIds
 * @href
 * @params
 * Example:
 * <os:PeopleRequest key="PagedFriends" userId="@owner" groupId="@friends" startIndex="${ViewParams.first}" count="20"/>
 * <os:HttpRequest href="http://developersite.com/api?ids=${PagedFriends.ids}"/>
 */

require_once 'GadgetBaseRenderer.php';

class GadgetHrefRenderer extends GadgetBaseRenderer {

  /**
   * Renders a 'proxied content' view, for reference see:
   * http://opensocial-resources.googlecode.com/svn/spec/draft/OpenSocial-Data-Pipelining.xml
   *
   * @param Shindig_Gadget $gadget
   * @param array $view
   */
  public function renderGadget(Shindig_Gadget $gadget, $view) {
    $this->setGadget($gadget);
    if (Shindig_Config::get('P3P') != '') {
      header("P3P: " . Shindig_Config::get('P3P'));
    }
    /* TODO
     * We should really re-add OAuth fetching support some day, uses these view atributes:
     * $view['oauthServiceName'], $view['oauthTokenName'], $view['oauthRequestToken'], $view['oauthRequestTokenSecret'];
    */
    $authz = $this->getAuthz($view);
    $refreshInterval = $this->getRefreshInterval($view);
    $href = $this->buildHref($view, $authz);
    if (count($view['dataPipelining'])) {
      $request = new RemoteContentRequest($href, "Content-type: application/json\n");
      $request->setMethod('POST');
      $request->getOptions()->ignoreCache = $gadget->gadgetContext->getIgnoreCache();
    } else {
      // no data-pipelining set, use GET and set cache/refresh interval options
      $request = new RemoteContentRequest($href);
      $request->setMethod('GET');
      $request->setRefreshInterval($refreshInterval);
      $request->getOptions()->ignoreCache = $gadget->gadgetContext->getIgnoreCache();
    }
    $signingFetcherFactory = $gadgetSigner = false;
    if ($authz != 'none') {
      $gadgetSigner = Shindig_Config::get('security_token_signer');
      $gadgetSigner = new $gadgetSigner();
      $token = $gadget->gadgetContext->extractAndValidateToken($gadgetSigner);
      $request->setToken($token);
      $request->setAuthType($authz);
      $signingFetcherFactory = new SigningFetcherFactory(Shindig_Config::get("private_key_file"));
    }
    $basicFetcher = new BasicRemoteContentFetcher();
    $basicRemoteContent = new BasicRemoteContent($basicFetcher, $signingFetcherFactory, $gadgetSigner);
    // Cache POST's as if they were GET's, since we don't want to re-fetch and repost the social data for each view
    $basicRemoteContent->setCachePostRequest(true);
    if (($response = $basicRemoteContent->getCachedRequest($request)) == false) {
      // Don't fetch the data-pipelining social data unless we don't have a cached version of the gadget's content
      $dataPipeliningResults = DataPipelining::fetch($view['dataPipelining'], $this->context);
      // spec stats that the proxied content data-pipelinging data is *not* available to templates (to avoid duplicate posting
      // of the data to the gadget dev's server and once to js space), so we don't assign it to the data context, and just
      // post the json encoded results to the remote url.
      $request->setPostBody(json_encode($dataPipeliningResults));
      $response = $basicRemoteContent->fetch($request);
    }
    if ($response->getHttpCode() != '200') {
      // an error occured fetching the proxied content's gadget content
      $content = '<html><body><h1>An error occured fetching the gadget content</h1><p>http error code: '.$response->getHttpCode().'</p><p>'.$response->getResponseContent().'</body></html>';
    } else {
      // fetched ok, build the response document and output it
      $content = $response->getResponseContent();
      $content = $this->parseTemplates($content);
      $content = $this->rewriteContent($content);
      $content = $this->addTemplates($content);
    }
    echo $content;
  }

  /**
   * Builds the outgoing URL by taking the href attribute of the view and appending
   * the country, lang, and opensocial query params to it
   *
   * @param array $view
   * @param SecurityToken $token
   * @return string the url
   */
  private function buildHref($view, $authz) {
    $href = $view['href'];
    if (empty($href)) {
      throw new Exception("Invalid empty href in the gadget view");
    } // add the required country and lang param to the URL
    $lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
    $country = isset($_GET['country']) ? $_GET['country'] : 'US';
    $firstSeperator = strpos($href, '?') === false ? '?' : '&';
    $href .= $firstSeperator . 'lang=' . urlencode($lang);
    $href .= '&country=' . urlencode($country);
    if ($authz != 'none') {
      $href .= '&opensocial_proxied_content=1';
    }
    return $href;
  }

  /**
   * Returns the requested refreshInterval (cache time) of the view, or if none is specified
   * it will return the configured default_refresh_interval value
   *
   * @param array $view
   * @return int refresh interval
   */
  private function getRefreshInterval($view) {
    return ! empty($view['refreshInterval']) && is_numeric($view['refreshInterval']) ? $view['refreshInterval'] : Shindig_Config::get('default_refresh_interval');
  }

  /**
   * Returns the authz attribute of the view, can be 'none', 'signed' or 'oauth'
   *
   * @param array $view
   * @return string authz attribute
   */
  private function getAuthz($view) {
    return ! empty($view['authz']) ? strtolower($view['authz']) : 'none';
  }
}
