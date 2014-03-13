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

require_once 'src/common/HttpServlet.php';
require_once 'src/common/JsMin.php';
require_once 'src/common/SecurityTokenDecoder.php';
require_once 'src/common/SecurityToken.php';
require_once 'src/common/BlobCrypter.php';
require_once 'src/common/RemoteContentRequest.php';
require_once 'src/common/RemoteContent.php';
require_once 'src/common/Cache.php';
require_once 'src/common/RemoteContentFetcher.php';
require_once 'src/common/sample/BasicRemoteContent.php';
require_once 'src/common/sample/BasicRemoteContentFetcher.php';
require_once 'src/gadgets/GadgetSpecParser.php';
require_once 'src/gadgets/GadgetBlacklist.php';
require_once 'src/gadgets/sample/BasicGadgetBlacklist.php';
require_once 'src/gadgets/GadgetContext.php';
require_once 'src/gadgets/GadgetFactory.php';
require_once 'src/gadgets/GadgetSpec.php';
require_once 'src/gadgets/Gadget.php';
require_once 'src/gadgets/GadgetException.php';
require_once 'src/gadgets/render/GadgetRenderer.php';
require_once 'src/gadgets/rewrite/GadgetRewriter.php';
require_once 'src/gadgets/rewrite/DomRewriter.php';

class GadgetRenderingServlet extends HttpServlet {
  private $context;

  public function doGet() {
    try {
      if (empty($_GET['url'])) {
        throw new GadgetException("Missing required parameter: url");
      }
      $this->context = new GadgetContext('GADGET');
      $gadgetSigner = Shindig_Config::get('security_token_signer');
      $gadgetSigner = new $gadgetSigner();
      try {
        $token = $this->context->extractAndValidateToken($gadgetSigner);
      } catch (Exception $e) {
        // no token given, this is a fatal error if 'render_token_required' is set to true
        if (Shindig_Config::get('render_token_required')) {
          $this->showError($e);
        } else {
          $token = '';
        }
      }
      $gadgetSpecFactory = new GadgetFactory($this->context, $token);
      $gadget = $gadgetSpecFactory->createGadget();
      $this->setCachingHeaders();
      $this->renderGadget($gadget);
    } catch (Exception $e) {
      $this->showError($e);
    }
  }

  private function renderGadget(Shindig_Gadget $gadget) {
    $view = $gadget->getView($this->context->getView());
    if ($view['type'] == 'URL') {
      require_once "src/gadgets/render/GadgetUrlRenderer.php";
      $gadgetRenderer = new GadgetUrlRenderer($this->context);
    } elseif ($view['type'] == 'HTML' && empty($view['href'])) {
      require_once "src/gadgets/render/GadgetHtmlRenderer.php";
      $gadgetRenderer = new GadgetHtmlRenderer($this->context);
    } elseif (empty($view['type']) || ! empty($view['href'])) {
      require_once "src/gadgets/render/GadgetHrefRenderer.php";
      $gadgetRenderer = new GadgetHrefRenderer($this->context);
    } else {
      throw new GadgetException("Invalid view type");
    }
    $gadgetRenderer->renderGadget($gadget, $view);
  }

  private function setCachingHeaders() {
    $this->setContentType("text/html; charset=UTF-8");
    if ($this->context->getIgnoreCache()) {
      // no cache was requested, set non-caching-headers
      $this->setNoCache(true);
    } elseif (isset($_GET['v'])) {
      // version was given, cache for a long long time (a year)
      $this->setCacheTime(365 * 24 * 60 * 60);
    } else {
      // no version was given, cache for 5 minutes
      $this->setCacheTime(5 * 60);
    }
  }

  private function showError($e) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    echo "<html><body>";
    echo "<h1>Error</h1>";
    echo $e->getMessage();
    if (Shindig_Config::get('debug')) {
      echo "<p><b>Debug backtrace</b></p><div style='overflow:auto; height:300px; border:1px solid #000000'><pre>";
      print_r(debug_backtrace());
      echo "</pre></div>>";
    }
    echo "</body></html>";
    die();
  }
}
