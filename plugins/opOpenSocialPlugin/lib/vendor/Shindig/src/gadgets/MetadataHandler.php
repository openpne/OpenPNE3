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

class MetadataHandler {

  public function process($requests) {
    $response = array();
    foreach ($requests->gadgets as $gadget) {
      try {
        $gadgetUrl = $gadget->url;
        $gadgetModuleId = $gadget->moduleId;
        $context = new MetadataGadgetContext($requests->context, $gadgetUrl);
        $token = $this->getSecurityToken();
        $gadgetServer = new GadgetFactory($context, $token);
        $gadget = $gadgetServer->createGadget($gadgetUrl);
        $response[] = $this->makeResponse($gadget, $gadgetModuleId, $gadgetUrl, $context);
      } catch (Exception $e) {
        $response[] = array('errors' => array($e->getMessage()),
            'moduleId' => $gadgetModuleId, 'url' => $gadgetUrl);
      }
    }
    return $response;
  }

  private function getSecurityToken() {
    $token = isset($_POST['st']) ? $_POST['st'] : (isset($_GET['st']) ? $_GET['st'] : '');
    if (empty($token)) {
      if (Shindig_Config::get('allow_anonymous_token')) {
        // no security token, continue anonymously, remeber to check
        // for private profiles etc in your code so their not publicly
        // accessable to anoymous users! Anonymous == owner = viewer = appId = modId = 0
        // create token with 0 values, no gadget url, no domain and 0 duration
        $gadgetSigner = Shindig_Config::get('security_token');
        return new $gadgetSigner(null, 0, SecurityToken::$ANONYMOUS, SecurityToken::$ANONYMOUS, 0, '', '', 0, Shindig_Config::get('container_id'));
      } else {
        return null;
      }
    }
    if (count(explode(':', $token)) != 7) {
      $token = urldecode(base64_decode($token));
    }
    $gadgetSigner = Shindig_Config::get('security_token_signer');
    $gadgetSigner = new $gadgetSigner();
    return $gadgetSigner->createToken($token);
  }

  private function getIframeURL(Shindig_Gadget $gadget, GadgetContext $context) {
    $v = $gadget->getChecksum();
    $view = $gadget->getView($context->getView());
    $up = '';
    foreach ($gadget->gadgetSpec->userPrefs as $pref) {
      $up .= '&up_' . urlencode($pref['name']) . '=' . urlencode($pref['value']);
    }
    $locale = $context->getLocale();
    //Note: putting the URL last, else some browsers seem to get confused (reported by hi5)
    return Shindig_Config::get('default_iframe_prefix') . 'container=' . $context->getContainer() . ($context->getIgnoreCache() ? '&nocache=1' : '&v=' . $v) . ($context->getModuleId() != 0 ? '&mid=' . $context->getModuleId() : '') . '&lang=' . $locale['lang'] . '&country=' . $locale['country'] . '&view=' . $view['view'] . $up . '&url=' . urlencode($context->getUrl());
  }

  private function makeResponse($gadget, $gadgetModuleId, $gadgetUrl, $context) {
    $response = array();
    $prefs = array();
    foreach ($gadget->gadgetSpec->userPrefs as $pref) {
      $prefs[$pref['name']] = $pref;
    }
    $views = array();
    foreach ($gadget->gadgetSpec->views as $name => $view) {
      // we want to include all information, except for the content
      unset($view['content']);
      $views[$name] = $view;
    }

    $oauth = array();
    /*
    $oauthspec = $gadget->getOAuthSpec();
    if (! empty($oauthspec)) {
      foreach ($oauthspec->getServices() as $oauthservice) {
        $oauth[$oauthservice->getName()] = array("request" => $oauthservice->getRequestUrl(), "access" => $oauthservice->getAccessUrl(), "authorization" => $oauthservice->getAuthorizationUrl());
      }
    }
    */

    $response['iframeUrl'] = $this->getIframeURL($gadget, $context);
    $response['features'] = $gadget->features;
    $response['links'] = $gadget->gadgetSpec->links;
    $response['icons'] = $gadget->gadgetSpec->icon;
    $response['views'] = $views;
    $response['author'] = $gadget->getAuthor();
    $response['authorEmail'] = $gadget->getAuthorEmail();
    $response['description'] = $gadget->getDescription();
    $response['directoryTitle'] = $gadget->getDirectoryTitle();
    $response['screenshot'] = $gadget->getScreenShot();
    $response['thumbnail'] = $gadget->getThumbnail();
    $response['title'] = $gadget->getTitle();
    $response['titleUrl'] = $gadget->getTitleUrl();
    $response['authorAffiliation'] = $gadget->getAuthorAffiliation();
    $response['authorLocation'] = $gadget->getAuthorLocation();
    $response['authorPhoto'] = $gadget->getAuthorPhoto();
    $response['authorAboutme'] = $gadget->getAuthorAboutme();
    $response['authorQuote'] = $gadget->getAuthorQuote();
    $response['authorLink'] = $gadget->getAuthorLink();
    $response['showInDirectory'] = $gadget->getShowInDirectory();
    $response['showStats'] = $gadget->getShowStats();
    $response['width'] = $gadget->getWidth();
    $response['height'] = $gadget->getHeight();
    $response['categories'] = Array($gadget->getCategory(), $gadget->getCategory2());
    $response['singleton'] = $gadget->getSingleton();
    $response['scaling'] = $gadget->getScaling();
    $response['scrolling'] = $gadget->getScrolling();
    $response['moduleId'] = $gadgetModuleId;
    $response['url'] = $gadgetUrl;
    $response['userPrefs'] = $prefs;
    $response['oauth'] = $oauth;
    return $response;
  }
}
