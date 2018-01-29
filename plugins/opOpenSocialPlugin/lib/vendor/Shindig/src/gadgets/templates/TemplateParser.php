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

//TODO for some reason the OSML spec stats you have to <Require feature="osml"> to use the os:Name etc tags yet no such feature exists, and for the code path's here it's not required at all..
//TODO remove the os-templates javascript if all the templates are rendered on the server (saves many Kb's in gadget size)
//TODO support for OSML tags (os:name, os:peopleselector, os:badge) and OSML functions (os:render, osx:flash, osx:parsejson, etc)
//TODO support os-template tags on OSML tags, ie this should work: <os:Html if="${Foo}" repeat="${Bar}" />

require_once 'ExpressionParser.php';

class TemplateParser {
  private $dataContext;

  /**
   * Processes an os-template
   *
   * @param string $template
   */
  public function process(DOMnode &$osTemplate, $dataContext) {
    $this->setDataContext($dataContext);
    if ($osTemplate instanceof DOMElement) {
      $this->parseNode($osTemplate);
    }
  }

  /**
   * Sets and initializes the data context to use while processing the template
   *
   * @param array $dataContext
   */
  private function setDataContext($dataContext) {
    $this->dataContext = array();
    $this->dataContext['Top'] = $dataContext;
    $this->dataContext['Cur'] = array();
    $this->dataContext['My'] = array();
    $this->dataContext['Context'] = array('UniqueId' => uniqid());
  }

  private function parseNode(DOMNode &$node) {
    if ($node instanceof DOMText) {
      if (! $node->isWhitespaceInElementContent() && ! empty($node->wholeText)) {
        $this->parseNodeText($node);
      }
    } else {
      $tagName = $node->tagName;
      if (substr($tagName, 0, 3) == 'os:' || substr($tagName, 0, 4) == 'osx:') {
        $this->parseOsmlNode($node);
      } else {
        $this->parseNodeAttributes($node);
      }
    }
  }

  private function parseNodeText(DOMText &$node) {
    if (strpos($node->wholeText, '${') !== false) {
      $expressions = array();
      preg_match_all('/(\$\{)(.*)(\})/imsxU', $node->wholeText, $expressions);
      for ($i = 0; $i < count($expressions[0]); $i ++) {
        $toReplace = $expressions[0][$i];
        $expression = $expressions[2][$i];
        $expressionResult = ExpressionParser::evaluate($expression, $this->dataContext);
        $stringVal = htmlentities(ExpressionParser::stringValue($expressionResult), ENT_QUOTES, 'UTF-8');
        $node->nodeValue = str_replace($toReplace, $stringVal, $node->wholeText);
      }
    }
  }

  private function parseNodeAttributes(DOMNode &$node) {
    if ($node->hasAttributes()) {
      foreach ($node->attributes as $attr) {
        if (strpos($attr->value, '${') !== false) {
          $expressions = array();
          preg_match_all('/(\$\{)(.*)(\})/imsxU', $attr->value, $expressions);
          for ($i = 0; $i < count($expressions[0]); $i ++) {
            $toReplace = $expressions[0][$i];
            $expression = $expressions[2][$i];
            $expressionResult = ExpressionParser::evaluate($expression, $this->dataContext);
            switch (strtolower($attr->name)) {
              case 'repeat':
                // Can only loop if the result of the expression was an array
                if (! is_array($expressionResult)) {
                  throw new ExpressionException("Can't repeat on a singular var");
                }
                // Make sure the repeat variable doesn't show up in the cloned nodes (otherwise it would infinit recurse on this->parseNode())
                $node->removeAttribute('repeat');
                // For information on the loop context, see http://opensocial-resources.googlecode.com/svn/spec/0.9/OpenSocial-Templating.xml#rfc.section.10.1
                $this->dataContext['Context']['Count'] = count($expressionResult);
                foreach ($expressionResult as $index => $entry) {
                  $this->dataContext['Cur'] = $entry;
                  $this->dataContext['Context']['Index'] = $index;
                  // Clone this node and it's children
                  $newNode = $node->cloneNode(true);
                  // Append the parsed & expanded node to the parent
                  $newNode = $node->parentNode->insertBefore($newNode, $node);
                  // And parse it (using the global + loop context)
                  $this->parseNode($newNode, true);
                }
                // Remove the original (unparsed) node
                $node->parentNode->removeChild($node);
                // And remove the loop data context entries
                $this->dataContext['Cur'] = array();
                unset($this->dataContext['Context']['Index']);
                unset($this->dataContext['Context']['Count']);
                return;
                break;

              case 'if':
                if (! $expressionResult) {
                  $node->parentNode->removeChild($node);
                  return; // Since this node is removed, no sense in evaluating it's other attributes and/or child nodes
                }
                break;

              // These special cases that only apply for certain tag types
              case 'selected':
                if ($node->tagName == 'option') {
                  if ($expressionResult) {
                    $node->setAttribute('selected', 'selected');
                  } else {
                    $node->removeAttribute('selected');
                  }
                } else {
                  throw new ExpressionException("Can only use selected on an option tag");
                }
                break;

              case 'checked':
                if ($node->tagName == 'input') {
                  if ($expressionResult) {
                    $node->setAttribute('checked', 'checked');
                  } else {
                    $node->removeAttribute('checked');
                  }
                } else {
                  throw new ExpressionException("Can only use checked on an input tag");
                }
                break;

              case 'disabled':
                $disabledTags = array('input', 'button', 'select', 'textarea');
                if (in_array($node->tagName, $disabledTags)) {
                  if ($expressionResult) {
                    $node->setAttribute('disabled', 'disabled');
                  } else {
                    $node->removeAttribute('disabled');
                  }
                } else {
                  throw new ExpressionException("Can only use disabled on input, button, select and textarea tags");
                }
                break;

              default:
                // On non os-template spec attributes, do a simple str_replace with the evaluated value
                $stringVal = htmlentities(ExpressionParser::stringValue($expressionResult), ENT_QUOTES, 'UTF-8');
                $newAttrVal = str_replace($toReplace, $stringVal, $attr->value);
                $node->setAttribute($attr->name, $newAttrVal);
                break;
            }
          }
        }
      }
    }
    // if a repeat attribute was found, don't recurse on it's child nodes, the repeat handling already did that
    if (isset($node->childNodes) && $node->childNodes->length > 0) {
      // recursive loop to all this node's children
      foreach ($node->childNodes as $childNode) {
        $this->parseNode($childNode);
      }
    }
  }

  /**
   * Function that handles the os: and osx: tags
   *
   * @param DOMNode $node
   */
  private function parseOsmlNode(DOMNode &$node) {
    $tagName = strtolower($node->tagName);
    switch ($tagName) {

      // Control statements

      case 'os:repeat':
        if (!$node->getAttribute('expression')) {
          throw new ExpressionException("Invalid os:Repeat tag, missing expression attribute");
        }
        $expressions = array();
        preg_match_all('/(\$\{)(.*)(\})/imsxU', $node->getAttribute('expression'), $expressions);
        $expression = $expressions[2][0];
        $expressionResult = ExpressionParser::evaluate($expression, $this->dataContext);
        if (! is_array($expressionResult)) {
          throw new ExpressionException("Can't repeat on a singular var");
        }
        // For information on the loop context, see http://opensocial-resources.googlecode.com/svn/spec/0.9/OpenSocial-Templating.xml#rfc.section.10.1
        $this->dataContext['Context']['Count'] = count($expressionResult);
        foreach ($expressionResult as $index => $entry) {
          $this->dataContext['Cur'] = $entry;
          $this->dataContext['Context']['Index'] = $index;
          foreach ($node->childNodes as $childNode) {
            $newNode = $childNode->cloneNode(true);
            $this->parseNode($newNode);
            $node->parentNode->insertBefore($newNode, $node);
          }
        }
        $node->parentNode->removeChild($node);
        $this->dataContext['Cur'] = array();
        unset($this->dataContext['Context']['Index']);
        unset($this->dataContext['Context']['Count']);
        break;

      case 'os:if':
        $expressions = array();
        if (!$node->getAttribute('condition')) {
          throw new ExpressionException("Invalid os:If tag, missing condition attribute");
        }
        preg_match_all('/(\$\{)(.*)(\})/imsxU', $node->getAttribute('condition'), $expressions);
        $expression = $expressions[2][0];
        $expressionResult = ExpressionParser::evaluate($expression, $this->dataContext);
        if ($expressionResult) {
          foreach ($node->childNodes as $childNode) {
            $newNode = $childNode->cloneNode(true);
            $this->parseNode($newNode);
            $node->insertBefore($newNode);
          }
        }
        $node->parentNode->removeChild($node);
        break;

      // OSML tags (os: name space)

      case 'os:name':
        break;

      case 'os:peopleselector':
        break;

      case 'os:badge':
        break;

      case 'os:html':
         if (!$node->getAttribute('code')) {
          throw new ExpressionException("Invalid os:Html tag, missing code attribute");
        }
        //FIXME this seems to not work out to well, probably need to use the original domdocument to $doc->createTextNode() to make this work
        $newNode = new DOMText();
        $newNode->nodeValue = $node->getAttribute('code');
        $node->parentNode->replaceChild($newNode, $node);
        break;

      case 'os:render':
        break;

      // Extension - Tags

      case 'osx:flash':
        break;

      case 'osx:navigatetoapp':
        break;

      case 'osx:navigatetoperson':
        break;

      // Extension - Functions

      case 'osx:parsejson':
        break;

      case 'osx:decodebase64':
        break;

      case 'osx:urlencode':
        break;

      case 'osx:urldecode':
        break;

    }
  }

}
