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
 * Basic methods for OutputAtomConverter and OutputXmlConverter.
 */
class OutputBasicXmlConverter {

  /**
   * Extracts the Xml entity name from the request url
   *
   * @param RequestItem $requestItem the request item
   * @param array $entryTypes the map of entries
   * @return string the request type
   */
  public static function getRequestType($requestItem, $entryTypes) {
    // map the Request URL to the content type to use  
    $params = $requestItem->getParameters();
    if (! is_array($params)) {
      throw new Exception("Unsupported request type");
    }
    $type = false;
    foreach ($params as $key => $val) {
      if (isset($entryTypes[$key])) {
        $type = $entryTypes[$key];
        break;
      }
    }
    if (! $type) {
      throw new Exception("Unsupported request type");
    }
    return $type;
  }

  /**
   * Easy shortcut for creating & appending XML nodes
   *
   * @param DOMDocument $doc document the root document
   * @param DOMElement $node node to append the new child node to
   * @param string $name name of the new element
   * @param string $value value of the element, if empty no text node is created
   * @param array $attributes optional array of attributes, false by default. If set attributes are added to the node using the key => val pairs
   * @param string $nameSpace optional namespace to use when creating node
   * @return DOMElement node
   */
  public static function addNode(DOMDocument $doc, $node, $name, $value = '', $attributes = false, $nameSpace = false) {
    if ($nameSpace) {
      $childNode = $node->appendChild($doc->createElementNS($nameSpace, $name));
    } else {
      $childNode = $node->appendChild($doc->createElement($name));
    }
    if (! empty($value) || $value == '0') {
      $childNode->appendChild($doc->createTextNode($value));
    }
    if ($attributes && is_array($attributes)) {
      foreach ($attributes as $attrName => $attrVal) {
        $childNodeAttr = $childNode->appendChild($doc->createAttribute($attrName));
        if (! empty($attrVal)) {
          $childNodeAttr->appendChild($doc->createTextNode($attrVal));
        }
      }
    }
    return $childNode;
  }

  /**
   * Recursive function that maps an data array or object to it's xml represantation 
   *
   * @param DOMDocument $doc the root document
   * @param DOMElement $element the element to append the new node(s) to
   * @param string $name the name of the to be created node
   * @param array or object $data the data to map to xml
   * @param string $nameSpace if specified, the node is created using this namespace
   * @return DOMElement returns newly created element
   */
  public static function addData(DOMDocument $doc, DOMElement $element, $name, $data, $nameSpace = false) {
    if ($nameSpace) {
      $newElement = $element->appendChild($doc->createElementNS($nameSpace, $name));
    } else {
      $newElement = $element->appendChild($doc->createElement($name));
    }
    if (is_array($data)) {
      foreach ($data as $key => $val) {
        if (is_array($val) || is_object($val)) {
          // prevent invalid names.. try to guess a good one :)
          if (is_numeric($key)) {
            $key = is_object($val) ? get_class($val) : $key = $name;
          }
          self::addData($doc, $newElement, $key, $val);
        } else {
          if (is_numeric($key)) {
            $key = is_object($val) ? get_class($val) : $key = $name;
          }
          $elm = $newElement->appendChild($doc->createElement($key));
          $elm->appendChild($doc->createTextNode($val));
        }
      }
    } elseif (is_object($data)) {
      if ($data instanceof Enum) {
        if (isset($data->key)) {
          // enums are output as : <NAME key="$key">$displayValue</NAME> 
          $keyEntry = $newElement->appendChild($doc->createAttribute('key'));
          $keyEntry->appendChild($doc->createTextNode($data->key));
          $newElement->appendChild($doc->createTextNode($data->getDisplayValue()));
        }
      } else {
        $vars = get_object_vars($data);
        foreach ($vars as $key => $val) {
          if (is_array($val) || is_object($val)) {
            // prevent invalid names.. try to guess a good one :)
            if (is_numeric($key)) {
              $key = is_object($val) ? get_class($val) : $key = $name;
            }
            self::addData($doc, $newElement, $key, $val);
          } else {
            $elm = $newElement->appendChild($doc->createElement($key));
            $elm->appendChild($doc->createTextNode($val));
          }
        }
      }
    } else {
      $newElement->appendChild($doc->createTextNode($data));
    }
    return $newElement;
  }
}
