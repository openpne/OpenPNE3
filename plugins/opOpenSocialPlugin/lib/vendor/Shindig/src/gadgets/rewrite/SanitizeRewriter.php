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
 * Content sanitizer, removes any javascript from the view if this view is part of the
 * sanitize_views array
 */
class SanitizeRewriter extends DomRewriter {

  /**
   * Register our dom node observers that will remove the javascript, but only
   * if this view should be sanitized
   *
   * @param GadgetRewriter $gadgetRewriter
   */
  public function register(GadgetRewriter &$gadgetRewriter) {
    $sanitizeViews = Shindig_Config::get('sanitize_views');
    // Only hook up our dom node observers if this view should be sanitized
    if (in_array($this->context->getView(), $sanitizeViews)) {
      $gadgetRewriter->addObserver('script', $this, 'rewriteScript');
    }
  }

  /**
   * This is a proof of concept / semi dummy content sanitizer
   * that removes any javascript from the content block
   *
   * @param DOMElement $node
   */
  public function rewriteScript(DOMElement &$node) {
    if (!empty($node->nodeValue)) {
      $node->nodeValue = '';
    }
    if ($node->getAttribute('src') != null) {
      $node->setAttribute('src', '');
    }
  }
}