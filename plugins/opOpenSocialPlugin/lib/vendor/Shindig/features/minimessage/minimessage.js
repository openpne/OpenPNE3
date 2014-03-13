/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/**
 * @fileoverview Library for creating small dismissible messages in gadgets.
 * Typical use cases:
 * <ul>
 * <li> status messages, e.g. loading, saving, etc.
 * <li> promotional messages, e.g. new features, new gadget, etc.
 * <li> debug/error messages, e.g. bad input, failed connection to server
 * </ul>
 */

var gadgets = gadgets || {};

/**
 * @class MiniMessage class.
 *
 * @description Used to create messages that will appear to the user within the
 *     gadget.
 * @param {String} opt_moduleId Optional module Id
 * @param {Element} opt_container Optional HTML container element where
 *                                mini-messages will appear.
 */
gadgets.MiniMessage = function(opt_moduleId, opt_container) {
  this.numMessages_ = 0;
  this.moduleId_ = opt_moduleId || 0;
  this.container_ = typeof opt_container === 'object' ?
                    opt_container : this.createContainer_();
};

/**
 * Helper function that creates a container HTML element where mini-messages
 * will be appended to.  The container element is inserted at the top of gadget.
 * @return {Element} An HTML div element as the message container
 * @private
 */
gadgets.MiniMessage.prototype.createContainer_ = function() {
  var containerId = 'mm_' + this.moduleId_;
  var container = document.getElementById(containerId);

  if (!container) {
    container = document.createElement('div');
    container.id = containerId;

    document.body.insertBefore(container, document.body.firstChild);
  }

  return container;
};

/**
 * Helper function that dynamically inserts CSS rules to the page.
 * @param {String} cssText CSS rules to inject
 * @private
 */
gadgets.MiniMessage.addCSS_ = function(cssText) {
  var head = document.getElementsByTagName('head')[0];
  if (head) {
    var styleElement = document.createElement('style');
    styleElement.type = 'text/css';
    if (styleElement.styleSheet) {
      styleElement.styleSheet.cssText = cssText;
    } else {
      styleElement.appendChild(document.createTextNode(cssText));
    }
    head.insertBefore(styleElement, head.firstChild);
  }
};

/**
 * Helper function that expands a class name into two class names.
 * @param {String} label The CSS class name
 * @return {String} "X Xn", with n is the ID of this module
 * @private
 */
gadgets.MiniMessage.prototype.cascade_ = function(label) {
  return label + ' ' + label + this.moduleId_;
};

/**
 * Helper function that returns a function that dismisses a message by removing
 * the message table element from the DOM.  The action is cancelled if the
 * callback function returns false.
 * @param {Element} element HTML element to remove
 * @param {Function} opt_callback Optional callback function to be called when
 *                                the message is dismissed.
 * @return {Function} A function that dismisses the specified message
 * @private
 */
gadgets.MiniMessage.prototype.dismissFunction_ = function(element, opt_callback) {
  return function() {
    if (typeof opt_callback === 'function' && !opt_callback()) {
      return;
    }
    try {
      element.parentNode.removeChild(element);
    } catch(e) {
      // Silently fail in case the element was already removed.
    }
  };
};

/**
 * Creates a dismissible message with an [[]x] icon that allows users to dismiss
 * the message. When the message is dismissed, it is removed from the DOM
 * and the optional callback function, if defined, is called.
 * @param {String | Object} message The message as an HTML string or DOM element
 * @param {Function} opt_callback Optional callback function to be called when
 *                                the message is dismissed
 * @return {Element} HTML element of the created message
 */
gadgets.MiniMessage.prototype.createDismissibleMessage = function(message,
                                                         opt_callback) {
  var table = this.createStaticMessage(message);
  var td = document.createElement('td');
  td.width = 10;

  var span = td.appendChild(document.createElement('span'));
  span.className = this.cascade_('mmlib_xlink');
  span.onclick = this.dismissFunction_(table, opt_callback);
  span.innerHTML = '[x]';

  table.rows[0].appendChild(td);

  return table;
};

/**
 * Creates a message that displays for the specified number of seconds.
 * When the timer expires,
 * the message is dismissed and the optional callback function is executed.
 * @param {String | Object} message The message as an HTML string or DOM element
 * @param {number} seconds Number of seconds to wait before dismissing
 *                         the message
 * @param {Function} opt_callback Optional callback function to be called when
 *                                the message is dismissed
 * @return {Element} HTML element of the created message
 */
gadgets.MiniMessage.prototype.createTimerMessage = function(message, seconds,
                                                            opt_callback) {
  var table = this.createStaticMessage(message);
  window.setTimeout(this.dismissFunction_(table, opt_callback), seconds * 1000);
  return table;
};

/**
 * Creates a static message that can only be dismissed programmatically
 * (by calling dismissMessage()).
 * @param {String | Object} message The message as an HTML string or DOM element
 * @return {Element} HTML element of the created message
 */
gadgets.MiniMessage.prototype.createStaticMessage = function(message) {
  // Generate and assign unique DOM ID to table.
  var table = document.createElement('table');
  table.id = 'mm_' + this.moduleId_ + '_' + this.numMessages_;
  table.className = this.cascade_('mmlib_table');
  table.cellSpacing = 0;
  table.cellPadding = 0;
  this.numMessages_++;

  var tbody = table.appendChild(document.createElement('tbody'));
  var tr = tbody.appendChild(document.createElement('tr'));

  // Create message column
  var td = tr.appendChild(document.createElement('td'));

  // If the message already exists in DOM, preserve its location.
  // Otherwise, insert it at the top.
  var ELEMENT_NODE = 1;
  if (typeof message === 'object' &&
      message.parentNode &&
      message.parentNode.nodeType === ELEMENT_NODE) {
    var messageClone = message.cloneNode(true);
    message.style.display = 'none';
    messageClone.id = '';
    td.appendChild(messageClone);
    message.parentNode.insertBefore(table, message.nextSibling);
  } else {
    if (typeof message === 'object') {
      td.appendChild(message);
    } else {
      td.innerHTML = message;
    }
    this.container_.appendChild(table);
  }

  return table;
};

/**
 * Dismisses the specified message.
 * @param {Element} message HTML element of the message to remove
 */
gadgets.MiniMessage.prototype.dismissMessage = function(message) {
  this.dismissFunction_(message)();
};

// Injects the default stylesheet for mini-messages.
gadgets.MiniMessage.addCSS_([
  '.mmlib_table {',
    'width: 100%;',
    'font: bold 9px arial,sans-serif;',
    'background-color: #fff4c2;',
    'border-collapse: separate;',
    'border-spacing: 0px;',
    'padding: 1px 0px;',
  '}',
  '.mmlib_xlink {',
    'font: normal 1.1em arial,sans-serif;',
    'font-weight: bold;',
    'color: #0000cc;',
    'cursor: pointer;',
  '}'
].join(''));

// Alias for legacy code

var _IG_MiniMessage = gadgets.MiniMessage;

