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
 * @fileoverview OpenSocial Template loader. Can be used to load template
 * libraries via URL. Supports Javascript and CSS injection.
 *
 * Usage:
 *   os.Loader.loadUrl("/path/templatelib.xml", function() { doSomething(); });
 *
 * or
 *   os.Loader.loadContent(
 *       "<Templates><Template tag="foo:bar">...</Template></Templates>");
 *
 * The Template Library should have the following structure:
 *
 *   <Templates xmlns:foo="http://foo.com/">
 *     <Namspace prefix="foo" url="http://foo.com/"/>
 *     <Template tag="foo:bar">[Template Markup Here]</Template>
 *     <Style>[CSS for all templates]</Style>
 *     <JavaScript>
 *       function usedByAllTemplates() { ... };
 *     </JavaScript>
 *     <TemplateDef tag="foo:baz">
 *       <Template>[Markup for foo:baz]</Template>
 *       <Style>[CSS for foo:baz]</Style>
 *       <JavaScript>
 *         function usedByFooBaz() { ... };
 *       </JavaScript>
 *     </TemplateDef>
 *   </Templates>
 *
 * TODO(levik): Implement dependency support - inject JS and CSS lazily.
 * TODO(levik): More error handling and reporting of ill-formed XML files.
 */

os.Loader = {};

/**
 * A map of URLs which were already loaded.
 */
os.Loader.loadedUrls_ = {};

/**
 * Load a remote URL via XMLHttpRequest or gadgets.io.makeRequest API
 *     when in context of a gadget.
 * @param {string} url The URL that is to be fetched.
 * @param {Function} callback Function to call once loaded.
 */
os.Loader.loadUrl = function(url, callback) {
  if (typeof(window['gadgets']) != "undefined") {
    os.Loader.requestUrlGadgets_(url, callback);
  } else {
    os.Loader.requestUrlXHR_(url, callback);
  }
};

/**
 * Loads a Template Library from a URL via XMLHttpRequest. Once the library is
 * loaded, the callback function is called. A map is kept to prevent loading
 * the same URL twice.
 * @param {string} url The URL of the Template Library.
 * @param {Function} callback Function to call once loaded.
 */
os.Loader.requestUrlXHR_ = function(url, callback) {
  if (os.Loader.loadedUrls_[url]) {
    window.setTimeout(callback, 0);
    return;
  }
  var req = null;
  if (typeof(XMLHttpRequest) != "undefined") {
    req = new XMLHttpRequest();
  } else {
    req = new ActiveXObject("MSXML2.XMLHTTP");
  }
  req.open("GET", url, true);
  req.onreadystatechange = function() {
    if (req.readyState == 4) {
      os.Loader.loadContent(req.responseText, url);
      callback();
    }
  };
  req.send(null);
};

/**
 * Fetch content remotely using the gadgets.io.makeRequest API.
 * @param {string} url The URL where the content is located.
 * @param {Function} callback Function to call with the data from the URL
 *     once it is fetched.
 */
os.Loader.requestUrlGadgets_ = function(url, callback) {
  var params = {};
  var gadgets = window['gadgets'];

  if (os.Loader.loadedUrls_[url]) {
    window.setTimeout(callback, 0);
    return;
  }
  params[gadgets.io.RequestParameters.CONTENT_TYPE] =
      gadgets.io.ContentType.TEXT;
  gadgets.io.makeRequest(url, function(obj) {
    os.Loader.loadContent(obj.data, url);
    callback();
  }, params);
};

/**
 * Loads a number of Template libraries, specified by an array of URLs. Once
 * all the libraries have been loaded, the callback is called.
 * @param {Array.<string>} urls An array of URLs of Template Libraries to load.
 * @param {Function} callback Function to call once all libraries are loaded.
 */
os.Loader.loadUrls = function(urls, callback) {
  var loadOne = function() {
    if (urls.length == 0) {
      callback();
    } else {
      os.Loader.loadUrl(urls.pop(), loadOne);
    }
  };
  loadOne();
};

/**
 * Processes the XML markup of a Template Library.
 */
os.Loader.loadContent = function(xmlString, url) {
  var doc = opensocial.xmlutil.parseXML(xmlString);
  var templatesNode = doc.firstChild;
  os.Loader.processTemplatesNode(templatesNode);
  os.Loader.loadedUrls_[url] = true;
};

/**
 * Gets the function that should be used for processing a tag.
 * @param {string} tagName Name of the tag.
 * @return {Function|null} The function for processing such tags.
 */
os.Loader.getProcessorFunction_ = function(tagName) {
  // TODO(levik): This won't work once compiler does name mangling.
  return os.Loader['process' + tagName + 'Node'] || null;
};

/**
 * Processes the <Templates> node.
 */
os.Loader.processTemplatesNode = function(node) {
  for (var child = node.firstChild; child; child = child.nextSibling) {
    if (child.nodeType == DOM_ELEMENT_NODE) {
      var handler = os.Loader.getProcessorFunction_(child.tagName);
      if (handler) {
        handler(child);
      }
    }
  }
};

/**
 * Processes the <Namespace> node.
 */
os.Loader.processNamespaceNode = function(node) {
  var prefix = node.getAttribute("prefix");
  var url = node.getAttribute("url");
  os.createNamespace(prefix, url);
};

/**
 * Processes the <TemplateDef> node
 */
os.Loader.processTemplateDefNode = function(node) {
  var tag = node.getAttribute("tag");
  var name = node.getAttribute("name");
  for (var child = node.firstChild; child; child = child.nextSibling) {
    if (child.nodeType == DOM_ELEMENT_NODE) {
      // TODO(levik): This won't work once compiler does name mangling.
      var handler = os.Loader.getProcessorFunction_(child.tagName);
      if (handler) {
        handler(child, tag, name);
      }
    }
  }
};

/**
 * Processes the <Template> node
 */
os.Loader.processTemplateNode = function(node, opt_tag, opt_name) {
  var tag = opt_tag || node.getAttribute("tag");
  var name = opt_name || node.getAttribute("name");
  if (tag) {
    var tagParts = tag.split(":");
    if (tagParts.length != 2) {
      throw Error("Invalid tag name: " + tag);
    }
    var nsObj = os.getNamespace(tagParts[0]);
    if (!nsObj) {
      throw Error("Namespace not registered: " + tagParts[0] +
          " while trying to define " + tag);
    }
    var template = os.compileXMLNode(node);
    nsObj[tagParts[1]] = os.createTemplateCustomTag(template);
  } else if (name) {
    var template = os.compileXMLNode(node);
    template.id = name;
    os.registerTemplate(template);
  }
};

/**
 * Processes the <JavaScript> node
 */
os.Loader.processJavaScriptNode = function(node, opt_tag) {
  for (var contentNode = node.firstChild; contentNode;
      contentNode = contentNode.nextSibling) {
    // TODO(levik): Skip empty text nodes (with whitespace and newlines)
    os.Loader.injectJavaScript(contentNode.nodeValue);
  }
};

/**
 * Processes the <Style> node
 */
os.Loader.processStyleNode = function(node, opt_tag) {
  for (var contentNode = node.firstChild; contentNode;
      contentNode = contentNode.nextSibling) {
    // TODO(levik): Skip empty text nodes (with whitespace and newlines)
    os.Loader.injectStyle(contentNode.nodeValue);
  }
};

/**
 * @type {Element} DOM node used for dynamic injection of JavaScript.
 * @private
 * TODO(davidbyttow): Only retrieve this once if JavaScript injection was
 * actually requested.
 */
os.Loader.headNode_ = document.getElementsByTagName('head')[0] ||
    document.getElementsByTagName('*')[0];

/**
 * Injects and evaluates JavaScript code synchronously in the global scope.
 */
os.Loader.injectJavaScript = function(jsCode) {
  var scriptNode = document.createElement('script');
  scriptNode.type = 'text/javascript';
  scriptNode.text = jsCode;
  os.Loader.headNode_.appendChild(scriptNode);
};

/**
 * Injects CSS Style code into the page.
 */
os.Loader.injectStyle = function(cssCode) {
  var sheet;
  if (document.styleSheets.length == 0) {
    document.getElementsByTagName("head")[0].appendChild(
        document.createElement("style"));
  }
  sheet = document.styleSheets[0];
  var rules = cssCode.split("}");
  for (var i = 0; i < rules.length; i++) {
    var rule = rules[i].replace(/\n/g, "").replace(/\s+/g, " ");
    if (rule.length > 2) {
      if (sheet.insertRule) {
        rule = rule + "}";
        sheet.insertRule(rule, sheet.cssRules.length);
      } else {
        var ruleParts = rule.split("{");
        sheet.addRule(ruleParts[0], ruleParts[1]);
      }
    }
  }
};
