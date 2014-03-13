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
 * Implements the Content-Rewrite feature which rewrites all image, css and script
 * links to their proxied versions, which can be quite a latency improvement, and
 * save the gadget dev's server from melting down
 *
 */
class ContentRewriter extends DomRewriter {
  private $rewrite;
  private $baseUrl;
  private $defaultRewrite = array('include-url' => array('*'), 'exclude-url' => array(), 'refresh' => '86400');

  public function __construct(GadgetContext $context, Shindig_Gadget &$gadget) {
    parent::__construct($context, $gadget);
    // if no rewrite params are set in the gadget but rewrite_by_default is on, use our default rules (rewrite all)
    if (! isset($gadget->gadgetSpec->rewrite) && Shindig_Config::get('rewrite_by_default')) {
      $this->rewrite = $this->defaultRewrite;
    } else {
      $this->rewrite = $gadget->gadgetSpec->rewrite;
    }
    // the base url of the gadget is used for relative paths
    $this->baseUrl = substr($this->context->getUrl(), 0, strrpos($this->context->getUrl(), '/') + 1);
  }

  /**
   * Register our dom node observers
   *
   * @param GadgetRewriter $gadgetRewriter
   */
  public function register(GadgetRewriter &$gadgetRewriter) {
    $gadgetRewriter->addObserver('img', $this, 'rewriteImage');
    $gadgetRewriter->addObserver('style', $this, 'rewriteStyle');
    $gadgetRewriter->addObserver('script', $this, 'rewriteScript');
    $gadgetRewriter->addObserver('link', $this, 'rewriteStyleLink');
  }

  /**
   * Produces the proxied version of a URL if it falls within the content-rewrite params and
   * will append a refresh param to the proxied url based on the expires param, and the gadget
   * url so that the proxy server knows to rewrite it's content or not
   *
   * @param string $url
   */
  private function getProxyUrl($url) {
    if (strpos(strtolower($url), 'http://') === false && strpos(strtolower($url), 'https://') === false) {
      $url = $this->baseUrl . $url;
    }
    $url = Shindig_Config::get('web_prefix') . '/gadgets/proxy?url=' . urlencode($url);
    $url .= '&refresh=' . (isset($this->rewrite['expires']) && is_numeric($this->rewrite['expires']) ? $this->rewrite['expires'] : '3600');
    $url .= '&gadget=' . urlencode($this->context->getUrl());
    return $url;
  }

  /**
   * Checks the URL against the include-url and exclude-url params
   *
   * @param string $url
   */
  private function includedUrl($url) {
    $included = $excluded = false;
    if (isset($this->rewrite['include-url'])) {
      foreach ($this->rewrite['include-url'] as $includeUrl) {
        if ($includeUrl == '*' || strpos($url, $includeUrl) !== false) {
          $included = true;
          break;
        }
      }
    }
    if (isset($this->rewrite['exclude-url'])) {
      foreach ($this->rewrite['exclude-url'] as $excludeUrl) {
        if ($excludeUrl == '*' || strpos($url, $excludeUrl) !== false) {
          $excluded = true;
          break;
        }
      }
    }
    return ($included && ! $excluded);
  }

  /**
   * Rewrites the src attribute of an img tag
   *
   * @param DOMElement $node
   */
  public function rewriteImage(DOMElement &$node) {
    if (($src = $node->getAttribute('src')) != null && $this->includedUrl($src)) {
      $node->setAttribute('src', $this->getProxyUrl($src));
    }
  }

  /**
   * Uses rewriteCSS to find url(<url tag>) constructs and rewrite them to their
   * proxied counterparts
   *
   * @param DOMElement $node
   */
  public function rewriteStyle(DOMElement &$node) {
    $node->nodeValue = $this->rewriteCSS($node->nodeValue);
  }

  /**
   * Does the actual CSS rewriting, this is a seperate function so it can be called
   * from the proxy handler too
   *
   * @param string $content
   * @return string
   */
  public function rewriteCSS($content) {
    $newVal = '';
    // loop through the url elements in the content
    while (($pos = strpos($content, 'url')) !== false) {
      // output everything before this url tag
      $newVal .= substr($content, 0, $pos + 3);
      $content = substr($content, $pos + 3);
      // low tech protection against miss-reading tags, if the open ( is to far away, this is probabbly a miss-read
      if (($beginTag = strpos($content, '(')) < 4) {
        $content = substr($content, $beginTag + 1);
        $endTag = strpos($content, ')');
        $tag = str_replace(array("'", "\""), '', trim(substr($content, 0, $endTag)));
        // at this point $tag should be the actual url aka: http://example.org/bar/foo.gif
        if ($this->includedUrl($tag)) {
          $newVal .= "('" . $this->getProxyUrl($tag) . "')";
        } else {
          $newVal .= "('$tag')";
        }
        $content = substr($content, $endTag + 1);
      }
    }
    // append what's left
    $newVal .= $content;
    return $newVal;
  }

  /**
   * Rewrites <script src="http://example.org/foo.js" /> tags into their proxied versions
   *
   * @param DOMElement $node
   */
  public function rewriteScript(DOMElement &$node) {
    if (($src = $node->getAttribute('src')) != null && $this->includedUrl($src)) {
      // make sure not to rewrite our forcedJsLibs src tag, else things break
      if (strpos($src, '/gadgets/js') === false) {
        $node->setAttribute('src', $this->getProxyUrl($src));
      }
    }
  }

  /**
   * Rewrites <link href="http://example.org/foo.css" /> tags into their proxied versions
   *
   * @param DOMElement $node
   */
  public function rewriteStyleLink(DOMElement &$node) {
    if (($src = $node->getAttribute('href')) != null && $this->includedUrl($src)) {
      $node->setAttribute('href', $this->getProxyUrl($src));
    }
  }
}
