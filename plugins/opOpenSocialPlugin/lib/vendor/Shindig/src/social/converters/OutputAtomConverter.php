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
 * Format = atom output converter, for format definition see:
 * http://sites.google.com/a/opensocial.org/opensocial/Technical-Resources/opensocial-spec-v08/restful-api-specification
 */
class OutputAtomConverter extends OutputConverter {
  private static $nameSpace = 'http://www.w3.org/2005/Atom';
  private static $osNameSpace = 'http://ns.opensocial.org/2008/opensocial';
  private static $xmlVersion = '1.0';
  private static $charSet = 'UTF-8';
  private static $formatOutput = true;
  // this maps the REST url to the atom content type
  private static $entryTypes = array('people' => 'entry', 'appdata' => 'entry',
      'activities' => 'entry', 'messages' => 'entry');
  private $doc;

  function outputResponse(ResponseItem $responseItem, RestRequestItem $requestItem) {
    $doc = $this->createAtomDoc();
    $requestType = $this->getRequestType($requestItem);
    $data = $responseItem->getResponse();
    $params = $requestItem->getParameters();
    $userId = isset($params['userId']) ? $params['userId'][0] : '';
    $guid = 'urn:guid:' . $userId;
    $authorName = $_SERVER['HTTP_HOST'] . ':' . $userId;
    $updatedAtom = date(DATE_ATOM);

    // Check to see if this is a single entry, or a collection, and construct either an atom
    // feed (collection) or an entry (single)
    if ($data instanceof RestfulCollection) {
      $totalResults = $data->getTotalResults();
      $itemsPerPage = $requestItem->getCount();
      $startIndex = $requestItem->getStartIndex();

      // The root Feed element
      $entry = $this->addNode($doc, 'feed', '', false, self::$nameSpace);

      // Required Atom fields
      $endPos = ($startIndex + $itemsPerPage) > $totalResults ? $totalResults : ($startIndex + $itemsPerPage);
      $this->addNode($entry, 'title', $requestType . ' feed for id ' . $authorName . ' (' . $startIndex . ' - ' . ($endPos - 1) . ' of ' . $totalResults . ')');
      $author = $this->addNode($entry, 'author');
      $this->addNode($author, 'uri', $guid);
      $this->addNode($author, 'name', $authorName);
      $this->addNode($entry, 'updated', $updatedAtom);
      $this->addNode($entry, 'id', $guid);
      $this->addNode($entry, 'link', '', array('rel' => 'self',
          'href' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
      // Add osearch & next link to the entry
      $this->addPagingFields($entry, $startIndex, $itemsPerPage, $totalResults);
      // Add response entries to feed
      $responses = $data->getEntry();
      foreach ($responses as $response) {
        // Attempt to have a real ID field, otherwise we fall back on the idSpec id
        $idField = is_object($response) && isset($response->id) ? $response->id : (is_array($response) && isset($response['id']) ? $response['id'] : $requestItem->getUser()->getUserId($requestItem->getToken()));
        // construct <entry> blocks this record
        $feedEntry = $this->addNode($entry, 'entry');
        $content = $this->addNode($feedEntry, 'content', '', array(
            'type' => 'application/xml'));
        // Author node
        $author = $this->addNode($feedEntry, 'author');
        $this->addNode($author, 'uri', $guid);
        $this->addNode($author, 'name', $authorName);
        // Special hoisting rules for activities
        if ($response instanceof Activity) {
          $this->addNode($feedEntry, 'category', '', array('term' => 'status'));
          $this->addNode($feedEntry, 'updated', date(DATE_ATOM, $response->postedTime));
          $this->addNode($feedEntry, 'id', 'urn:guid:' . $response->id);
          //FIXME should add a link field but don't have URL's available yet:
          // <link rel="self" type="application/atom+xml" href="http://api.example.org/activity/feeds/.../af3778"/>
          $this->addNode($feedEntry, 'title', strip_tags($response->title));
          $this->addNode($feedEntry, 'summary', $response->body);
          // Unset them so addData doesn't include them again
          unset($response->postedTime);
          unset($response->id);
          unset($response->title);
          unset($response->body);
        } else {
          $this->addNode($feedEntry, 'id', 'urn:guid:' . $idField);
          $this->addNode($feedEntry, 'title', $requestType . ' feed entry for id ' . $idField);
          $this->addNode($feedEntry, 'updated', $updatedAtom);
        }

        // recursively add responseItem data to the xml structure
        $this->addData($content, $requestType, $response, self::$osNameSpace);
      }
    } else {
      // Single entry = Atom:Entry
      $entry = $doc->appendChild($doc->createElementNS(self::$nameSpace, "entry"));
      // Atom fields
      $this->addNode($entry, 'title', $requestType . ' entry for ' . $authorName);
      $author = $this->addNode($entry, 'author');
      $this->addNode($author, 'uri', $guid);
      $this->addNode($author, 'name', $authorName);
      $this->addNode($entry, 'id', $guid);
      $this->addNode($entry, 'updated', $updatedAtom);
      $content = $this->addNode($entry, 'content', '', array('type' => 'application/xml'));
      // addData loops through the responseItem data recursively creating a matching XML structure
      $this->addData($content, $requestType, $data['entry'], self::$osNameSpace);
    }
    $xml = $doc->saveXML();
    if ($responseItem->getResponse() instanceof RestfulCollection) {
      //FIXME dirty hack until i find a way to add multiple name spaces using DomXML functions
      $xml = str_replace('<feed xmlns="http://www.w3.org/2005/Atom">', '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:osearch="http://a9.com/-/spec/opensearch/1.1">', $xml);
    }
    echo $xml;
  }

  function outputBatch(Array $responses, SecurityToken $token) {
    throw new Exception("Atom batch not supported");
  }

  /**
   * Easy shortcut for creating & appending XML nodes
   *
   * @param DOMElement $node node to append the new child node too
   * @param string $name name of the new element
   * @param string $value value of the element, if empty no text node is created
   * @param array $attributes optional array of attributes, false by default. If set attributes are added to the node using the key => val pairs
   * @param string $nameSpace optional namespace to use when creating node
   * @return DOMElement node
   */
  private function addNode($node, $name, $value = '', $attributes = false, $nameSpace = false) {
    return OutputBasicXmlConverter::addNode($this->doc, $node, $name, $value, $attributes, $nameSpace);
  }

  /**
   * Adds the osearch fields & generates a next link if result set > itemsPerPage
   *
   * @param DOMElement $entry the entry DOMElement to append the links too
   * @param int $startIndex
   * @param int $itemsPerPage
   * @param int $totalResults
   */
  private function addPagingFields($entry, $startIndex, $itemsPerPage, $totalResults) {
    $this->addNode($entry, 'osearch:totalResults', $totalResults);
    $this->addNode($entry, 'osearch:startIndex', $startIndex ? $startIndex : '0');
    $this->addNode($entry, 'osearch:itemsPerPage', $itemsPerPage);
    // Create a 'next' link based on our current url if this is a pageable collection & there is more to display
    if (($startIndex + $itemsPerPage) < $totalResults) {
      $nextStartIndex = ($startIndex + $itemsPerPage) - 1;
      if (($uri = $_SERVER['REQUEST_URI']) === false) {
        throw new Exception("Could not parse URI : {$_SERVER['REQUEST_URI']}");
      }
      $uri = parse_url($uri);
      $params = array();
      if (isset($uri['query'])) {
        parse_str($uri['query'], $params);
      }
      $params[RestRequestItem::$START_INDEX] = $nextStartIndex;
      $params[RestRequestItem::$COUNT] = $itemsPerPage;
      foreach ($params as $paramKey => $paramVal) {
        $outParams[] = $paramKey . '=' . $paramVal;
      }
      $outParams = '?' . implode('&', $outParams);
      $nextUri = 'http://' . $_SERVER['HTTP_HOST'] . $uri['path'] . $outParams;
      $this->addNode($entry, 'link', '', array('rel' => 'next', 'href' => $nextUri));
    }
  }

  /**
   * Creates the root document using our xml version & charset
   *
   * @return DOMDocument
   */
  private function createAtomDoc() {
    $this->doc = new DOMDocument(self::$xmlVersion, self::$charSet);
    $this->doc->formatOutput = self::$formatOutput;
    return $this->doc;
  }

  /**
   * Extracts the Atom entity name from the request url
   *
   * @param RequestItem $requestItem the request item
   * @return string the request type
   */
  private function getRequestType($requestItem) {
    return OutputBasicXmlConverter::getRequestType($requestItem, self::$entryTypes);
  }

  /**
   * Recursive function that maps an data array or object to it's xml represantation
   *
   * @param DOMElement $element the element to append the new node(s) to
   * @param string $name the name of the to be created node
   * @param array or object $data the data to map to xml
   * @param string $nameSpace if specified, the node is created using this namespace
   * @return DOMElement returns newly created element
   */
  private function addData(DOMElement $element, $name, $data, $nameSpace = false) {
    return OutputBasicXmlConverter::addData($this->doc, $element, $name, $data, $nameSpace);
  }
}
