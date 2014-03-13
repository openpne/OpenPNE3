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
 * Format = xml output converter, for format definition see:
 * http://docs.google.com/View?docid=dcc2jvzt_37hdzwkmf8
 */
class OutputXmlConverter extends OutputConverter {
  private static $xmlVersion = '1.0';
  private static $charSet = 'UTF-8';
  private static $formatOutput = true;
  
  // this maps the REST url to the xml tags
  private static $entryTypes = array('people' => 'entry', 'appdata' => 'entry', 
      'activities' => 'entry', 'messages' => 'entry');
  private $doc;

  function outputResponse(ResponseItem $responseItem, RestRequestItem $requestItem) {
    $doc = $this->createXmlDoc();
    $requestType = $this->getRequestType($requestItem);
    $data = $responseItem->getResponse();
    
    // Check to see if this is a single entry, or a collection, and construct either an xml 
    // feed (collection) or an entry (single)		
    if ($data instanceof RestfulCollection) {
      $totalResults = $data->getTotalResults();
      $itemsPerPage = $requestItem->getCount();
      $startIndex = $requestItem->getStartIndex();
      
      // The root Feed element
      $entry = $this->addNode($doc, 'response', '');
      
      // Required Xml fields
      $this->addNode($entry, 'startIndex', $startIndex);
      $this->addNode($entry, 'itemsPerPage', $itemsPerPage);
      $this->addNode($entry, 'totalResults', $totalResults);
      $responses = $data->getEntry();
      foreach ($responses as $response) {
        // recursively add responseItem data to the xml structure
        $this->addData($entry, $requestType, $response);
      }
    } else {
      // Single entry = Xml:Entry	
      $entry = $this->addNode($doc, 'response', '');
      // addData loops through the responseItem data recursively creating a matching XML structure
      $this->addData($entry, 'entry', $data['entry']);
    }
    $xml = $doc->saveXML();
    echo $xml;
  }

  function outputBatch(Array $responses, SecurityToken $token) {
    throw new Exception("XML batch not supported");
  }

  /**
   * Easy shortcut for creating & appending XML nodes
   *
   * @param DOMElement $node node to append the new child node too
   * @param string $name name of the new element
   * @param string $value value of the element, if empty no text node is created
   * @param array $attributes optional array of attributes, false by default. If set attributes are added to the node using the key => val pairs
   * @return DOMElement node
   */
  private function addNode($node, $name, $value = '', $attributes = false) {
    return OutputBasicXmlConverter::addNode($this->doc, $node, $name, $value, $attributes);
  }

  /**
   * Creates the root document using our xml version & charset
   *
   * @return DOMDocument
   */
  private function createXmlDoc() {
    $this->doc = new DOMDocument(self::$xmlVersion, self::$charSet);
    $this->doc->formatOutput = self::$formatOutput;
    return $this->doc;
  }

  /**
   * Extracts the Xml entity name from the request url
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
   * @return DOMElement returns newly created element
   */
  private function addData(DOMElement $element, $name, $data) {
    return OutputBasicXmlConverter::addData($this->doc, $element, $name, $data);
  }
}
