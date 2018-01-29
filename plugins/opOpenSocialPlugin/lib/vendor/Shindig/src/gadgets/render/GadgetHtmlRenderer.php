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

require 'GadgetBaseRenderer.php';

/**
 * Renders a Gadget's Content type="html" view, inlining the content, feature javascript and javascript initialization
 * into the gadget's content. Most of the logic is performed with in the shared GadgetBaseRender class
 *
 */
class GadgetHtmlRenderer extends GadgetBaseRenderer {

  public function renderGadget(Shindig_Gadget $gadget, $view) {
    $this->setGadget($gadget);
    // Was a privacy policy header configured? if so set it
    if (Shindig_Config::get('P3P') != '') {
      header("P3P: " . Shindig_Config::get('P3P'));
    }
    $content = '';
    // Set doctype if quirks = false or empty in the view
    if (! empty($view['quirks']) || ! $view['quirks']) {
      $content .= "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">\n";
    }
    // Rewriting the gadget's content using the libxml library does impose some restrictions to the validity of the input html, so
    // for the time being (until either gadgets are all fixed, or we find a more tolerant html parsing lib), we try to avoid it when we can
    $domRewrite = false;
    if (isset($gadget->gadgetSpec->rewrite) || Shindig_Config::get('rewrite_by_default')) {
      $domRewrite = true;
    } elseif (strpos($view['content'], 'text/os-data') !== false || strpos($view['content'], 'text/os-template') !== false) {
      $domRewrite = true;
    }
    if (!$domRewrite) {
      // Manually generate the html document using basic string concatinations instead of using our DOM based functions
      $content .= "<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n";
      $content .= '<style>'.Shindig_Config::get('gadget_css')."</style>\n";
      $scripts = $this->getJavaScripts();
      if ($scripts['external']) {
        $content .= "<script type=\"text/javascript\" src=\"{$scripts['external']}\"></script>\n";
      }
      if (!empty($scripts['inline'])) {
        $content .= "<script type=\"text/javascript\">{$scripts['inline']}</script>\n";
      }
      $content .= "</head>\n<body>\n";
      $content .= $gadget->substitutions->substitute($view['content']);
      $content .= '<script type="text/javascript">'.$this->getBodyScript()."</script>\n";
      $content .= "\n</body>\n</html>\n";
    } else {
      // Use the (libxml2 based) DOM rewriter
      $content .= "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/></head><body>\n";
      // Append the content for the selected view
      $content .= $gadget->substitutions->substitute($view['content']);
      $content .= "\n</body>\n</html>";
      $content = $this->parseTemplates($content);
      $content = $this->rewriteContent($content);
      $content = $this->addTemplates($content);
    }
    echo $content;
  }
}
