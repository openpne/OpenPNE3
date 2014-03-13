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
 * @fileoverview Provides various utility functions used throughout the library.
 */


/**
 * Trims leading and trailing whitespace from a string.
 * @param {string} string The input string.
 * @return {string} Input with leading and trailing whitespace removed.
 */
os.trim = function(string) {
  return string.replace(/^\s+|\s+$/g, '');         
};


/**
 * Checks whether or not a given character is alpha-numeric. * 
 * @param {string} ch Character to check.
 * @return {boolean} This character is alpha-numeric.
 */
os.isAlphaNum = function(ch) {
  // TODO: Try with ch.charCodeAt() to see if faster.
  return ((ch >= 'a' && ch <= 'z') || (ch >= 'A' && ch <= 'Z') ||
      (ch >= '0' && ch <= '9') || ch == '_');
};

/**
 * Clears the children of a given DOM node.
 * @param {Node} node DOM node to clear.
 */
os.removeChildren = function(node) {
  while (node.firstChild) {
    node.removeChild(node.firstChild);
  }
};

/**
 * Copies all children from one node to another.
 * @param {Node} sourceNode DOM node with children to append.
 * @param {Node} targetNode DOM node to append sourceNode's children to.
 */
os.appendChildren = function(sourceNode, targetNode) {
  if (sourceNode == targetNode) {
    return;
  }
  while (sourceNode.firstChild) {
    targetNode.appendChild(sourceNode.firstChild);
  }
};

/**
 * Given a property name (e.g. 'foo') will create a JavaBean-style getter 
 * (e.g. 'getFoo').
 * @param {string} propertyName Name of the property.
 * @return {string} The name of the getter function.
 */
os.getPropertyGetterName = function(propertyName) {
  var getter = 'get' + propertyName.charAt(0).toUpperCase() +
      propertyName.substring(1);
  return getter;
};


/**
 * Given a constant-style string (e.g., 'FOO_BAR'), will return a camel-cased
 * string (e.g., fooBar).
 * @param {string} str String to convert to camelCase.
 * @return {string} The camel-cased string.
 */
os.convertToCamelCase = function(str) {
  // Preserve the upper case string, to work around problems in some locales
  // (such as Turkish, where 'I'.toLowerCase().toUpperCase() != 'I')
  var upper = str.toUpperCase();
  var words = str.toLowerCase().split('_');
  var out = [];
  var index = words[0].length + 1;
  out.push(words[0]);
  for (var i = 1; i < words.length; ++i) {
    if (words[i].length > 0) {
      var piece = upper.charAt(index) + words[i].substring(1);
      out.push(piece);
    }
    index += words[i].length + 1
  }
  return out.join('');
};
