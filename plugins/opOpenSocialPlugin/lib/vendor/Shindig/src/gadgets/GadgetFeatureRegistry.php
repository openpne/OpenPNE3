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
 * Class that deals with the processing, loading and dep resolving of the gadget features
 * Features are javascript libraries that provide an API, like 'opensocial' or 'settitle'
 *
 */
class GadgetFeatureRegistry {
  public $features;
  private $coreDone = false;
  private $coreFeaturs;

  public function __construct($featurePath) {
    $this->registerFeatures($featurePath);
  }

  public function getFeatureContent($feature, GadgetContext $context, $isGadgetContext) {
    if (empty($feature)) return '';
    if (!isset($this->features[$feature])) {
      throw new GadgetException("Invalid feature: ".htmlentities($feature));
    }
    $featureName = $feature;
    $feature = $this->features[$feature];
    $filesContext = $isGadgetContext ? 'gadgetJs' : 'containerJs';
    if (!isset($feature[$filesContext])) {
      // no javascript specified for this context
      return '';
    }
    $ret = '';
    if (Shindig_Config::get('compress_javascript')) {
      $featureCache = Cache::createCache(Shindig_Config::get('feature_cache'), 'FeatureCache');
      if (($featureContent = $featureCache->get(md5('features:'.$featureName.$isGadgetContext)))) {
        return $featureContent;
      }
    }
    foreach ($feature[$filesContext] as $entry) {
      switch ($entry['type']) {
        case 'URL':
          $request = new RemoteContentRequest($entry['content']);
          $request->getOptions()->ignoreCache = $context->getIgnoreCache();
          $context->getHttpFetcher()->fetch($request);
          if ($request->getHttpCode() == '200') {
            $ret .= $request->getResponseContent()."\n";
          }
          break;
        case 'FILE':
          $file = $feature['basePath'] . '/' . $entry['content'];
          $ret .= file_get_contents($file). "\n";
          break;
        case 'INLINE':
          $ret .= $entry['content'] . "\n";
          break;
      }
    }
    if (Shindig_Config::get('compress_javascript')) {
      $ret = JsMin::minify($ret);
      $featureCache->set(md5('features:'.$featureName.$isGadgetContext), $ret);
    }
    return $ret;
  }

  public function resolveFeatures($needed, &$resultsFound, &$resultsMissing) {
    $resultsFound = array();
    $resultsMissing = array();
    if (! count($needed)) {
      // Shortcut for gadgets that don't have any explicit dependencies.
      $resultsFound = $this->coreFeatures;
      return true;
    }
    foreach ($needed as $featureName) {
      $feature = isset($this->features[$featureName]) ? $this->features[$featureName] : null;
      if ($feature == null) {
        $resultsMissing[] = $featureName;
      } else {
        $this->addFeatureToResults($resultsFound, $feature);
      }
    }
    return count($resultsMissing) == 0;
  }

  private function addFeatureToResults(&$results, $feature) {
    if (in_array($feature['name'], $results)) {
      return;
    }
    foreach ($feature['deps'] as $dep) {
      $this->addFeatureToResults($results, $this->features[$dep]);
    }
    if (!in_array($feature['name'], $results)) {
      $results[] = $feature['name'];
    }
  }

  /**
   * Loads the features present in the $featurePath
   *
   * @param string $featurePath path to scan
   */
  private function registerFeatures($featurePath) {
    $this->features = array();
    // Load the features from the shindig/features/features.txt file
    $featuresFile = $featurePath . '/features.txt';
    if (Shindig_File::exists($featuresFile)) {
      $files = explode("\n", file_get_contents($featuresFile));
      // custom sort, else core.io seems to bubble up before core, which breaks the dep chain order
      usort($files, array($this, 'sortFeaturesFiles'));
      foreach ($files as $file) {
        if (! empty($file) && strpos($file, 'feature.xml') !== false && substr($file, 0, 1) != '#' && substr($file, 0, 2) != '//') {
          $file = realpath($featurePath . '/../' . trim($file));
          $feature = $this->processFile($file);
          $this->features[$feature['name']] = $feature;
        }
      }
    }
    // Determine the core features
    $this->coreFeatures = array();
    foreach ($this->features as $entry) {
      if (strtolower(substr($entry['name'], 0, strlen('core'))) == 'core') {
        $this->coreFeatures[$entry['name']] = $entry['name'];
      }
    }
    // And make sure non-core features depend on core.
    foreach ($this->features as $key => $entry) {
      if ($entry == null) {
        continue;
      }
      if (strtolower(substr($entry['name'], 0, strlen('core'))) != 'core') {
        $this->features[$key]['deps'] = array_merge($entry['deps'], $this->coreFeatures);
      }
    }
  }

  /**
   * Loads the feature's xml content
   *
   * @param unknown_type $file
   * @return unknown
   */
  private function processFile($file) {
    $feature = null;
    if (!empty($file) && Shindig_File::exists($file)) {
      if (($content = file_get_contents($file))) {
        $feature = $this->parse($content, dirname($file));
      }
    }
    return $feature;
  }

  /**
   * Parses the feature's XML content
   *
   * @param string $content
   * @param string $path
   * @return feature array
   */
  private function parse($content, $path) {
    $entityLoaderConfig = libxml_disable_entity_loader(true);
    $doc = simplexml_load_string($content);
    libxml_disable_entity_loader($entityLoaderConfig);

    $feature = array();
    $feature['deps'] = array();
    $feature['basePath'] = $path;
    if (! isset($doc->name)) {
      throw new GadgetException('Invalid name in feature: ' . $path);
    }
    $feature['name'] = trim($doc->name);
    foreach ($doc->gadget as $gadget) {
      $this->processContext($feature, $gadget, false);
    }
    foreach ($doc->container as $container) {
      $this->processContext($feature, $container, true);
    }
    foreach ($doc->dependency as $dependency) {
      $feature['deps'][trim($dependency)] = trim($dependency);
    }
    return $feature;
  }

  /**
   * Processes the feature's entries
   *
   * @param array $feature
   * @param string $context
   * @param boolean $isContainer
   */
  private function processContext(&$feature, $context, $isContainer) {
    foreach ($context->script as $script) {
      $attributes = $script->attributes();
      if (! isset($attributes['src'])) {
        // inline content
        $type = 'INLINE';
        $content = (string)$script;
      } else {
        $content = trim($attributes['src']);
        if (strtolower(substr($content, 0, strlen("http://"))) == "http://" || strtolower(substr($content, 0, strlen("https://"))) == "https://") {
          $type = 'URL';
        } else {
          $type = 'FILE';
          // skip over any java resource files (res://) since we don't support them
          if (substr($content, 0, 6) == 'res://') {
            continue;
          }
          $content = $content;
        }
      }
      $library = array('type' => $type, 'content' => $content);
      if ($library != null) {
        if ($isContainer) {
          $feature['containerJs'][] = $library;
        } else {
          $feature['gadgetJs'][] = $library;
        }
      }
    }
  }

  private function sortFeaturesFiles($feature1, $feature2) {
    $feature1 = basename(str_replace('/feature.xml', '', $feature1));
    $feature2 = basename(str_replace('/feature.xml', '', $feature2));
    if ($feature1 == $feature2) {
      return 0;
    }
    return ($feature1 < $feature2) ? - 1 : 1;
  }
}
