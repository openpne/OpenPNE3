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
 * The rewriter meta class, it checks the various gadget and configuration
 * settings, and calls the appropiate classes for the registered dom element
 * listeners
 */
class GadgetRewriter {
  private $context;
  private $doc;
  private $domObservers = array();

  public function __construct(GadgetContext $context) {
    $this->context = $context;
  }

  /**
   * Does the actual rewrite option scanning and performs the dom parsing
   *
   * @param string $content
   * @param Shindig_Gadget $gadget
   */
  public function rewrite($content, Shindig_Gadget &$gadget) {
    // Check to see if the gadget requested rewriting, or if rewriting is forced in the configuration
    if (is_array($gadget->gadgetSpec->rewrite) || Shindig_Config::get('rewrite_by_default')) {
      require_once "src/gadgets/rewrite/ContentRewriter.php";
      $contentRewriter = new ContentRewriter($this->context, $gadget);
      $contentRewriter->register($this);
    }
    // Are we configured to sanitize certain views? (if so the config should be an array of view names to sanitize, iaw: array('profile', 'home'))
    if (is_array(Shindig_Config::get('sanitize_views'))) {
      require_once "src/gadgets/rewrite/SanitizeRewriter.php";
      $sanitizeRewriter = new SanitizeRewriter($this->context, $gadget);
      $sanitizeRewriter->register($this);
    }
    // no observers registered, return the original content, otherwise parse the DOM tree and call the observers
    if (! count($this->domObservers)) {
      return $content;
    } else {
      libxml_use_internal_errors(true);
      $this->doc = new DOMDocument(null, 'utf-8');
      $this->doc->preserveWhiteSpace = true;
      $this->doc->formatOutput = false;
      $this->doc->strictErrorChecking = false;
      $this->doc->recover = false;
      $this->doc->resolveExternals = false;
      if (! $this->doc->loadHtml($content)) {
        //TODO parse and output libxml_get_errors();
        libxml_clear_errors();
        // parsing failed, return the unmodified content
        return $content;
      }
      // find and parse all nodes in the dom document
      $rootNodes = $this->doc->getElementsByTagName('*');
      $this->parseNodes($rootNodes);
      // DomDocument tries to make the document a valid html document, so added the html/body/head elements to it.. so lets strip them off before returning the content
      $html = $this->doc->saveHTML();
      // If the gadget specified the caja feature, cajole it
      if (in_array('caja', $gadget->features)) {
        //TODO : use the caja daemon to cajole the content (experimental patch is available and will be added soon)
      }
      return $html;
    }
  }

  /**
   * This function should be called from the DomRewriter implmentation class in the form of:
   * addObserver('img', $this, 'rewriteImage')
   *
   * @param string $tag
   * @param object instance $class
   * @param string $function
   */
  public function addObserver($tag, $class, $function) {
    // add the tag => function to call relationship to our $observers array
    $this->domObservers[] = array('tag' => $tag, 'class' => $class, 'function' => $function);
  }

  /**
   * Parses the DOMNodeList $nodes and calls the registered rewriting function on nodes
   *
   * @param DOMNodeList $nodes
   */
  private function parseNodes(DOMNodeList &$nodes) {
    foreach ($nodes as $node) {
      $tagName = strtolower($node->tagName);
      foreach ($this->domObservers as $observer) {
        if ($observer['tag'] == $tagName) {
          $class = $observer['class'];
          $function = $observer['function'];
          $class->$function($node, $this->doc);
        }
      }
    }
  }
}
