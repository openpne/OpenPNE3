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
 * @fileoverview Provides the Template class used to represent a single
 * compiled template that can be rendered into any DOM node.
 */


/**
 * Creates a context object out of a json data object.
 */
os.createContext = function(data, opt_globals) {
  var context = JsEvalContext.create(data);
  context.setVariable(os.VAR_callbacks, []);
  var defaults = os.getContextDefaults_();
  for (var def in defaults) {
    if (defaults.hasOwnProperty(def)) {
      context.setVariable(def, defaults[def]);
    }
  }
  context.setVariable(os.VAR_emptyArray, os.EMPTY_ARRAY);
  if (opt_globals) {
    for (var global in opt_globals) {
      if (opt_globals.hasOwnproperty(global)) {
        context.setVariable(global, opt_globals[global]);
      }
    }
  }
  return context;
};

os.contextDefaults_ = null;

os.getContextDefaults_ = function() {
  if (!os.contextDefaults_) {
    os.contextDefaults_ = {};
    os.contextDefaults_[os.VAR_emptyArray] = os.EMPTY_ARRAY;
    os.contextDefaults_[os.VAR_identifierresolver] = os.getFromContext;
    if (window["JSON"] && JSON.parse) {
      os.contextDefaults_["osx:parseJson"] = JSON.parse;
    } else if (window["gadgets"] && gadgets.json && gadgets.json.parse) {
      os.contextDefaults_["osx:parseJson"] = gadgets.json.parse;
    }
  }
  return os.contextDefaults_;
};

/**
 * A renderable compiled Template. A template can contain one or more
 * compiled nodes pre-processed for JST operation. 
 * @constructor
 */
os.Template = function(opt_id) {
  this.templateRoot_ = document.createElement("span");
  this.id = opt_id || ('template_' + os.Template.idCounter_++);
};

/**
 * A global counter for template IDs.
 * @type {number}
 * @private
 */
os.Template.idCounter_ = 0;

/**
 * A Map of registered templates by keyed ID.
 * @type {Object.<string, os.Template>}
 * @private 
 */
os.registeredTemplates_ = {};

/**
 * Registers a compiled template by its ID.
 * @param {os.Template} template List of template nodes.
 */
os.registerTemplate = function(template) {
  os.registeredTemplates_[template.id] = template;
};

/**
 * De-registers a compiled template..
 * @param {os.Template} template List of template nodes.
 */
os.unRegisterTemplate = function(template) {
  delete os.registeredTemplates_[template.id];
};

/**
 * Gets a registered template by ID.
 * @param {string} templateId The ID of a registered Template.
 * @return {os.Template} A Template object.
 */
os.getTemplate = function(templateId) {
  return os.registeredTemplates_[templateId];
};

/**
 * Sets a single compiled node into this template.
 * @param node {Element} A compiled node.
 */
os.Template.prototype.setCompiledNode_ = function(node) {
  os.removeChildren(this.templateRoot_);
  this.templateRoot_.appendChild(node);
};

/**
 * Sets a list of compiled nodes into this template.
 * @param nodes {Array.Element} An array of compiled nodes.
 */
os.Template.prototype.setCompiledNodes_ = function(nodes) {
  os.removeChildren(this.templateRoot_);
  for (var i = 0; i < nodes.length; i++) {
    this.templateRoot_.appendChild(nodes[i]);
  }
};

/**
 * Renders the template and returns the result.
 * Does not fire callbacks.
 * @return {Element} a DOM element containing the result of template processing
 */
os.Template.prototype.render = function(opt_data, opt_context) {
  if (!opt_context) {
    opt_context = os.createContext(opt_data);
  }
  return os.renderTemplateNode_(this.templateRoot_, opt_context);            
};

/**
 * Renders the template and puts the result into the specified element, then
 * fires callbacks.
 */
os.Template.prototype.renderInto = function(root, opt_data, opt_context) {
  if (!opt_context) {
    opt_context = os.createContext(opt_data);
  }
  var result = this.render(opt_data, opt_context);
  os.removeChildren(root);
  os.appendChildren(result, root);
  os.fireCallbacks(opt_context);
};
