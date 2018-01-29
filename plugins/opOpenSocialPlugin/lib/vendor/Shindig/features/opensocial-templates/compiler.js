/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
/**
 * @fileoverview Implements compiler functionality for OpenSocial Templates.
 *
 * TODO(davidbyttow): Move into os.Compiler.
 */

/**
 * Literal semcolons have special meaning in JST, so we need to change them to
 * variable references.
 */
os.SEMICOLON = ';';

/**
 * Check if the browser is Internet Explorer.
 *
 * TODO(levik): Find a better, more general way to do this, esp. if we need
 * to do other browser checks elswhere.
 */
os.isIe = navigator.userAgent.indexOf('Opera') != 0 &&
    navigator.userAgent.indexOf('MSIE') != -1;

/**
 * Takes an XML node containing Template markup and compiles it into a Template.
 * The node itself is not considered part of the markup.
 * @param {Node} node XML node to be compiled.
 * @param {string} opt_id An optional ID for the new template.
 * @return {os.Template} A compiled Template object.
 */
os.compileXMLNode = function(node, opt_id) {
  var nodes = [];
  for (var child = node.firstChild; child; child = child.nextSibling) {
    if (child.nodeType == DOM_ELEMENT_NODE) {
      nodes.push(os.compileNode_(child));
    } else if (child.nodeType == DOM_TEXT_NODE) {
      if (child != node.firstChild ||
          !child.nodeValue.match(os.regExps_.ONLY_WHITESPACE)) {
        var compiled = os.breakTextNode_(child);
        for (var i = 0; i < compiled.length; i++) {
          nodes.push(compiled[i]);
        }
      }
    }
  }
  var template = new os.Template(opt_id);
  template.setCompiledNodes_(nodes);
  return template;
};

/**
 * Takes an XML Document and compiles it into a Template object.
 * @param {Document} doc XML document to be compiled.
 * @param {string} opt_id An optional ID for the new template.
 * @return {os.Template} A compiled Template object.
 */
os.compileXMLDoc = function(doc, opt_id) {
  var node = doc.firstChild;
  // Find the <root> node (skip DOCTYPE).
  while (node.nodeType != DOM_ELEMENT_NODE) {
    node = node.nextSibling;
  }

  return os.compileXMLNode(node, opt_id);
};

/**
 * Map of special operators to be transformed.
 */
os.operatorMap = {
  'and': '&&',
  'eq': '==',
  'lte': '<=',
  'lt': '<',
  'gte': '>=',
  'gt': '>',
  'neq': '!=',
  'or': '||',
  'not': '!'
};

/**
 * Shared regular expression to split a string into lexical parts. Quoted 
 * strings are treated as tokens, so are identifiers and any characters between 
 * them.
 * In "foo + bar = 'baz - bing'", the tokens are
 *   ["foo", " + ", "bar", " = ", "'baz - bing'"]
 */
os.regExps_.SPLIT_INTO_TOKENS = 
  /"(?:[^"\\]|\\.)*"|'(?:[^'\\]|\\.)*'|\w+|[^"'\w]+/g;
  
/**
 * Parses operator markup into JS code. See operator map above.
 *
 * TODO: Simplify this to only work on neccessary operators - binary ones that
 * use "<" or ">".
 *
 * @param {string} src The string snippet to parse.
 */
os.remapOperators_ = function(src) {
  return src.replace(os.regExps_.SPLIT_INTO_TOKENS, 
      function (token) { 
        return os.operatorMap.hasOwnProperty(token) ? 
            os.operatorMap[token] : token;
      });
};

/**
 * Remap variable references in the expression.
 * @param {string} expr The expression to transform.
 * @return {string} Transformed exression.
 */
os.transformVariables_ = function(expr) {
  expr = os.replaceTopLevelVars_(expr);

  return expr;
};

/**
 * Map of variables to transform
 */
os.variableMap_ = {
  'my': os.VAR_my,
  'My': os.VAR_my,
  'cur': VAR_this,
  'Cur': VAR_this,
  '$cur': VAR_this,
  'Top': VAR_top,
  'Context': VAR_loop  
};

/**
 * Replace the top level variables
 * @param {string} expr The expression
 * @return {string} Expression with replacements
 */
os.replaceTopLevelVars_ = function(text) {

  var regex;

  regex = os.regExps_.TOP_LEVEL_VAR_REPLACEMENT;
  if (!regex) {
    regex = /(^|[^.$a-zA-Z0-9])([$a-zA-Z0-9]+)/g;
    os.regExps_.TOP_LEVEL_VAR_REPLACEMENT = regex;
  }

  return text.replace(regex,
      function (whole, left, right) { 
        if (os.variableMap_.hasOwnProperty(right)) { 
          return left + os.variableMap_[right]; 
        } else { 
          return whole; 
        } 
      });
};

/**
 * This function is used to lookup named properties of objects.
 * By default only a simple lookup is performed, but using
 * os.setIdentifierResolver() it's possible to plug in a more complex function,
 * for example one that looks up foo -> getFoo() -> get("foo").
 *
 * TODO: This should not be in compiler.
 */
os.identifierResolver_ = function(data, name) {
  return data.hasOwnProperty(name) ? data[name] : ('get' in data ? data.get(name) : null);
};

/**
 * Sets the Identifier resolver function. This is global, and must be done
 * before any compilation of templates takes place.
 *
 * TODO: This should possibly not be in compiler?
 */
os.setIdentifierResolver = function(resolver) {
  os.identifierResolver_ = resolver;
};

/**
 * Gets a named property from a JsEvalContext (by checking data_ and vars_) or
 * from a simple JSON object by looking at properties. The IdentifierResolver
 * function is used in either case.
 *
 * TODO: This should not be in compiler.
 *
 * @param {JsEvalContext|Object} context Context to get property from
 * @param {String} name Name of the property
 * @return {Object|String}
 */
os.getFromContext = function(context, name, opt_default) {
  if (!context) {
    return opt_default;
  }
  var ret;
  // Check if this is a context object.
  if (context.vars_ && context.data_) {
    // Is the context payload a DOM node?
    if (context.data_.nodeType == DOM_ELEMENT_NODE) {
      ret = os.getValueFromNode_(context.data_, name);
      if (ret == null) {        
        // Set to undefined
        ret = void(0);
      }
    } else {
      ret = os.identifierResolver_(context.data_, name);
    }
    if (typeof(ret) == "undefined") {
      ret = os.identifierResolver_(context.vars_, name);
    }
    if (typeof(ret) == "undefined" && context.vars_[os.VAR_my]) {
      ret = os.getValueFromNode_(context.vars_[os.VAR_my], name);
    }
    if (typeof(ret) == "undefined" && context.vars_[VAR_top]) {
      ret = context.vars_[VAR_top][name];
    }
  } else if (context.nodeType == DOM_ELEMENT_NODE) {
    // Is the context a DOM node?
    ret = os.getValueFromNode_(context, name);
  } else {
    ret = os.identifierResolver_(context, name);
  }
  if (typeof(ret) == "undefined" || ret == null) {
    if (typeof(opt_default) != "undefined") {
      ret = opt_default;
    } else {
      ret = "";
    }
  } else if (opt_default && os.isArray(opt_default) && !os.isArray(ret) && 
      ret.list && os.isArray(ret.list)) {
    // If we were trying to get an array, but got a JSON object with an
    // array property "list", return that instead.
    ret = ret.list;    
  }
  return ret;
};

/**
 * Prepares an expression for JS evaluation.
 * @param {string} expr The expression snippet to parse.
 * @param {string} opt_default An optional default value reference (such as the
 * literal string 'null').
 */
os.transformExpression_ = function(expr, opt_default) {
  expr = os.remapOperators_(expr);
  expr = os.transformVariables_(expr);
  if (os.identifierResolver_) {
    expr = os.wrapIdentifiersInExpression(expr, opt_default);
  }
  return expr;
};

/**
 * A Map of special attribute names to change while copying attributes during
 * compilation. The key is OST-spec attribute, while the value is JST attribute
 * used to implement that feature.
 */
os.attributeMap_ = {
  'if': ATT_display,
  'repeat': ATT_select,
  'cur': ATT_innerselect
};

/**
 * Appends a JSTemplate attribute value while maintaining previous values.
 */
os.appendJSTAttribute_ = function(node, attrName, value) {
  var previousValue = node.getAttribute(attrName);
  if (previousValue) {
    value = previousValue + ";" + value;
  }
  node.setAttribute(attrName, value);
};

/**
 * Copies attributes from one node (xml or html) to another (html),.
 * Special OpenSocial attributes are substituted for their JStemplate
 * counterparts.
 * @param {Element} from An XML or HTML node to copy attributes from.
 * @param {Element} to An HTML node to copy attributes to.
 * @param {String} opt_customTag The name of the custom tag, being processed if
 * any.
 *
 * TODO(levik): On IE, some properties/attributes might be case sensitive when
 * set through script (such as "colSpan") - since they're not case sensitive
 * when defined in HTML, we need to support this type of use.
 */
os.copyAttributes_ = function(from, to, opt_customTag) {

  var dynamicAttributes = null;

  for (var i = 0; i < from.attributes.length; i++) {
    var name = from.attributes[i].nodeName;
    var value = from.getAttribute(name);
    if (name && value) {
      if (name == 'var') {
        os.appendJSTAttribute_(to, ATT_vars, from.getAttribute(name) +
            ': $this');
      } else if (name == 'context') {
        os.appendJSTAttribute_(to, ATT_vars, from.getAttribute(name) +
            ': ' + VAR_loop);
      } else if (name.length < 7 || name.substring(0, 6) != 'xmlns:') {
        if (os.customAttributes_[name]) {
          os.appendJSTAttribute_(to, ATT_eval, "os.doAttribute(this, '" + name +
              "', $this, $context)");
        } else if (name == 'repeat') {
          os.appendJSTAttribute_(to, ATT_eval,
              "os.setContextNode_($this, $context)");
        }
        var outName = os.attributeMap_.hasOwnProperty(name) ? 
            os.attributeMap_[name] : name;
        var substitution =
            (os.attributeMap_[name]) ?
            null : os.parseAttribute_(value);

        if (substitution) {
          if (outName == 'class') {
            // Dynamically setting the @class attribute gets ignored by the
            // browser. We need to set the .className property instead.
            outName = '.className';
          } else if (outName == 'style') {
            // Similarly, on IE, setting the @style attribute has no effect.
            // The cssText property of the style object must be set instead.
            outName = '.style.cssText';
          } else if (to.getAttribute(os.ATT_customtag)) {
            // For custom tags, it is more useful to put values into properties
            // where they can be accessed as objects, rather than placing them
            // into attributes where they need to be serialized.
            outName = '.' + outName;
          } else if (os.isIe && !os.customAttributes_[outName] &&
              outName.substring(0, 2).toLowerCase() == 'on') {
            // For event handlers on IE, setAttribute doesn't work, so we need
            // to create a function to set as a property.
            outName = '.' + outName;
            substitution = 'new Function(' + substitution + ')';
          } else if (outName == 'selected' && to.tagName == 'OPTION') {
            // For the @selected attribute of an option, set the property 
            // instead to allow false values to not mark the option selected.
            outName = '.selected'
          }

          // TODO: reuse static array (IE6 perf).
          if (!dynamicAttributes) {
            dynamicAttributes = [];
          }
          dynamicAttributes.push(outName + ':' + substitution);
        } else {
          // For special attributes, do variable transformation.
          if (os.attributeMap_.hasOwnProperty(name)) {
            // If the attribute value looks like "${expr}", just use the "expr".
            if (value.length > 3 &&
              value.substring(0, 2) == '${' &&
              value.charAt(value.length - 1) == '}') {
              value = value.substring(2, value.length - 1);
            }
            // In special attributes, default value is empty array for repeats,
            // null for others
            value = os.transformExpression_(value, 
                name == 'repeat' ? os.VAR_emptyArray : 'null');
          } else if (outName == 'class') {
            // In IE, we must set className instead of class.
            to.setAttribute('className', value);
          } else if (outName == 'style') {
            // Similarly, on IE, setting the @style attribute has no effect.
            // The cssText property of the style object must be set instead.
            to.style.cssText = value;
          }
          if (os.isIe && !os.customAttributes_.hasOwnProperty(outName) &&
              outName.substring(0, 2).toLowerCase() == 'on') {
            // In IE, setAttribute doesn't create event handlers, so we must
            // use attachEvent in order to create handlers that are preserved
            // by calls to cloneNode().
            to.attachEvent(outName, new Function(value));
          } else {
            to.setAttribute(outName, value);
          }
        }
      }
    }
  }

  if (dynamicAttributes) {
    os.appendJSTAttribute_(to, ATT_values, dynamicAttributes.join(';'));
  }
};

/**
 * Recursively compiles an individual node from XML to DOM (for JSTemplate)
 * Special os.* tags and tags for which custom functions are defined
 * are converted into markup recognizable by JSTemplate.
 *
 * TODO: process text nodes and attributes  with ${} notation here
 */
os.compileNode_ = function(node) {
  if (node.nodeType == DOM_TEXT_NODE) {
    var textNode = node.cloneNode(false);
    return os.breakTextNode_(textNode);
  } else if (node.nodeType == DOM_ELEMENT_NODE) {
    var output;
    if (node.tagName.indexOf(":") > 0) {
      if (node.tagName == "os:Repeat") {
        output = document.createElement(os.computeContainerTag_(node));
        output.setAttribute(ATT_select, os.parseAttribute_(node.getAttribute("expression")));
        var varAttr = node.getAttribute("var");
        if (varAttr) {
          os.appendJSTAttribute_(output, ATT_vars, varAttr + ': $this');
        }
        var contextAttr = node.getAttribute("context");
        if (contextAttr) {
          os.appendJSTAttribute_(output, ATT_vars, contextAttr + ': ' + VAR_loop);
        }
        os.appendJSTAttribute_(output, ATT_eval, "os.setContextNode_($this, $context)");
      } else if (node.tagName == "os:If") {
        output = document.createElement(os.computeContainerTag_(node));
        output.setAttribute(ATT_display, os.parseAttribute_(node.getAttribute("condition")));
      } else {
        output = document.createElement("span");
        output.setAttribute(os.ATT_customtag, node.tagName);

        var custom = node.tagName.split(":");
        os.appendJSTAttribute_(output, ATT_eval, "os.doTag(this, \""
            + custom[0] + "\", \"" + custom[1] + "\", $this, $context)");
        var context = node.getAttribute("cur") || "{}";
        output.setAttribute(ATT_innerselect, context);

        // For os:Render, create a parent node reference.
        // TODO: remove legacy support
        if (node.tagName == "os:render" || node.tagName == "os:Render" ||
            node.tagName == "os:renderAll" || node.tagName == "os:RenderAll") {
          os.appendJSTAttribute_(output, ATT_values, os.VAR_parentnode + ":" +
              os.VAR_node);
        }

        os.copyAttributes_(node, output, node.tagName);
      }
    } else {
      output = os.xmlToHtml_(node);
    }
    if (output && !os.processTextContent_(node, output)) {
      for (var child = node.firstChild; child; child = child.nextSibling) {
        var compiledChild = os.compileNode_(child);
        if (compiledChild) {
          if (os.isArray(compiledChild)) {
            for (var i = 0; i < compiledChild.length; i++) {
              output.appendChild(compiledChild[i]);
            }
          } else {
            // If inserting a TR into a TABLE, inject a TBODY element.
            if (compiledChild.tagName == 'TR' && output.tagName == 'TABLE') {
              var lastEl = output.lastChild;
              while (lastEl && lastEl.nodeType != DOM_ELEMENT_NODE &&
                  lastEl.previousSibling) {
                lastEl = lastEl.previousSibling;
              }
              if (!lastEl || lastEl.tagName != 'TBODY') {
                lastEl = document.createElement('tbody');
                output.appendChild(lastEl);
              }
              lastEl.appendChild(compiledChild);
            } else {
              output.appendChild(compiledChild);
            }
          }
        }
      }
    }
    return output;
  }
  return null;
};

/**
 * Calculates the type of element best suited to encapsulating contents of a 
 * <os:Repeat> or <os:If> tags. Inspects the element's children to see if one 
 * of the special cases should be used.
 * "optgroup" for <option>s
 * "tbody" for <tr>s
 * "span" otherwise
 * @param {Element} element The repeater/conditional element
 * @return {stirng} Name of the node ot represent this repeater.
 */
os.computeContainerTag_ = function(element) {
  var child = element.firstChild;
  if (child) {
    while (child && !child.tagName) {
      child = child.nextSibling;
    }
    if (child) {
      var tag = child.tagName.toLowerCase();
      if (tag == "option") {
        return "optgroup";
      }
      if (tag == "tr") {
        return "tbody";
      }
    }
  }
  return "span";
};

/**
 * XHTML Entities we need to support in XML, defined in DOCTYPE format.
 *
 * TODO(levik): A better way to do this.
 */
os.ENTITIES = "<!ENTITY nbsp \"&#160;\">";

/**
 * Creates an HTML node that's a shallow copy of an XML node
 * (includes attributes).
 */
os.xmlToHtml_ = function(xmlNode) {
  var htmlNode = document.createElement(xmlNode.tagName);
  os.copyAttributes_(xmlNode, htmlNode);
  return htmlNode;
};

/**
 * Fires callbacks on a context object
 */
os.fireCallbacks = function(context) {
  var callbacks = context.getVariable(os.VAR_callbacks);
  while (callbacks.length > 0) {
    var callback = callbacks.pop();
    if (callback.onAttach) {
      callback.onAttach();
    // TODO(levik): Remove no-context handlers?
    } else if (typeof(callback) == "function") {
      callback.apply({});
    }
  }
};

/**
 * Checks for and processes an optimized case where a node only has text content
 * In this instance, any variable substitutions happen without creating
 * intermediary spans.
 *
 * This will work when node content looks like:
 *   - "Plain text"
 *   - "${var}"
 *   - "Plain text with ${var} inside"
 * But not when it is
 *   - "Text <b>With HTML content</b> (with or without a ${var})
 *   - Custom tags are also exempt from this optimization.
 *
 * @return {boolean} true if node only had text data and needs no further
 * processing, false otherwise.
 */
os.processTextContent_ = function(fromNode, toNode) {
  if (fromNode.childNodes.length == 1 &&
      !toNode.getAttribute(os.ATT_customtag) &&
      fromNode.firstChild.nodeType == DOM_TEXT_NODE) {
    var substitution = os.parseAttribute_(fromNode.firstChild.data);
    if (substitution) {
      toNode.setAttribute(ATT_content, substitution);
    } else {
      toNode.appendChild(document.createTextNode(
          os.trimWhitespaceForIE_(fromNode.firstChild.data, true, true)));
    }
    return true;
  }
  return false;
};

/**
 * Create a textNode out of a string, if non-empty, then puts into an array.
 * @param {string} text A string to be created as a text node.
 */
os.pushTextNode = function(array, text) {
  if (text.length > 0) {
    array.push(document.createTextNode(text));
  }
};

/**
 * Removes extra whitespace and newline characters for IE - to be used for
 * transforming strings that are destined for textNode content.
 * @param {string} string The string to trim spaces from.
 * @param {boolean} opt_trimStart Trim the start of the string.
 * @param {boolean} opt_trimEnd Trim the end of the string.
 * @return {string} The string with extra spaces removed on IE, original
 * string on other browsers.
 */
os.trimWhitespaceForIE_ = function(string, opt_trimStart, opt_trimEnd) {
  if (os.isIe) {
    // Replace newlines with spaces, then multiple spaces with single ones.
    // Then remove leading and trailing spaces.
    var ret = string.replace(/[\x09-\x0d ]+/g, ' ');
    if (opt_trimStart) {
      ret = ret.replace(/^\s/, '');
    }
    if (opt_trimEnd) {
      ret = ret.replace(/\s$/, '');
    }
    return ret;
  }
  return string;
};

/**
 * Breaks up a text node with special ${var} markup into a series of text nodes
 * and spans with appropriate jscontent attribute.
 *
 * @return {Array.<Node>} An array of textNodes and Span Elements if variable
 * substitutions were found, or an empty array if none were.
 */
os.breakTextNode_ = function(textNode) {
  var substRex = os.regExps_.VARIABLE_SUBSTITUTION;
  var text = textNode.data;
  var nodes = [];
  var match = text.match(substRex);
  while (match) {
    if (match[1].length > 0) {
      os.pushTextNode(nodes, os.trimWhitespaceForIE_(match[1]));
    }
    var token = match[2].substring(2, match[2].length - 1);
    if (!token) {
      token = VAR_this;
    }
    var tokenSpan = document.createElement("span");
    tokenSpan.setAttribute(ATT_content, os.transformExpression_(token));
    nodes.push(tokenSpan);
    match = text.match(substRex);
    text = match[3];
    match = text.match(substRex);
  }
  if (text.length > 0) {
    os.pushTextNode(nodes, os.trimWhitespaceForIE_(text));
  }
  return nodes;
};

/**
 * Transforms a literal string for inclusion into a variable evaluation 
 * (a JS string):
 *   - Escapes single quotes.
 *   - Replaces newlines with spaces.
 *   - Substitutes variable references for literal semicolons.
 *   - Addes single quotes around the string.
 */
os.transformLiteral_ = function(string) {
  return "'" + string.replace(/'/g, "\\'").
      replace(/\n/g, " ").replace(/;/g, "'+os.SEMICOLON+'") + "'";
};

/**
 * Parses an attribute value into a JS expression. "Hello, ${user}!" becomes
 * "Hello, " + user + "!".
 *
 * @param {string} value Attribute value to parse
 * TODO: Rename to parseExpression()
 */
os.parseAttribute_ = function(value) {
  if (!value.length) {
    return null;
  }
  var substRex = os.regExps_.VARIABLE_SUBSTITUTION;
  var text = value;
  var parts = [];
  var match = text.match(substRex);
  if (!match) {
    return null;
  }
  while (match) {
    if (match[1].length > 0) {
      parts.push(os.transformLiteral_(
          os.trimWhitespaceForIE_(match[1], parts.length == 0)));
    }
    var expr = match[2].substring(2, match[2].length - 1);
    if (!expr) {
      expr = VAR_this;
    }
    parts.push('(' + 
        os.transformExpression_(expr) + ')');
    text = match[3];
    match = text.match(substRex);
  }
  if (text.length > 0) {
    parts.push(os.transformLiteral_(
        os.trimWhitespaceForIE_(text, false, true)));
  }
  return parts.join('+');
};

/**
 * Returns a named value of a given node. First looks for a property, then 
 * attribute, then a child node (or nodes). If multiple child nodes are found,
 * they will be returned in an array. If we find a single Node that is an 
 * Element, it's children will be returned in an array.
 * @param {Element} node The DOM node to inspect
 * @param {string} name The name of the property/attribute/child node(s) to get.
 * The special value "*" means return all child Nodes
 * @return {string|Element|Object|Array.<Element>} The value as a String,
 * Object, Element or array of Elements.
 */
os.getValueFromNode_ = function(node, name) {

  if (name == "*") {
    var children = [];
    for (var child = node.firstChild; child; child = child.nextSibling) {
      children.push(child);
    }
    return children;
  }
  
  // Since namespaces are not supported, strip off prefix.
  if (name.indexOf(':') >= 0) {
    name = name.substring(name.indexOf(':') + 1);
  }
  
  var ret = node[name];
  if (typeof(ret) == "undefined" || ret == null) {
    ret = node.getAttribute(name);
  }
  
  if (typeof(ret) != "undefined" && ret != null) {
    // Process special cases where ret would be wrongly evaluated as "true"
    if (ret == "false") {
      ret = false;
    } else if (ret == "0") {
      ret = 0;
    }
    return ret;
  }

  var myMap = node[os.VAR_my];
  if (!myMap) {
    myMap = os.computeChildMap_(node);
    node[os.VAR_my] = myMap;
  }
  ret = myMap[name.toLowerCase()];
  return ret;
};

//------------------------------------------------------------------------------
// The functions below are for parsing JS expressions to wrap identifiers.
// They should be move into a separate file/js-namespace.
//------------------------------------------------------------------------------

/**
 * A map of identifiers that should not be wrapped
 * (such as JS built-ins and special method names).
 */
os.identifiersNotToWrap_ = {};
os.identifiersNotToWrap_['true'] = true;
os.identifiersNotToWrap_['false'] = true;
os.identifiersNotToWrap_['null'] = true;
os.identifiersNotToWrap_['var'] = true;
os.identifiersNotToWrap_[os.VAR_my] = true;
os.identifiersNotToWrap_[VAR_this] = true;
os.identifiersNotToWrap_[VAR_context] = true;
os.identifiersNotToWrap_[VAR_top] = true;
os.identifiersNotToWrap_[VAR_loop] = true;

/**
 * Checks if a character can begin a legal JS identifier name.
 * @param {string} ch Character to check.
 * @return {boolean} This character can start an identifier.
 */
os.canStartIdentifier= function(ch) {
  return (ch >= 'a' && ch <= 'z') ||
      (ch >= 'A' && ch <= 'Z') ||
      ch == '_' || ch == '$';
};

/**
 * Checks if a character can be contained in a legal identifier name.
 * (A legal identifier in Templates can contain any character a legal 
 * JS identifier can plus the colon - to support ${My.os:Foo})
 * @param {string} ch Character to check.
 * @return {string} This is a valid identifier character.
 */
os.canBeInIdentifier = function(ch) {
  return os.canStartIdentifier(ch) || (ch >= '0' && ch <= '9') ||
      // The colon char cannot be in a real JS identifier, but we allow it,
      // so that namespaced tag names are treated as whole identifiers.
      ch == ':';
};

/**
 * Checks if a character can be contained in a expression token.
 * @param {string} ch Character to check.
 * @return {string} This is a valid token character.
 */
os.canBeInToken = function(ch) {
  return os.canBeInIdentifier(ch) || ch == '(' || ch == ')' ||
      ch == '[' || ch == ']' || ch == '.';
};

/**
 * Wraps an identifier for Identifier Resolution with respect to the context.
 * os.VAR_idenfitierresolver ("$_ir") is used as the function name.
 * So, "foo.bar" becomes "$_ir($_ir($context, 'foo'), 'bar')"
 * @param {string} iden A string representing an identifier.
 * @param {string} opt_context A string expression to use for context.
 * @param {string} opt_default An optional default value reference (such as the
 * literal string 'null').
 */
os.wrapSingleIdentifier = function(iden, opt_context, opt_default) {
  if (os.identifiersNotToWrap_.hasOwnProperty(iden) && 
      (!opt_context || opt_context == VAR_context)) {
    return iden;
  }
  return os.VAR_identifierresolver + '(' +
      (opt_context || VAR_context) + ', \'' + iden + '\'' +
      (opt_default ? ', ' + opt_default : '') +
      ')';
};

/**
 * Wraps identifiers in a single token of JS.
 */
os.wrapIdentifiersInToken = function(token, opt_default) {
  if (!os.canStartIdentifier(token.charAt(0))) {
    return token;
  }

  // If the identifier is accessing a message
  // (and gadget messages are obtainable), inline it here.
  // TODO: This is inefficient for times when the message contains no markup -
  // such cases should be optimized.
  if (token.substring(0, os.VAR_msg.length + 1) == (os.VAR_msg + '.') &&
      os.gadgetPrefs_) {
    var key = token.split(".")[1];
    var msg = os.getPrefMessage(key) || '';
    return os.parseAttribute_(msg) || os.transformLiteral_(msg);
  }

  var identifiers = os.tokenToIdentifiers(token);
  var parts = false;
  var buffer = [];
  var output = null;
  for (var i = 0; i < identifiers.length; i++) {
    var iden = identifiers[i];
    parts = os.breakUpParens(iden);
    if (!parts) {
      if (i == identifiers.length - 1) {
        output = os.wrapSingleIdentifier(iden, output, opt_default);
      } else {
        output = os.wrapSingleIdentifier(iden, output);
      }
    } else {
      buffer.length = 0;
      buffer.push(os.wrapSingleIdentifier(parts[0], output));
      for (var j = 1; j < parts.length; j+= 3) {
        buffer.push(parts[j]);
        if (parts[j + 1]) {
          buffer.push(os.wrapIdentifiersInExpression(parts[j + 1]));
        }
        buffer.push(parts[j + 2]);
      }
      output = buffer.join('');
    }
  }
 return output;
};

/**
 * Wraps all identifiers in a JS expression. The expression is tokenized, then
 * each token is wrapped individually.
 * @param {string} expr The expression to wrap.
 * @param {string} opt_default An optional default value reference (such as the
 * literal string 'null').
 */
os.wrapIdentifiersInExpression = function(expr, opt_default) {
  var out = [];
  var tokens = os.expressionToTokens(expr);
  for (var i = 0; i < tokens.length; i++) {
    out.push(os.wrapIdentifiersInToken(tokens[i], opt_default));
  }
  return out.join('');
};

/**
 * Tokenizes a JS expression. Each token is either an operator, a literal
 * string, an identifier, or a function call.
 * For example,
 *   "foo||bar" is tokenized as ["foo", "||", "bar"], but
 *   "bing(foo||bar)" becomes   ["bing(foo||bar)"].
 */
os.expressionToTokens = function(expr) {
  var tokens = [];
  var inquotes = false;
  var inidentifier = false;
  var inparens = 0;
  var escaped = false;
  var quotestart = null;
  var buffer = [];
  for (var i = 0; i < expr.length; i++) {
    var ch = expr.charAt(i);
    if (inquotes) {
      if (!escaped && ch == quotestart) {
        inquotes = false;
      } else if (ch == '\\') {
        escaped = true;
      } else {
        escaped = false;
      }
      buffer.push(ch);
    } else {
      if (ch == "'" || ch == '"') {
        inquotes = true;
        quotestart = ch;
        buffer.push(ch);
        continue;
      }
      if (ch == '(') {
        inparens++;
      } else if (ch == ')' && inparens > 0) {
        inparens--;
      }
      if (inparens > 0) {
        buffer.push(ch);
        continue;
      }
      if (!inidentifier && os.canStartIdentifier(ch)) {
        if (buffer.length > 0) {
          tokens.push(buffer.join(''));
          buffer.length = 0;
        }
        inidentifier = true;
        buffer.push(ch);
        continue;
      }
      if (inidentifier) {
        if (os.canBeInToken(ch)) {
          buffer.push(ch);
        } else {
          tokens.push(buffer.join(''));
          buffer.length = 0;
          inidentifier = false;
          buffer.push(ch);
        }
      } else {
        buffer.push(ch);
      }
    }
  }
  tokens.push(buffer.join(''));
  return tokens;
};

/**
 * Breaks up a JS token into identifiers, separated by '.'
 * "foo.bar" becomes ["foo", "bar"].
 */
os.tokenToIdentifiers = function(token) {
  var inquotes = false;
  var quotestart = null;
  var escaped = false;
  var buffer = [];
  var identifiers = [];
  for (var i = 0; i < token.length; i++) {
    var ch = token.charAt(i);
    if (inquotes) {
      if (!escaped && ch == quotestart) {
        inquotes = false;
      } else if (ch == '\\') {
        escaped = true;
      } else {
        escaped = false;
      }
      buffer.push(ch);
      continue;
    } else {
      if (ch == "'" || ch == '"') {
        buffer.push(ch);
        inquotes = true;
        quotestart = ch;
        continue;
      }
    }
    if (ch == '.' && !inquotes) {
      identifiers.push(buffer.join(''));
      buffer.length = 0;
      continue;
    }
    buffer.push(ch);
  }
  identifiers.push(buffer.join(''));
  return identifiers;
};


/**
 * Checks if a JS identifier has parenthesis and bracket parts. If no such
 * parts are found, return false. Otherwise, the expression is returned as
 * an array of components:
 *   "foo(bar)"       -> ["foo", "(", "bar", ")"]
 *   "foo[bar](baz)"  -> ["foo", "[", "bar", "]", "(", "baz", ")"]
 */
os.breakUpParens = function(identifier) {
  var parenIndex = identifier.indexOf('(');
  var bracketIndex = identifier.indexOf('[');
  if (parenIndex < 0 && bracketIndex < 0) {
    return false;
  }
  var parts = [];
  if (parenIndex < 0 || (bracketIndex >= 0 && bracketIndex < parenIndex)) {
    parenIndex = 0;
    parts.push(identifier.substring(0, bracketIndex));
  } else {
    bracketIndex = 0;
    parts.push(identifier.substring(0, parenIndex));
  }
  var parenstart = null;
  var inquotes = false;
  var quotestart = null;
  var parenlevel = 0;
  var escaped = false;
  var buffer = [];
  for (var i = bracketIndex + parenIndex; i < identifier.length; i++) {
    var ch = identifier.charAt(i);
    if (inquotes) {
      if (!escaped && ch == quotestart) {
        inquotes = false;
      } else if (ch == '\\') {
        escaped = true;
      } else {
        escaped = false;
      }
      buffer.push(ch);
    } else {
      if (ch == "'" || ch == '"') {
        inquotes = true;
        quotestart = ch;
        buffer.push(ch);
        continue;
      }
      if (parenlevel == 0) {
        if (ch == '(' || ch == '[') {
          parenstart = ch;
          parenlevel++;
          parts.push(ch);
          buffer.length = 0;
        }
      } else {
        if ((parenstart == '(' && ch == ')') ||
          (parenstart == '[' && ch == ']')) {
          parenlevel--;
          if (parenlevel == 0) {
            parts.push(buffer.join(''));
            parts.push(ch);
          } else {
            buffer.push(ch);
          }
        } else {
          if (ch == parenstart) {
            parenlevel++;
          }
          buffer.push(ch);
        }
      }
    }
  }
  return parts;
};
