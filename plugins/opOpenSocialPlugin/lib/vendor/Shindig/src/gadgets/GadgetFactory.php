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
 * The Gadget Factory builds a gadget based on the current context and token and returns a fully processed
 * gadget ready to be rendered.
 *
 */
class GadgetFactory {
  /**
   * @var GadgetContext
   */
  private $context;
  private $token;

  public function __construct(GadgetContext $context, $token) {
    $this->context = $context;
    $this->token = $token;
  }

  /**
   * Returns the processed gadget spec
   *
   * @return GadgetSpec
   */
  public function createGadget() {
    $gadgetUrl = $this->context->getUrl();
    if ($this->context->getBlacklist() != null && $this->context->getBlacklist()->isBlacklisted($gadgetUrl)) {
      throw new GadgetException("The Gadget ($gadgetUrl) is blacklisted and can not be rendered");
    }
    // Fetch the gadget's content and create a GadgetSpec
    $gadgetContent = $this->fetchGadget($gadgetUrl);
    $gadgetSpecParser = new GadgetSpecParser();
    $gadgetSpec = $gadgetSpecParser->parse($gadgetContent);
    $gadget = new Shindig_Gadget($gadgetSpec, $this->context);

    // Process the gadget: fetching remote resources, processing & applying the correct translations, user prefs and feature resolving
    $this->fetchResources($gadget);
    $this->mergeLocales($gadget);
    $this->parseUserPrefs($gadget);
    $this->addSubstitutions($gadget);
    $this->applySubstitutions($gadget);
    $this->parseFeatures($gadget);
    return $gadget;
  }

  /**
   * Resolves the Required and Optional features and their dependencies into a real feature list using
   * the GadgetFeatureRegistry, which can be used to construct the javascript for the gadget
   *
   * @param Shindig_Gadget $gadget
   */
  private function parseFeatures(Shindig_Gadget &$gadget) {
    $found = $missing = array();
    if (!$this->context->getRegistry()->resolveFeatures(array_merge($gadget->gadgetSpec->requiredFeatures, $gadget->gadgetSpec->optionalFeatures), $found, $missing)) {
      $requiredMissing = false;
      foreach ($missing as $featureName) {
        if (in_array($featureName, $gadget->gadgetSpec->requiredFeatures)) {
          $requiredMissing = true;
          break;
        }
      }
      if ($requiredMissing) {
        throw new GadgetException("Unknown features: ".implode(',', $missing));
      }
    }
    unset($gadget->gadgetSpec->optionalFeatures);
    unset($gadget->gadgetSpec->requiredFeatures);
    $gadget->features = $found;
  }

  /**
   * Applies the substitutions to the complex types (preloads, user prefs, etc). Simple
   * types (author, title, etc) are translated on the fly in the gadget's getFoo() functions
   */
  private function applySubstitutions(Shindig_Gadget &$gadget) {
    // Apply the substitutions to the UserPrefs
    foreach ($gadget->gadgetSpec->userPrefs as $key => $pref) {
      $gadget->gadgetSpec->userPrefs[$key]['name'] = $gadget->substitutions->substitute($pref['name']);
      $gadget->gadgetSpec->userPrefs[$key]['displayName'] = $gadget->substitutions->substitute($pref['displayName']);
      $gadget->gadgetSpec->userPrefs[$key]['required'] = $gadget->substitutions->substitute($pref['required']);
      $gadget->gadgetSpec->userPrefs[$key]['datatype'] = $gadget->substitutions->substitute($pref['datatype']);
      $gadget->gadgetSpec->userPrefs[$key]['defaultValue'] = $gadget->substitutions->substitute($pref['defaultValue']);
      $gadget->gadgetSpec->userPrefs[$key]['value'] = $gadget->substitutions->substitute($pref['value']);
      if (isset($pref['enumValues'])) {
        foreach ($pref['enumValues'] as $enumKey => $enumVal) {
          $gadget->gadgetSpec->userPrefs[$key]['enumValues'][$enumKey]['value'] = $gadget->substitutions->substitute($enumVal['value']);
          $gadget->gadgetSpec->userPrefs[$key]['enumValues'][$enumKey]['displayValue'] = $gadget->substitutions->substitute($enumVal['displayValue']);
        }
      }
    }
    // Apply substitutions to the preloads
    foreach ($gadget->gadgetSpec->preloads as $key  => $preload) {
      $gadget->gadgetSpec->preloads[$key]['body'] = $gadget->substitutions->substitute($preload['body']);
    }
  }

  /**
   * Seeds the substitutions class with the user prefs, messages, bidi and module id
   */
  private function addSubstitutions(Shindig_Gadget &$gadget) {
    $gadget->substitutions = new Substitutions();
    if ($this->token) {
      $gadget->substitutions->addSubstitution('MODULE', "ID", $this->token->getModuleId());
    } else {
      $gadget->substitutions->addSubstitution('MODULE', "ID", 0);
    }
    if ($gadget->gadgetSpec->locales) {
      $gadget->substitutions->addSubstitutions('MSG', $gadget->gadgetSpec->locales);
    }
    $gadget->substitutions->addSubstitution('BIDI', "START_EDGE", $gadget->rightToLeft ? "right" : "left");
    $gadget->substitutions->addSubstitution('BIDI', "END_EDGE", $gadget->rightToLeft ? "left" : "right");
    $gadget->substitutions->addSubstitution('BIDI', "DIR", $gadget->rightToLeft ? "rtl" : "ltr");
    $gadget->substitutions->addSubstitution('BIDI', "REVERSE_DIR", $gadget->rightToLeft ? "ltr" : "rtl");
    foreach ($gadget->gadgetSpec->userPrefs as $pref) {
      $gadget->substitutions->addSubstitution('UP', $gadget->substitutions->substitute($pref['name']), $gadget->substitutions->substitute($pref['value']));
    }
  }
  /**
   * Process the UserPrefs values based on the current context
   *
   * @param Shindig_Gadget $gadget
   */
  private function parseUserPrefs(Shindig_Gadget &$gadget) {
    foreach ($gadget->gadgetSpec->userPrefs as $key => $pref) {
      $queryKey = 'up_'.$pref['name'];
      $gadget->gadgetSpec->userPrefs[$key]['value'] = isset($_GET[$queryKey]) ? trim(urldecode($_GET[$queryKey])) : $pref['defaultValue'];
    }
  }

  /**
   * Merges all matching Message bundles, with a full match (lang and country) having the
   * highest priority and all/all having the lowest.
   *
   * This distills the locales array's back to one array of translations, which is then exposed
   * through the $gadget->substitutions class
   *
   * @param Shindig_Gadget $gadget
   */
  private function mergeLocales(Shindig_Gadget $gadget) {
    if (count($gadget->gadgetSpec->locales)) {
      $contextLocale = $this->context->getLocale();
      $locales = $gadget->gadgetSpec->locales;
      $gadget->rightToLeft  = false;
      $full = $partial = $all = null;
      foreach ($locales as $locale) {
        if ($locale['lang'] == $contextLocale['lang'] && $locale['country'] == $contextLocale['country']) {
          $full = $locale['messageBundle'];
          $gadget->rightToLeft = $locale['languageDirection'] == 'rtl';
        } elseif ($locale['lang'] == $contextLocale['lang'] && $locale['country'] == 'all') {
          $partial = $locale['messageBundle'];
        } elseif ($locale['country'] == 'all' && $locale['lang'] == 'all') {
          $all = $locale['messageBundle'];
        }
      }
      $gadget->gadgetSpec->locales = array();
      // array_merge overwrites duplicate keys from param 2 over param 1, so $full takes precedence over partial, and it over all
      if ($full) $gadget->gadgetSpec->locales = array_merge($full, $gadget->gadgetSpec->locales);
      if ($partial) $gadget->gadgetSpec->locales = array_merge($partial, $gadget->gadgetSpec->locales);
      if ($all) $gadget->gadgetSpec->locales = array_merge($all, $gadget->gadgetSpec->locales);
    }
  }

  /**
   * Fetches all remote resources simultaniously using a multiFetchRequest to optimize rendering time.
   *
   * The preloads will be json_encoded to their gadget document injection format, and the locales will
   * be reduced to only the GadgetContext->getLocale matching entries.
   *
   * @param Shindig_Gadget $gadget
   * @param GadgetContext $context
   */
  private function fetchResources(Shindig_Gadget &$gadget) {
    $contextLocale = $this->context->getLocale();
    $unsignedRequests = $signedRequests = array();
    foreach ($gadget->getLocales() as $key => $locale) {
      // Only fetch the locales that match the current context's language and country
      if (($locale['country'] == 'all' && $locale['lang'] == 'all') || ($locale['lang'] == $contextLocale['lang'] && $locale['country'] == 'all') || ($locale['lang'] == $contextLocale['lang'] && $locale['country'] == $contextLocale['country'])) {
        if (!empty($locale['messages'])) {
          // locale matches the current context, add it to the requests queue
          $unsignedRequests[] = $locale['messages'];
        }
      } else {
        // remove any locales that are not applicable to this context
        unset($gadget->gadgetSpec->locales[$key]);
      }
    }
    // Add preloads to the request queue
    foreach ($gadget->getPreloads() as $preload) {
      if (!empty($preload['href'])) {
        if (!empty($preload['authz']) && $preload['authz'] == 'SIGNED') {
          if ($this->token == '') {
            throw new GadgetException("Signed preloading requested, but no valid security token set");
          }
          $signedRequests[] = $preload['href'];
        } else {
          $unsignedRequests[] = $preload['href'];
        }
      }
    }
    // Perform the non-signed requests
    foreach ($unsignedRequests as $key => $requestUrl) {
      $request = new RemoteContentRequest($requestUrl);
      $request->createRemoteContentRequestWithUri($requestUrl);
      $request->getOptions()->ignoreCache = $this->context->getIgnoreCache();
      $unsignedRequests[$key] = $request;
    }
    $responses = array();
    if (count($unsignedRequests)) {
      $brc = new BasicRemoteContent();
      $resps = $brc->multiFetch($unsignedRequests);
      foreach ($resps as $response) {
        $responses[$response->getUrl()] = array(
            'body' => $response->getResponseContent(),
            'rc' => $response->getHttpCode());
      }
    }
    // Perform the signed requests
    foreach ($signedRequests as $key => $requestUrl) {
      $request = new RemoteContentRequest($requestUrl);
      $request->setAuthType(RemoteContentRequest::$AUTH_SIGNED);
      $request->setNotSignedUri($requestUrl);
      $request->setToken($this->token);
      $request->getOptions()->ignoreCache = $this->context->getIgnoreCache();
      $signedRequests[$key] = $request;
    }
    if (count($signedRequests)) {
    	$signingFetcherFactory = new SigningFetcherFactory(Shindig_Config::get("private_key_file"));
      $remoteContent = new BasicRemoteContent(new BasicRemoteContentFetcher(), $signingFetcherFactory);
      $resps = $remoteContent->multiFetch($signedRequests);
      foreach ($resps as $response) {
        $responses[$response->getNotSignedUrl()] = array(
            'body' => $response->getResponseContent(),
            'rc' => $response->getHttpCode());
      }
    }
    // assign the results to the gadget locales and preloads (using the url as the key)
    foreach ($gadget->gadgetSpec->locales as $key => $locale) {
      if (!empty($locale['messages']) && isset($responses[$locale['messages']]) && $responses[$locale['messages']]['rc'] == 200) {
        $gadget->gadgetSpec->locales[$key]['messageBundle'] = $this->parseMessageBundle($responses[$locale['messages']]['body']);
      }
    }
    $preloads = array();
    foreach ($gadget->gadgetSpec->preloads as $key => $preload) {
      if (!empty($preload['href']) && isset($responses[$preload['href']]) && $responses[$preload['href']]['rc'] == 200) {
        $preloads[] = array_merge(array('id' => $preload['href']), $responses[$preload['href']]);
      }
    }
    $gadget->gadgetSpec->preloads = $preloads;
  }

  /**
   * Parses the (remote / fetched) message bundle xml
   *
   * @param string $messageBundleData
   * @return array (MessageBundle)
   */
  private function parseMessageBundle($messageBundleData) {
    libxml_use_internal_errors(true);
    $doc = new DOMDocument();

    $entityLoaderConfig = libxml_disable_entity_loader(true);
    $parseResult = $doc->loadXML($messageBundleData, LIBXML_NOCDATA);
    libxml_disable_entity_loader($entityLoaderConfig);

    if (! $parseResult) {
      throw new GadgetSpecException("Error parsing gadget xml:\n".XmlError::getErrors($messageBundleData));
    }
    $messageBundle = array();
    if (($messageBundleNode = $doc->getElementsByTagName('messagebundle')) != null && $messageBundleNode->length > 0) {
      $messageBundleNode = $messageBundleNode->item(0);
      $messages = $messageBundleNode->getElementsByTagName('msg');
      foreach ($messages as $msg) {
        $messageBundle[$msg->getAttribute('name')] = trim($msg->nodeValue);
      }
    }
    return $messageBundle;
  }

  /**
   * Fetches the gadget xml for the requested URL using the http fetcher
   *
   * @param unknown_type $gadgetUrl
   * @return string gadget's xml content
   */
  protected function fetchGadget($gadgetUrl) {
    $request = new RemoteContentRequest($gadgetUrl);
    $request->setToken($this->token);
    $request->getOptions()->ignoreCache = $this->context->getIgnoreCache();
    $xml = $this->context->getHttpFetcher()->fetch($request);
    if ($xml->getHttpCode() != '200') {
      throw new GadgetException("Failed to retrieve gadget content (recieved http code " . $xml->getHttpCode() . ")");
    }
    return $xml->getResponseContent();
  }
}
