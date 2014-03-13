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

require_once 'src/gadgets/templates/DataPipelining.php';
require_once 'src/gadgets/templates/TemplateParser.php';

//TODO check if the opensocial-templates feature has disableAutoProcessing = true as param, if so don't


class EmptyClass {
}

/**
 * Base class which both the href and html renderer inherit from. This containers all the general
 * functions to deal with rewriting, templates, script insertion, etc
 */
abstract class GadgetBaseRenderer extends GadgetRenderer {
  public $gadget;
  public $dataContext = array();
  public $unparsedTemplates = array();
  public $dataInserts = array();

  /**
   * Sets the $this->gadget property, and populates Msg, UserPref and ViewParams dataContext
   *
   * @param Shindig_Gadget $gadget
   */
  public function setGadget(Shindig_Gadget $gadget) {
    $this->gadget = $gadget;
    $this->dataContext['UserPrefs'] = $this->dataContext['ViewParams'] = $this->dataContext['Msg'] = array();
    if (isset($this->gadget->gadgetSpec->locales)) {
      foreach ($this->gadget->gadgetSpec->locales as $key => $val) {
        $this->dataContext['Msg'][$key] = $val;
      }
    }
    if (isset($this->gadget->gadgetSpec->userPrefs)) {
      foreach ($this->gadget->gadgetSpec->userPrefs as $pref) {
        $this->dataContext['UserPrefs'][$pref['name']] = isset($pref['value']) ? $pref['value'] : '';
      }
    }
    if (isset($_GET['view-params'])) {
      $viewParams = json_decode($_GET['view-params'], true);
      if ($viewParams != $_GET['view-params'] && $viewParams) {
        foreach ($viewParams as $key => $val) {
          $this->dataContext['ViewParams'][$key] = $val;
        }
      }
    }
  }

  /**
   * If some templates could not be parsed, we paste the back into the html document
   * so javascript can take care of them
   */
  public function addTemplates($content) {
    if (count($this->unparsedTemplates)) {
      foreach ($this->unparsedTemplates as $key => $val) {
        $content = str_replace("<template_$key></template_$key>", $val . "\n", $content);
      }
    }
    return $content;
  }

  /**
   * This function parses the os-template and os-data script tags.
   * It's of vital importance to call this function *before* the rewriteContent function
   * since the html/dom parser of the later breaks, mangles and otherwise destroys the
   * os template/data script tags. So we need to expand the templates to pure html
   * before we can proceeed to dom parse the resulting document
   *
   * @param string $content html to parse
   */
  public function parseTemplates($content) {
    $osTemplates = array();
    $osDataRequests = array();
    // First extract all the os-data tags, and execute those in a single combined request, saves latency
    // and is consistent with other server implementations
    preg_match_all('/(<script.*type="text\/(os-data)".*>)(.*)(<\/script>)/imsxU', $content, $osDataRequests);
    $osDataRequestsCombined = '';
    foreach ($osDataRequests[0] as $match) {
      $osDataRequestsCombined .= $match . "\n";
      // Remove the reference from the html document
      $content = str_replace($match, '', $content);
    }
    if (! empty($osDataRequestsCombined)) {
      $this->performDataRequests($osDataRequestsCombined);
    }
    preg_match_all('/(<script.*type="text\/(os-template)".*>)(.*)(<\/script>)/imxsU', $content, $osTemplates);
    foreach ($osTemplates[0] as $match) {
      if (($renderedTemplate = $this->renderTemplate($match)) !== false) {
        // Template was rendered, insert the rendered html into the document
        $content = str_replace($match, $renderedTemplate, $content);
      } else {
        /*
         * The template could not be rendered, this could happen because:
         * - @require is present, and at least one of the required pieces of data is unavailable
         * - @name is present
         * - @autoUpdate == true
         * - disableAutoProcessing param on the opensocial-templates feature is true
         * So set a magic marker (<template_$index>) that after the dom document parsing will be replaced with the original script content
         */
        $index = count($this->unparsedTemplates);
        $this->unparsedTemplates[$index] = $match;
        $content = str_replace($match, "<template_$index></template_$index>", $content);
      }
    }
    return $content;
  }

  /**
   * Parses the OpenSocial RPC format data reply into the local data context
   *
   * @param array $array the data to add to the context
   */
  public function addContextData($array) {
    foreach ($array as $val) {
      // we really only accept entries with a request id, otherwise it can't be referenced by context anyhow
      if (isset($val['id'])) {
        $key = $val['id'];
        // Pick up only the actual data part of the response, so we can do direct variable resolution
        if (isset($val['data']['list'])) {
          $this->dataContext[$key] = $val['data']['list'];
        } elseif (isset($val['data']['entry'])) {
          $this->dataContext[$key] = $val['data']['entry'];
        } elseif (isset($val['data'])) {
          $this->dataContext[$key] = $val['data'];
        }
      }
    }
  }

  /**
   * Parses and performs the (combined) os-data requests
   *
   * @param string $osDataRequests
   */
  private function performDataRequests($osDataRequests) {
    //TODO check with the java implementation guys if they do a caching strategy here (same as with data-pipelining), would mean a much higher render performance..
    libxml_use_internal_errors(true);
    $this->doc = new DOMDocument(null, 'utf-8');
    $this->doc->preserveWhiteSpace = true;
    $this->doc->formatOutput = false;
    $this->doc->strictErrorChecking = false;
    $this->doc->recover = false;
    $this->doc->resolveExternals = false;

    $entityLoaderConfig = libxml_disable_entity_loader(true);
    $parseResult = $this->doc->loadXML($osDataRequests);
    libxml_disable_entity_loader($entityLoaderConfig);

    if ($parseResult) {
      $dataPipeliningRequests = array();
      // walk the one or multiple script tags, and build a combined request array
      foreach ($this->doc->childNodes as $childNode) {
        if ($childNode->tagName == 'script') {
          $dataPipeliningRequests = array_merge($dataPipeliningRequests, DataPipelining::Parse($childNode));
        }
      }
      // and perform the requests
      if (count($dataPipeliningRequests)) {
        $this->dataInserts = DataPipelining::fetch($dataPipeliningRequests, $this->context);
        $this->addContextData($this->dataInserts);
      }
    } else {
      echo "Error parsing os-data:\n" . XmlError::getErrors($osDataRequests);
    }
  }

  /**
   * Does the parsing of the template/data script content, then it hands
   * the os-data parsing to the DataPipeling class, and os-template tags to
   * the TemplateParser, and then returns the expanded template content (or '' on data)
   *
   * @param string $template
   * @return string
   */
  private function renderTemplate($template) {
    libxml_use_internal_errors(true);
    $this->doc = new DOMDocument(null, 'utf-8');
    $this->doc->preserveWhiteSpace = true;
    $this->doc->formatOutput = false;
    $this->doc->strictErrorChecking = false;
    $this->doc->recover = false;
    $this->doc->resolveExternals = false;

    $entityLoaderConfig = libxml_disable_entity_loader(true);
    $parseResult = $this->doc->loadXML($template);
    libxml_disable_entity_loader($entityLoaderConfig);

    if (! $parseResult) {
      return "Error parsing os-template:\n" . XmlError::getErrors($template);
    }
    if ($this->doc->childNodes->length < 1 || $this->doc->childNodes->length >> 1) {
      return 'Invalid script block';
    }
    $childNode = $this->doc->childNodes->item(0);
    if ($childNode->tagName == 'script' && $childNode->getAttribute('name') == null && $childNode->getAttribute('autoUpdate') != 'true') {
      // If the require tag is set, check to see if we have all required data parts, and if not leave it to the client to render
      if (($require = $childNode->getAttribute('require')) != null) {
        $requires = explode(',', $require);
        foreach ($requires as $val) {
          $val = trim($val);
          if (! isset($this->dataContext[$val])) {
            return false;
          }
        }
      }
      // Everything checked out, proceeding to render the template
      $parser = new TemplateParser();
      $parser->process($childNode, $this->dataContext);
      // unwrap the output, ie we only want the script block's content and not the main <script></script> node
      $output = new DOMDocument(null, 'utf-8');
      foreach ($childNode->childNodes as $node) {
        $outNode = $output->importNode($node, true);
        $output->appendChild($outNode);
      }
      // Restore single tags to their html variant, and remove the xml header
      $ret = str_replace(array(
          '<?xml version="" encoding="utf-8"?>', '<br/>'), array('', '<br>'), $output->saveXML());
      return $ret;
    }
    return false;
  }

  /**
   * Rewrites the content, based on shindig's configuration (force_rewrite) and/or the gadget's
   * spec params, it also injects the required html, css and javascript for the final gadget
   * using the dom observer methods for the head and body
   *
   * @param unknown_type $content
   * @return unknown
   */
  public function rewriteContent($content) {
    // Rewrite the content, this will rewrite resource links to proxied versions (if requested), sanitize if configured, and
    // add the various javascript tags to the document
    $rewriter = new GadgetRewriter($this->context);
    $rewriter->addObserver('head', $this, 'addHeadTags');
    $rewriter->addObserver('body', $this, 'addBodyTags');
    return $rewriter->rewrite($content, $this->gadget);
  }

  /**
   * Generates the body script content
   *
   * @return string script
   */
  public function getBodyScript() {
    $script = "gadgets.util.runOnLoadHandlers();";
    if ($this instanceof GadgetHrefRenderer) {
      $script .= " window.setTimeout(function(){gadgets.window.adjustHeight()}, 10);";
    }
    return $script;
  }

  /**
   * Append the runOnLoadHandlers script to the gadget's document body
   *
   * @param DOMElement $node
   * @param DOMDocument $doc
   */
  public function addBodyTags(DOMElement &$node, DOMDocument &$doc) {
    $script = $this->getBodyScript();
    $scriptNode = $doc->createElement('script');
    $scriptNode->setAttribute('type', 'text/javascript');
    $scriptNode->nodeValue = str_replace('&', '&amp;', $script);
    $node->appendChild($scriptNode);
  }

  public function getJavaScripts() {
    $forcedJsLibs = $this->getForcedJsLibs();
    $externalScript = false;
    if (! empty($forcedJsLibs)) {
      // if some of the feature libraries are externalized (through a browser cachable <script src="/gadgets/js/opensocial-0.9:settitle.js"> type url)
      // we inject the tag and don't inline those libs (and their dependencies)
      $forcedJsLibs = explode(':', $forcedJsLibs);
      $externalScript = Shindig_Config::get('default_js_prefix') . $this->getJsUrl($forcedJsLibs, $this->gadget) . "&container=" . $this->context->getContainer();
      $registry = $this->context->getRegistry();
      $missing = array();
      $registry->resolveFeatures($forcedJsLibs, $forcedJsLibs, $missing);
    }
    $script = '';
    foreach ($this->gadget->features as $feature) {
      if (! is_array($forcedJsLibs) || (is_array($forcedJsLibs) && ! in_array($feature, $forcedJsLibs))) {
        $script .= $this->context->getRegistry()->getFeatureContent($feature, $this->context, true);
      }
    }
    // Add the JavaScript initialization strings for the configuration, localization and preloads
    $script .= "\n";
    $script .= $this->appendJsConfig($this->gadget, count($forcedJsLibs));
    $script .= $this->appendMessages($this->gadget);
    $script .= $this->appendPreloads($this->gadget);
    if (count($this->dataInserts)) {
      foreach ($this->dataInserts as $data) {
        $key = $data['id'];
        $data = json_encode($data['data']);
        $script .= "opensocial.data.DataContext.putDataSet(\"$key\", $data);\n";
      }
    }
    return array('inline' => $script, 'external' => $externalScript);
  }

  /**
   * Adds the various bits of javascript to the gadget's document head element
   *
   * @param DOMElement $node
   * @param DOMDocument $doc
   */
  public function addHeadTags(DOMElement &$node, DOMDocument &$doc) {
    // Inject our configured gadget document style
    $styleNode = $doc->createElement('style');
    $styleNode->setAttribute('type', 'text/css');
    $styleNode->appendChild($doc->createTextNode(Shindig_Config::get('gadget_css')));
    $node->appendChild($styleNode);
    // Inject the OpenSocial feature javascripts
    $scripts = $this->getJavaScripts();
    if ($scripts['external']) {
      $scriptNode = $doc->createElement('script');
      $scriptNode->setAttribute('src', $scripts['external']);
      $node->appendChild($scriptNode);
    }
    $scriptNode = $doc->createElement('script');
    $scriptNode->setAttribute('type', 'text/javascript');
    $scriptNode->appendChild($doc->createTextNode($scripts['inline']));
    $node->appendChild($scriptNode);
  }

  /**
   * Retrieve the forced javascript libraries (if any), using either the &libs= from the query
   * or if that's empty, from the config
   *
   * @return unknown
   */
  private function getForcedJsLibs() {
    $forcedJsLibs = $this->context->getForcedJsLibs();
    // allow the &libs=.. param to override our forced js libs configuration value
    if (empty($forcedJsLibs)) {
      $forcedJsLibs = Shindig_Config::get('focedJsLibs');
    }
    return $forcedJsLibs;
  }

  /**
   * Appends the javascript features configuration string
   *
   * @param Shindig_Gadget $gadget
   * @param unknown_type $hasForcedLibs
   * @return string
   */
  private function appendJsConfig(Shindig_Gadget $gadget, $hasForcedLibs) {
    $container = $this->context->getContainer();
    $containerConfig = $this->context->getContainerConfig();
    //TODO some day we should parse the forcedLibs too, and include their config selectivly as well for now we just include everything if forced libs is set.
    if ($hasForcedLibs) {
      $gadgetConfig = $containerConfig->getConfig($container, 'gadgets.features');
    } else {
      $gadgetConfig = array();
      $featureConfig = $containerConfig->getConfig($container, 'gadgets.features');
      foreach ($gadget->getJsLibraries() as $library) {
        $feature = $library->getFeatureName();
        if (! isset($gadgetConfig[$feature]) && ! empty($featureConfig[$feature])) {
          $gadgetConfig[$feature] = $featureConfig[$feature];
        }
      }
    }
    // Add gadgets.util support. This is calculated dynamically based on request inputs.
    // See java/org/apache/shindig/gadgets/render/RenderingContentRewriter.java for reference.
    $requires = array();
    foreach ($gadget->features as $feature) {
      $requires[$feature] = new EmptyClass();
    }
    $gadgetConfig['core.util'] = $requires;
    if (isset($gadgetConfig['osml'])) {
      unset($gadgetConfig['osml']);
    }
    if (! isset($gadgetConfig['osapi.services']) || count($gadgetConfig['osapi.services']) == 1) {
      // this should really be set in config/container.js, but if not, we build a complete default set so at least most of it works out-of-the-box
      $gadgetConfig['osapi.services'] = array(
          'gadgets.rpc' => array('container.listMethods'),
          'http://%host%/social/rpc' => array("messages.update", "albums.update",
              "activities.delete", "activities.update",
              "activities.supportedFields", "albums.get",
              "activities.get", "mediaitems.update",
              "messages.get", "appdata.get",
              "system.listMethods", "people.supportedFields",
              "messages.create", "mediaitems.delete",
              "mediaitems.create", "people.get", "people.create",
              "albums.delete", "messages.delete",
              "appdata.update", "activities.create",
              "mediaitems.get", "albums.create",
              "appdata.delete", "people.update",
              "appdata.create"),
          'http://%host%/gadgets/api/rpc' => array('cache.invalidate'));
    }
    return "gadgets.config.init(" . json_encode($gadgetConfig) . ");\n";
  }

  /**
   * Injects the relevant translation message bundle into the javascript api
   *
   * @param Shindig_Gadget $gadget
   * @return string
   */
  private function appendMessages(Shindig_Gadget $gadget) {
    $msgs = '';
    if (! empty($gadget->gadgetSpec->locales)) {
      $msgs = json_encode($gadget->gadgetSpec->locales);
    }
    return "gadgets.Prefs.setMessages_($msgs);\n";
  }

  /**
   * Injects the preloaded content into the javascript api
   *
   * @param Shindig_Gadget $gadget
   * @return string
   */
  private function appendPreloads(Shindig_Gadget $gadget) {
    return "gadgets.io.preloaded_ = " . (count($gadget->gadgetSpec->preloads) ? json_encode($gadget->gadgetSpec->preloads) : "{}") . ";\n";
  }
}
