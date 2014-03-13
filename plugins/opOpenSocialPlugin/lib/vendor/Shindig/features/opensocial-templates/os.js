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
 * @fileoverview Implements os:Render tag and OpenSocial-specific
 * identifier resolver.
 */

/**
 * Define 'os:Render' and 'os:Html' tags and the @onAttach attribute
 */
os.defineBuiltinTags = function() {
  var osn = os.getNamespace("os") ||
      os.createNamespace("os", "http://ns.opensocial.org/2008/markup");

  /**
   * <os:Render> custom tag renders the specified child nodes of the current
   * context.
   */
  osn.Render = function(node, data, context) {
    var parent = context.getVariable(os.VAR_parentnode);
    var exp = node.getAttribute("content") || "*";
    var result = os.getValueFromNode_(parent, exp);
    if (!result) {
       return "";
    } else if (typeof(result) == "string") {
      var textNode = document.createTextNode(result);
      result = [];
      result.push(textNode);
    } else if (!os.isArray(result)) {
      var resultArray = [];
      for (var i = 0; i < result.childNodes.length; i++) {
        resultArray.push(result.childNodes[i]);
      }
      result = resultArray;
    } else if (exp != "*" && result.length == 1 &&
        result[0].nodeType == DOM_ELEMENT_NODE) {
      // When we call <os:renderAll content="tag"/>, render the inner content
      // of the tag returned, not the tag itself.
      var resultArray = [];
      for (var child = result[0].firstChild; child; child = child.nextSibling) {
        resultArray.push(child);
      }
      result = resultArray;
    }

    // Trim away leading and trailing spaces on IE, which interprets them
    // literally.
    if (os.isIe) {
      for (var i = 0; i < result.length; i++) {
        if (result[i].nodeType == DOM_TEXT_NODE) {
          var trimmed = os.trimWhitespaceForIE_(
              result[i].nodeValue, (i == 0), (i == result.length - 1));
          if (trimmed != result[i].nodeValue) {
            result[i].parentNode.removeChild(result[i]);
            result[i] = document.createTextNode(trimmed);
          }
        }
      }
    }

    return result;
  };
  // TODO: Remove legacy names.
  osn.render = osn.RenderAll = osn.renderAll = osn.Render;

  /**
   * <os:Html> custom tag renders HTML content (as opposed to HTML code), so
   * <os:Html code="<b>Hello</b>"/> would result in the bold string "Hello",
   * rather than the text of the markup.
   */
  osn.Html = function(node) {
    var html = node.code ? "" + node.code : node.getAttribute("code") || "";
    // TODO(levik): Sanitize the HTML here to avoid script injection issues.
    // Perhaps use the gadgets sanitizer if available.
    return html;
  };

  function createClosure(object, method) {
    return function() {
      method.apply(object);
    };
  }

  /**
   * Custom attribute handler for @onAttach attribute, which allows deferred
   * execution of a snippet of JS when a template is attached to the DOM.
   * This is useful for when geometry needs to be available for post-processing
   * (such as with Google Maps).
   * The code will have "this" bounde to the DOM node on which the attribute was
   * found.
   */
  function processOnAttach(node, code, data, context) {
    var callbacks = context.getVariable(os.VAR_callbacks);
    var func = new Function(code);
    callbacks.push(createClosure(node, func));
  }
  os.registerAttribute_("onAttach", processOnAttach);
  os.registerAttribute_("onCreate", processOnAttach);
  os.registerAttribute_("oncreate", processOnAttach);
  os.registerAttribute_("x-oncreate", processOnAttach);
  os.registerAttribute_("x-onCreate", processOnAttach);
};

os.defineBuiltinTags();

/**
 * Identifier Resolver function for OpenSocial objects.
 * Checks for:
 * <ul>
 *   <li>Simple property</li>
 *   <li>JavaBean-style getter</li>
 *   <li>OpenSocial Field</li>
 *   <li>Data result set</li>
 * </ul>
 * @param {Object} object The object in the scope of which to get a named
 * property.
 * @param {string} name The name of the property to get.
 * @return {Object?} The property requested.
 */
os.resolveOpenSocialIdentifier = function(object, name) {
  // Simple property from object.
  if (typeof(object[name]) != "undefined") {
    return object[name];
  }

  // JavaBean-style getter method.
  var functionName = os.getPropertyGetterName(name);
  if (object[functionName]) {
    return object[functionName]();
  }

  // Check OpenSocial field by dictionary mapping
  if (object.getField) {
    var fieldData = object.getField(name);
    if (fieldData) {
      return fieldData;
    }
  }

  // Multi-purpose get() method
  if (object.get) {
    var responseItem = object.get(name);

    // ResponseItem is a data set
    if (responseItem && responseItem.getData) {
      var data = responseItem.getData();
      // Return array payload where appropriate
      return data.array_ || data;
    }
    return responseItem;
  }

  // Return undefined value, to avoid confusing with existing value of "null".
  var und;
  return und;
};

os.setIdentifierResolver(os.resolveOpenSocialIdentifier);

/**
 * Create methods for an object based upon a field map for OpenSocial.
 * @param {Object} object Class object to have methods created for.
 * @param {Object} fields A key-value map object to retrieve fields (keys) and
 * method names (values) from.
 * @private
 */
os.createOpenSocialGetMethods_ = function(object, fields) {
  if (object && fields) {
    for (var key in fields) {
      var value = fields[key];
      var getter = os.getPropertyGetterName(value);
      object.prototype[getter] = function() {
        this.getField(key);
      };
    }
  }
};

/**
 * Automatically register JavaBean-style methods for various OpenSocial objects.
 * @private
 */
os.registerOpenSocialFields_ = function() {
  var fields = os.resolveOpenSocialIdentifier.FIELDS;
  if (opensocial) {
    // TODO: Add more OpenSocial objects.
    if (opensocial.Person) {
      //os.createOpenSocialGetMethods_(opensocial.Person,  opensocial.Person.Field);
    }
  }
};

os.registerOpenSocialFields_();
