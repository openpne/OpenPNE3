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
 * @fileoverview Standard methods invoked by containers to use the template API.
 *
 * Sample usage:
 *  &lt;script type="text/os-template" tag="os:Button">
 *    <button onclick="alert('Clicked'); return false;">
 *      <os:renderAll/>
 *    </button>
 *  &lt;/script]
 *
 *  &lt;script type="text/os-template"]
 *    <os:Button>
 *      <div>Click me</div>
 *    </os:Button>
 *  &lt;/script]
 *
 * os.Container.registerDocumentTemplates();
 * os.Container.renderInlineTemplates();
 */

os.Container = {};

/***
 * @type {Array.<Object>} Array of registered inline templates.
 * @private
 */
os.Container.inlineTemplates_ = [];

/**
 * @type {Array.<Function>} An array of callbacks to fire when the page DOM has
 * loaded. This will be null until the first callback is added
 * @see registerDomListener_
 * @private
 */
os.Container.domLoadCallbacks_ = null;

/**
 * @type {boolean} A boolean flag determining wether the page DOM has loaded.
 * @private
 */
os.Container.domLoaded_ = false;

/**
 * @type {number} The number of libraries needed to load.
 */
os.Container.requiredLibraries_ = 0;

/**
 * @type {boolean} Determines whether all templates are automatically processed.
 */
os.Container.autoProcess_ = true;

/**
 * @type {boolean} Has the document been processed already?
 */
os.Container.processed_ = false;

os.Container.disableAutoProcessing = function() {
  if (os.Container.processed_) {
    throw Error("Document already processed.");
  }
  os.Container.autoProcess_ = false;
};

// Create reference from opensocial-templates.
os.disableAutoProcessing = os.Container.disableAutoProcessing;

/**
 * Registers the DOM Load listener to fire when the page DOM is available.
 * TODO: See if we can use gadgets.util.regiterOnLoadHandler() here.
 * TODO: Currently for everything but Mozilla, this just registers an
 * onLoad listener on the window. Should use DOMContentLoaded on Opera9,
 * appropriate hacks (polling?) on IE and Safari.
 * @private
 */
os.Container.registerDomLoadListener_ = function() {
  var gadgets = window['gadgets'];
  if (gadgets && gadgets.util) {
    gadgets.util.registerOnLoadHandler(os.Container.onDomLoad_);
  } else if (typeof(navigator) != 'undefined' && navigator.product && 
      navigator.product == 'Gecko') {
    window.addEventListener('DOMContentLoaded', os.Container.onDomLoad_, false);
  } if (window.addEventListener) {
    window.addEventListener('load', os.Container.onDomLoad_, false);
  } else {
    if (!document.body) {
      setTimeout(arguments.callee, 0);
      return;
    }
    var oldOnLoad = window.onload || function() {};
    window.onload = function() {
      oldOnLoad();
      os.Container.onDomLoad_();
    };
  }
};

/**
 * To be called when the page DOM is available - will fire all the callbacks
 * in os.Container.domLoadCallbacks_.
 * @private
 */
os.Container.onDomLoad_ = function() {
  if (os.Container.domLoaded_) {
    return;
  }
  for (var i = 0; i < os.Container.domLoadCallbacks_.length; i ++) {
    try {
      os.Container.domLoadCallbacks_[i]();
    } catch (e) {
      os.log(e);
    }
  }
  os.Container.domLoaded_ = true;
};

/**
 * Adds a callback to be fired when the page DOM is available. If the page
 * is already loaded, the callback will execute asynchronously.
 * @param {Function} callback The callback to be fired when DOM is loaded.
 */
os.Container.executeOnDomLoad = function(callback) {
  if (os.Container.domLoaded_) {
    setTimeout(callback, 0);
  } else {
    if (os.Container.domLoadCallbacks_ == null) {
      os.Container.domLoadCallbacks_ = [];
      os.Container.registerDomLoadListener_();
    }
    os.Container.domLoadCallbacks_.push(callback);
  }
};

/**
 * Compiles and registers all DOM elements in the document. Templates are
 * registered as tags if they specify their name with the "tag" attribute
 * and as templates if they have a name (or id) attribute.
 * @param {Object} opt_doc Optional document to use rather than the global doc.
 */
os.Container.registerDocumentTemplates = function(opt_doc) {
  var doc = opt_doc || document;
  var nodes = doc.getElementsByTagName(os.Container.TAG_script_);
  for (var i = 0; i < nodes.length; ++i) {
    var node = nodes[i];
    if (os.Container.isTemplateType_(node.type)) {
      var tag = node.getAttribute('tag');
      if (tag) {
        os.Container.registerTagElement_(node, tag);
      } else if (node.getAttribute('name')) {
        os.Container.registerTemplateElement_(node, node.getAttribute('name'));
      }
    }
  }
};

/**
 * Compiles and registers all unnamed templates in the document.
 * @param {Object} opt_data Optional JSON data.
 * @param {Object} opt_doc Optional document to use instead of window.document.
 */
os.Container.compileInlineTemplates = function(opt_data, opt_doc) {
  var doc = opt_doc || document;
  var nodes = doc.getElementsByTagName(os.Container.TAG_script_);
  for (var i = 0; i < nodes.length; ++i) {
    var node = nodes[i];
    if (os.Container.isTemplateType_(node.type)) {
      var name = node.getAttribute('tag');
      if (!name || name.length < 0) {
        var template = os.compileTemplate(node, name);
        if (template) {
          os.Container.inlineTemplates_.push(
              {'template': template, 'node': node});
        } else {
          os.warn('Failed compiling inline template.');
        }
      }
    }
  }
};

/**
 * @return {JsEvalContext} the default rendering context to use - this will
 * contain all available data. 
 */
os.Container.getDefaultContext = function() {
  if ((window['gadgets'] && gadgets.util.hasFeature('opensocial-data')) ||
      (opensocial.data.getDataContext)) {
    return os.createContext(opensocial.data.getDataContext().getData());
  }
  return os.createContext({});
};

/**
 * Renders any registered inline templates.
 * @param {Object} opt_doc Optional document to use instead of window.document.
 */
os.Container.renderInlineTemplates = function(opt_doc) {
  var doc = opt_doc || document;
  var context = os.Container.getDefaultContext();
  var inlined = os.Container.inlineTemplates_;
  for (var i = 0; i < inlined.length; ++i) {
    var template = inlined[i].template;
    var node = inlined[i].node;
    var id = '_T_' + template.id;
    var rendered = true;
    var el = doc.getElementById(id);
    if (!el) {
      el = doc.createElement('div');
      el.setAttribute('id', id);
      node.parentNode.insertBefore(el, node);
      rendered = false;
    }

    // Only honor @before and @require attributes if the opensocial-data
    // feature is present.
    if ((window['gadgets'] && gadgets.util.hasFeature('opensocial-data')) ||
        (opensocial.data.DataContext)) {
      var beforeData = node.getAttribute('before') ||
          node.getAttribute('beforeData');
      if (beforeData) {
        // Automatically hide this template when specified data is available.
        var keys = beforeData.split(/[\, ]+/);
        opensocial.data.DataContext.registerListener(keys,
            os.Container.createHideElementClosure(el));
      }

      var requiredData = node.getAttribute('require') ||
          node.getAttribute('requireData');
      if (requiredData) {
        // This template will render when the specified data is available.
        var keys = requiredData.split(/[\, ]+/);
        var callback = os.Container.createRenderClosure(template, el);
        if ("true" == node.getAttribute("autoUpdate")) {
          if (rendered) {
            opensocial.data.getDataContext().registerDeferredListener_(keys, callback);
          } else {
            opensocial.data.getDataContext().registerListener(keys, callback);
          }
        } else {
          opensocial.data.getDataContext().registerOneTimeListener_(keys, callback);
        }
      } else {
        template.renderInto(el, null, context);
      }
    } else {
      template.renderInto(el, null, context);
    }
  }
};

/**
* Creates a closure that will render the a template into an element with
* optional data.
* @param {Object} template The template object to use.
* @param {Element} element The DOM element to inject the template into.
* @param {Object} opt_data Optional data to be used as to create a context.
* @param {Object} opt_context Optional pre-constructed rendering context.
* @return {Function} The constructed closure.
* TODO(davidbyttow): Move this into util.js
*/
os.Container.createRenderClosure = function(template, element, opt_data,
    opt_context) {
 var closure = function() {
   var context = opt_context;
   var data = opt_data;
   if (!context) {
     if (data) {
       context = os.createContext(data);
     } else {
       context = os.Container.getDefaultContext();
       data = context.data_;
     }
   }
   template.renderInto(element, data, context);
 };
 return closure;
};

/**
 * Creates a closure that will hide a DOM element.
 * @param {Element} element The DOM element to inject the template into.
 * @return {Function} The constructed closure.
 * TODO(davidbyttow): Move this into util.js
 */
os.Container.createHideElementClosure = function(element) {
  var closure = function() {
    displayNone(element);
  };
  return closure;
};

/**
 * Compiles and registers a template from a DOM element.
 * @param {string} elementId Id of DOM element from which to create a template.
 * @return {Object} The compiled and registered template object.
 */
os.Container.registerTemplate = function(elementId) {
  var element = document.getElementById(elementId);
  return os.Container.registerTemplateElement_(element);
};

/**
 * Registers a custom tag from a namespaced DOM element.
 * @param {string} elementId Id of the DOM element to register.
 */
os.Container.registerTag = function(elementId) {
  var element = document.getElementById(elementId);
  os.Container.registerTagElement_(element, elementId);
};

/**
 * Renders a DOM element with a specified template and contextual data.
 * @param {string} elementId Id of DOM element to inject into.
 * @param {string} templateId Id of the template.
 * @param {Object} opt_data Data to supply to template.
 */
os.Container.renderElement = function(elementId, templateId, opt_data) {
  var template = os.getTemplate(templateId);
  if (template) {
    var element = document.getElementById(elementId);
    if (element) {
      template.renderInto(element, opt_data);
    } else {
      os.warn('Element (' + elementId + ') not found to render into.');
    }
  } else {
    os.warn('Template (' + templateId + ') not registered.');
  }
};

/**
 * Compiles and renders all inline templates.
 * @param {Object} opt_doc Optional document to use instead of window.document.
 */
os.Container.processInlineTemplates = function(opt_doc) {
  os.Container.compileInlineTemplates(opt_doc);
  os.Container.renderInlineTemplates(opt_doc);
};

/**
 * Process the gadget configuration when it is available.
 */
os.Container.processGadget = function() {
  if (!window['gadgets']) {
    return;
  }
  
  // Honor the "disableAutoProcessing" feature param.
  var params = gadgets.util.getFeatureParameters("opensocial-templates");
  if (!params) {
    return;
  }
  if (params.disableAutoProcessing && 
      params.disableAutoProcessing.toLowerCase != "false") {
    os.Container.autoProcess_ = false;
  }
  
  // Honor the "requireLibrary" feature param(s).
  if (params.requireLibrary) {
    if (typeof params.requireLibrary == "string") {
      os.Container.addRequiredLibrary(params.requireLibrary);
    } else {
      for (var i = 0; i < params.requireLibrary.length; i++) {
        os.Container.addRequiredLibrary(params.requireLibrary[i]);
      }
    }
  }  
};

//Process the gadget when the page loads.
os.Container.executeOnDomLoad(os.Container.processGadget);

/**
 * A flag to determine if auto processing is waiting for libraries to load.
 * @type {boolean}
 */
os.Container.processWaitingForLibraries_ = false;

/**
 * Utility method which will automatically register all templates
 * and render all that are inline.
 * @param {Object} opt_data Optional JSON object to render templates against
 * @param {Document} opt_doc Optional document to use instead of window.document
 */
os.Container.processDocument = function(opt_data, opt_doc) {
  if (os.Container.requiredLibraries_ > 0) {
    os.Container.processWaitingForLibraries_ = true;
    return;
  }
  os.Container.processWaitingForLibraries_ = false;
  os.Container.registerDocumentTemplates(opt_doc);
  os.Container.processInlineTemplates(opt_data, opt_doc);
  os.Container.processed_ = true;
};

// Expose function in opensocial.template namespace.
os.process = os.Container.processDocument;

// Process the document when the page loads - unless requested not to.
os.Container.executeOnDomLoad(function() {
  if (os.Container.autoProcess_) {
    os.Container.processDocument();
  }
});

/**
 * A handler called when one of the required libraries loads.
 */
os.Container.onLibraryLoad_ = function() {
  if (os.Container.requiredLibraries_ > 0) {
    os.Container.requiredLibraries_--;
    if (os.Container.requiredLibraries_ == 0 && 
        os.Container.processWaitingForLibraries_) {
      os.Container.processDocument();
    }
  } 
};

/**
 * Adds a required library - the processing will be deferred until all
 * required libraries have loaded.
 * @param {string} libUrl The URL of the library needed to process this page
 */
os.Container.addRequiredLibrary = function(libUrl) {
  os.Container.requiredLibraries_++;
  os.Loader.loadUrl(libUrl, os.Container.onLibraryLoad_);
};

/**
 * @type {string} Tag name of a template.
 * @private
 */
os.Container.TAG_script_ = 'script';

/**
 * @type {Object} Map of allowed template content types.
 * @private
 * TODO(davidbyttow): Remove text/template.
 */
os.Container.templateTypes_ = {};
os.Container.templateTypes_['text/os-template'] = true;
os.Container.templateTypes_['text/template'] = true;

/**
 * Checks if a given type name is properly named as a template.
 * @param {string} typeName Name of a given type.
 * @return {boolean} This type is considered a template.
 * @private
 */
os.Container.isTemplateType_ = function(typeName) {
  return os.Container.templateTypes_[typeName] != null;
};

/**
 * Compiles and registers a template from a DOM element.
 * @param {Element} element DOM element from which to create a template.
 * @param {string} opt_id Optional id for template.
 * @return {Object} The compiled and registered template object.
 * @private
 */
os.Container.registerTemplateElement_ = function(element, opt_id) {
  var template = os.compileTemplate(element, opt_id);
  if (template) {
    os.registerTemplate(template);
  } else {
    os.warn('Could not compile template (' + element.id + ')');
  }
  return template;
};

/**
 * Registers a custom tag from a namespaced DOM element.
 * @param {Element} element DOM element to register.
 * @param {string} name Name of the tag.
 * @private
 */
os.Container.registerTagElement_ = function(element, name) {
  var template = os.Container.registerTemplateElement_(element, name);
  if (template) {
    var tagParts = name.split(':');
    // Only register custom tags of the "ns:Tag" format.
    if (tagParts.length == 2) {
      var nsObj = os.getNamespace(tagParts[0]);
      if (!nsObj) {
        // Auto Create a namespace for lazy registration.
        nsObj = os.createNamespace(tagParts[0], null);
      }    
      nsObj[tagParts[1]] = os.createTemplateCustomTag(template);
    }
  }
};
