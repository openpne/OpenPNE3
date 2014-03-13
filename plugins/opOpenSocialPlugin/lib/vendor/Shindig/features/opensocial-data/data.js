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
 * @fileoverview Implements the global implicit data context for containers.
 *
 * TODO:
 *   Variable substitution in markup.
 *   Support cross-cutting predicates (page, sort, search).
 *   URL parameter support.
 */

/**
 * @type {string} The key attribute constant.
 */

opensocial.data.ATTR_KEY = "key";

/**
 * @type {string} The type of script tags that contain data markup.
 */
opensocial.data.SCRIPT_TYPE = "text/os-data";

opensocial.data.NSMAP = {};

opensocial.data.VAR_REGEX = /^([\w\W]*?)(\$\{[^\}]*\})([\w\W]*)$/;

/**
 * A RequestDescriptor is a wrapper for an XML tag specifying a data request.
 * This object can be used to access attributes of the request - performing
 * necessary variable substitutions from the global DataContext. An instance of
 * this object will be passed to the Data Request Handler so it can obtain its
 * parameters through it.
 * @constructor
 * @param {Element} xmlNode An XML DOM node representing the request.
 */
opensocial.data.RequestDescriptor = function(xmlNode) {
  this.tagName = xmlNode.tagName;
  this.tagParts = this.tagName.split(":");
  this.attributes = {};

  // Flag to indicate that this request depends on other requests.
  this.dependencies = false;

  for (var i = 0; i < xmlNode.attributes.length; ++i) {
    var name = xmlNode.attributes[i].nodeName;
    if (name) {
      var value = xmlNode.getAttribute(name);
      if (name && value) {
        this.attributes[name] = value;
        // TODO: This attribute may not be used by the handler.
        this.computeNeededKeys_(value);
      }
    }
  }

  this.key = this.attributes[opensocial.data.ATTR_KEY];
  this.register_();
};


/**
 * Checks if an attribute has been specified for this tag.
 * @param {string} name The attribute name
 * @return {boolean} The attribute is set.
 */
opensocial.data.RequestDescriptor.prototype.hasAttribute = function(name) {
  return !!this.attributes[name];
};


/**
 * Returns the value of a specified attribute. If the attribute includes
 * variable substitutions, they will be evaluated against the DataContext and
 * the result returned.
 *
 * @param {string} name The attribute name to look up.
 * @return {Object} The result of evaluation.
 */
opensocial.data.RequestDescriptor.prototype.getAttribute = function(name) {
  var attrExpression = this.attributes[name];
  if (!attrExpression) {
    return attrExpression;
  }
  // TODO: Don't do this every time - cache the result.
  var expression = opensocial.data.parseExpression_(attrExpression);
  if (!expression) {
    return attrExpression;
  }
  return opensocial.data.DataContext.evalExpression(expression);
};


opensocial.data.parseExpression_ = function(value) {
  if (!value.length) {
    return null;
  }
  var substRex = opensocial.data.VAR_REGEX;
  var text = value;
  var parts = [];
  var match = text.match(substRex);
  if (!match) {
    return null;
  }
  while (match) {
    if (match[1].length > 0) {
      parts.push(opensocial.data.transformLiteral_(match[1]));
    }
    var expr = match[2].substring(2, match[2].length - 1);
    parts.push('(' + expr + ')');
    text = match[3];
    match = text.match(substRex);
  }
  if (text.length > 0) {
    parts.push(opensocial.data.transformLiteral_(text));
  }
  return parts.join('+');
};


/**
 * Transforms a literal string for inclusion into a variable evaluation:
 *   - Escapes single quotes.
 *   - Replaces newlines with spaces.
 *   - Addes single quotes around the string.
 */
opensocial.data.transformLiteral_ = function(string) {
  return "'" + string.replace(/'/g, "\\'").
      replace(/\n/g, " ") + "'";
};


/**
 * Sends this request off to be fulfilled. The current DataContext state will
 * be used to reslove any variable references.
 */
opensocial.data.RequestDescriptor.prototype.sendRequest = function() {
  var ns = opensocial.data.NSMAP[this.tagParts[0]];
  var handler = null;
  if (ns) {
    handler = ns[this.tagParts[1]];
  }
  if (!handler) {
    throw Error("Data handler undefined for " + this.tagName);
  }
  handler(this);
};


/**
 * Creates a closure to this RequestDescriptor's sendRequest() method.
 */
opensocial.data.RequestDescriptor.prototype.getSendRequestClosure = function() {
  var self = this;
  return function() {
    self.sendRequest();
  };
};


/**
 * Computes the keys needed by an attribute by looking for variable substitution
 * markup. For example if the attribute is "http://example.com/${user.id}", the
 * "user" key is needed. The needed keys are set as properties into a member of
 * this RequestDescriptor.
 * @param {string} attribute The value of the attribute to inspect.
 * @private
 */
opensocial.data.RequestDescriptor.prototype.computeNeededKeys_ = function(attribute) {
  var substRex = opensocial.data.VAR_REGEX;
  var match = attribute.match(substRex);
  while (match) {
    var token = match[2].substring(2, match[2].length - 1);
    var key = token.split(".")[0];
    if (!this.neededKeys) {
      this.neededKeys = {};
    }
    this.neededKeys[key] = true;
    match = match[3].match(substRex);
  }
};


/**
 * Registers this RequestDescriptor using its key.
 * @private
 */
opensocial.data.RequestDescriptor.prototype.register_ = function() {
  opensocial.data.registerRequestDescriptor(this);
};



/**
 * Evaluates a JS expression against the DataContext.
 * @param {string} expr The expression to evaluate.
 * @return {Object} The result of evaluation.
 */
opensocial.data.DataContext.evalExpression = function(expr) {
  return (new Function("context", "with (context) return " + expr))
      (opensocial.data.DataContext.getData());
};


/**
 * @type {Object} Map of currently registered RequestDescriptors (by key).
 * @private
 */
opensocial.data.requests_ = {};


/**
 * Registers a RequestDescriptor by key in the global registry.
 * @param {RequestDescriptor} requestDescriptor The RequestDescriptor to
 * register.
 */
opensocial.data.registerRequestDescriptor = function(requestDescriptor) {
  if (opensocial.data.requests_[requestDescriptor.key]) {
    throw Error("Request already registered for " + requestDescriptor.key);
  }
  opensocial.data.requests_[requestDescriptor.key] = requestDescriptor;
};


/**
 * @type {DataRequest} A shared DataRequest object for batching OS API data
 * calls.
 * @private
 */
opensocial.data.currentAPIRequest_ = null;


/**
 * @type {Array<string>} An array of keys requested by the shared DataRequest.
 * @private
 */
opensocial.data.currentAPIRequestKeys_ = null;


/**
 * @type {Object<string, Function(Object)>} A map of custom callbacks for the
 * keys in the shared DataRequest.
 * @private
 */
opensocial.data.currentAPIRequestCallbacks_ = null;


/**
 * Gets the shared DataRequest, constructing it lazily when needed.
 * Access to this object is provided so that various sub-requests can be
 * constructed (i.e. via newFetchPersonRequest()). Neither add() nor send()
 * should be called on this object - doing so will lead to undefined behavior.
 * Use opensocial.data.addToCurrentAPIRequest() instead.
 * TODO: Create a wrapper that doesn't support add() and send().
 * @return {DataRequest} The shared DataRequest.
 */
opensocial.data.getCurrentAPIRequest = function() {
  if (!opensocial.data.currentAPIRequest_) {
    opensocial.data.currentAPIRequest_ = opensocial.newDataRequest();
    opensocial.data.currentAPIRequestKeys_ = [];
    opensocial.data.currentAPIRequestCallbacks_ = {};
  }
  return opensocial.data.currentAPIRequest_;
};


/**
 * Adds a request to the current shared DataRequest object. Any requests
 * added in a synchronous block of code will be batched. The requests will be
 * automatically sent once the syncronous block is done executing.
 * @param {Object} request Specifies data to fetch
 * (constructed via DataRequest's newFetch???Request() methods)
 * @param {String} key The key to map generated response data to
 * @param {Function(string, ResponseItem)} opt_callback An optional callback
 * function to pass the returned ResponseItem to. If present, the function will
 * be called with the key and ResponseItem as params. If this is omitted, the
 * ResponseItem will be passed to putDataSet() with the specified key.
 */
opensocial.data.addToCurrentAPIRequest = function(request, key, opt_callback) {
  opensocial.data.getCurrentAPIRequest().add(request, key);
  opensocial.data.currentAPIRequestKeys_.push(key);

  if (opt_callback) {
    opensocial.data.currentAPIRequestCallbacks_[key] = opt_callback;
  }

  window.setTimeout(opensocial.data.sendCurrentAPIRequest_, 0);
};


/**
 * Sends out the current shared DataRequest. The reference is removed, so that
 * when new requests are added, a new shared DataRequest object will be
 * constructed.
 * @private
 */
opensocial.data.sendCurrentAPIRequest_ = function() {
  if (opensocial.data.currentAPIRequest_) {
    opensocial.data.currentAPIRequest_.send(opensocial.data.createSharedRequestCallback_());
    opensocial.data.currentAPIRequest_ = null;
  }
};


/**
 * Creates a callback closure for processing a DataResponse. The closure
 * remembers which keys were requested, and what custom callbacks need to be
 * called.
 * @return {Function(DataResponse)} a handler for DataResponse.
 * @private
 */
opensocial.data.createSharedRequestCallback_ = function() {
  var keys = opensocial.data.currentAPIRequestKeys_;
  var callbacks = opensocial.data.currentAPIRequestCallbacks_;
  return function(data) {
    opensocial.data.onAPIResponse(data, keys, callbacks);
  };
};


/**
 * Processes a response to the shared API DataRequest by looping through
 * requested keys and notifying appropriate parties of the received data.
 * @param {DataResonse} data Data received from the server
 * @param {Array<string>} keys The list of keys that were requested
 * @param {Object<string, Function(string, ResponseItem)>} callbacks A map of
 * any custom callbacks by key.
 */
opensocial.data.onAPIResponse = function(responseItem, keys, callbacks) {
  for (var i = 0; i < keys.length; i++) {
    var key = keys[i];
    var item = responseItem.get(key);
    if (!item.hadError()) {
      var data = opensocial.data.extractJson_(item, key);
      if (callbacks[key]) {
        callbacks[key](key, data);
      } else {
        opensocial.data.DataContext.putDataSet(key, data);
      }
    }
    // TODO: What should we do if there *is* an error?
  }
};

/**
 * Extract the JSON payload from the ResponseItem. This includes
 * iterating over an array of API objects and extracting their JSON into a
 * simple array structure.
 */
opensocial.data.extractJson_ = function(responseItem, key) {
  var data = responseItem.getData();
  if (data.array_) {
    var out = [];
    for (var i = 0; i < data.array_.length; i++) {
      out.push(data.array_[i].fields_);
    }
    data = out;
    
    // For os:PeopleRequests that request @groupId="self", crack the array
    var request = opensocial.data.requests_[key];
    if (request.tagName == "os:PeopleRequest") {
      var groupId = request.getAttribute("groupId");
      if ((!groupId || groupId == "@self") && data.length == 1) {
        data = data[0];
      }
    }
  } else {
    data = data.fields_ || data;
  }
  return data;
};


/**
 * Registers a tag as a data request handler.
 * @param {string} name Prefixed tag name.
 * @param {Function} handler Method to call when this tag is invoked.
 *
 * TODO: Store these tag handlers separately from the ones for UI tags.
 * TODO: Formalize the callback interface.
 */
opensocial.data.registerRequestHandler = function(name, handler) {
  var tagParts = name.split(':');
  var ns = opensocial.data.NSMAP[tagParts[0]];
  if (!ns) {
    if (!opensocial.xmlutil.NSMAP[tagParts[0]]) {
      opensocial.xmlutil.NSMAP[tagParts[0]] = null;
    }
    ns = opensocial.data.NSMAP[tagParts[0]] = {};
  } else if (ns[tagParts[1]]) {
    throw Error('Request handler ' + tagParts[1] + ' is already defined.');
  }

  ns[tagParts[1]] = handler;
};


/**
 * Loads and executes all inline data request sections.
 * @param {Object} opt_doc Optional document to use instead of window.document.
 * TODO: Currently this processes all 'script' blocks together,
 *     instead of collecting them all and then processing together. Not sure
 *     which is preferred yet.
 * TODO: Figure out a way to pass in params used only for data
 *     and not for template rendering.
 */
opensocial.data.processDocumentMarkup = function(opt_doc) {
  var doc = opt_doc || document;
  var nodes = doc.getElementsByTagName("script");
  for (var i = 0; i < nodes.length; ++i) {
    var node = nodes[i];
    if (node.type == opensocial.data.SCRIPT_TYPE) {
      opensocial.data.loadRequests(node);
    }
  }
  opensocial.data.registerRequestDependencies();
  opensocial.data.executeRequests();
};


/**
 * Process the document when it's ready.
 */
if (window['gadgets'] && window['gadgets']['util']) {
  gadgets.util.registerOnLoadHandler(opensocial.data.processDocumentMarkup);
}


/**
 * Parses XML data and constructs the pending request list.
 * @param {Element|string} xml A DOM element or string containing XML.
 */
opensocial.data.loadRequests = function(xml) {
  if (typeof(xml) == 'string') {
    opensocial.data.loadRequestsFromMarkup_(xml);
    return;
  }
  var node = xml;
  xml = node.value || node.innerHTML;
  opensocial.data.loadRequestsFromMarkup_(xml);
};


/**
 * Parses XML data and constructs the pending request list.
 * @param {string} xml A string containing XML markup.
 */
opensocial.data.loadRequestsFromMarkup_ = function(xml) {
  xml = opensocial.xmlutil.prepareXML(xml);
  var doc = opensocial.xmlutil.parseXML(xml);

  // Find the <root> node (skip DOCTYPE).
  var node = doc.firstChild;
  while (node.nodeType != 1) {
    node = node.nextSibling;
  }

  opensocial.data.processDataNode_(node);
};


/**
 * Processes a data request node for data sets.
 * @param {Node} node The node to process.
 * @private
 */
opensocial.data.processDataNode_ = function(node) {
  for (var child = node.firstChild; child; child = child.nextSibling) {
    if (child.nodeType == 1) {
      var requestDescriptor = new opensocial.data.RequestDescriptor(child);
    }
  }
};


opensocial.data.registerRequestDependencies = function() {
  for (var key in opensocial.data.requests_) {
    var request = opensocial.data.requests_[key];
    var neededKeys = request.neededKeys;
    var dependencies = [];
    for (var neededKey in neededKeys) {
      if (opensocial.data.DataContext.getDataSet(neededKey) == null &&
          opensocial.data.requests_[neededKey]) {
        dependencies.push(neededKey);
      }
    }
    if (dependencies.length > 0) {
      opensocial.data.DataContext.registerListener(dependencies,
          request.getSendRequestClosure());
      request.dependencies = true;
    }
  }
};


opensocial.data.executeRequests = function() {
  for (var key in opensocial.data.requests_) {
    var request = opensocial.data.requests_[key];
    if (!request.dependencies) {
      request.sendRequest();
    }
  }
};


/**
 * Transforms "@"-based special values such as "@owner" into uppercase
 * keywords like "OWNER".
 * @param {string} value The value to transform.
 * @return {string} Transformed or original value.
 */
opensocial.data.transformSpecialValue = function(value) {
  if (value.substring(0, 1) == '@') {
    return value.substring(1).toUpperCase();
  }
  return value;
};


/**
 * Parses a string of comma-separated field names and adds the resulting array
 * (if any) to the params object.
 * @param {object} params The params object used to construct an Opensocial
 * DataRequest
 * @param {string} fieldStr A string containing comma-separated field names
 */
opensocial.data.addFieldsToParams_ = function(params, fieldsStr) {
  if (!fieldsStr) {
    return;
  }
  var fields = fieldsStr.replace(/(^\s*|\s*$)/g, '').split(/\s*,\s*/);
  params[opensocial.DataRequest.PeopleRequestFields.PROFILE_DETAILS] = fields;
};


/**
 * Anonymous function defines OpenSocial specific requests.
 * Automatically called when this file is loaded.
 */
(function() {
  opensocial.data.registerRequestHandler("os:ViewerRequest", function(descriptor) {
    var params = {};
    opensocial.data.addFieldsToParams_(params, descriptor.getAttribute("fields"));
    var req = opensocial.data.getCurrentAPIRequest().newFetchPersonRequest("VIEWER", params);
    // TODO: Support @fields param.
    opensocial.data.addToCurrentAPIRequest(req, descriptor.key);
  });

  opensocial.data.registerRequestHandler("os:OwnerRequest", function(descriptor) {
    var params = {};
    opensocial.data.addFieldsToParams_(params, descriptor.getAttribute("fields"));
    var req = opensocial.data.getCurrentAPIRequest().newFetchPersonRequest("OWNER", params);
    // TODO: Support @fields param.
    opensocial.data.addToCurrentAPIRequest(req, descriptor.key);
  });

  opensocial.data.registerRequestHandler("os:PeopleRequest", function(descriptor) {
    var userId = descriptor.getAttribute("userId");
    var groupId = descriptor.getAttribute("groupId") || "@self";
    var idSpec = {};
    idSpec.userId = opensocial.data.transformSpecialValue(userId);
    if (groupId != "@self") {
      idSpec.groupId = opensocial.data.transformSpecialValue(groupId);
    }
    var params = {};
    opensocial.data.addFieldsToParams_(params, descriptor.getAttribute("fields"));
    // TODO: Support other params.
    var req = opensocial.data.getCurrentAPIRequest().newFetchPeopleRequest(
        opensocial.newIdSpec(idSpec), params);
    // TODO: Annotate with the @ids property.
    opensocial.data.addToCurrentAPIRequest(req, descriptor.key);
  });

  opensocial.data.registerRequestHandler("os:ActivitiesRequest", function(descriptor) {
    var userId = descriptor.getAttribute("userId");
    var groupId = descriptor.getAttribute("groupId") || "@self";
    var idSpec = {};
    idSpec.userId = opensocial.data.transformSpecialValue(userId);
    if (groupId != "@self") {
      idSpec.groupId = opensocial.data.transformSpecialValue(groupId);
    }
    // TODO: Support other params.
    var req = opensocial.data.getCurrentAPIRequest().newFetchActivitiesRequest(
        opensocial.newIdSpec(idSpec));
    opensocial.data.addToCurrentAPIRequest(req, descriptor.key);
  });

  opensocial.data.registerRequestHandler("os:HttpRequest", function(descriptor) {
    var href = descriptor.getAttribute('href');
    var format = descriptor.getAttribute('format') || "json";
    var params = {};
    params[gadgets.io.RequestParameters.CONTENT_TYPE] =
        format.toLowerCase() == "text" ? gadgets.io.ContentType.TEXT :
            gadgets.io.ContentType.JSON;
    params[gadgets.io.RequestParameters.METHOD] =
        gadgets.io.MethodType.GET;
    gadgets.io.makeRequest(href, function(obj) {
        opensocial.data.DataContext.putDataSet(descriptor.key, obj.data);
    }, params);
  });
})();


/**
 * Pre-populate a Data Set based on application's URL parameters.
 */
(opensocial.data.populateParams_ = function() {
  if (window["gadgets"] && gadgets.util.hasFeature("views")) {
    opensocial.data.DataContext.putDataSet("ViewParams", gadgets.views.getParams());
  }
})();
