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
 * @fileoverview Implements namespace support for custom tags.
 *
 * TODO(davidbyttow): Refactor this.
 */

/**
 * Map of namespace collections.
 *
 * Each namespace collection is either a map of tag handlers, or an object
 * that has a getTag(tagName) method that will return a tag handler based on
 * name.
 *
 * A tag handler function should be have the following signature:
 * function({Element} node, {Object} data, {JSEvalContext} context)
 * where context is the JSEvalContext used to wrap data.
 *
 * For simpler implementations,
 * function({Element} node, {Object} data)
 * can be used, omitting the third param.
 *
 * Handler functions can return a string, a DOM Element or an Object with
 * {Element} root and, optionally, {Function} onAttach properties.
 *
 * @type {Object}
 * @private
 */
os.nsmap_ = {};

/***
 * Registers the given namespace with a specified URL. Throws an error if it
 * already exists as a different URL.
 * @param {string} ns Namespace tag.
 * @param {string} url URI Reference for namespace.
 * @return {Object} The object map of registered tags.
 */
os.createNamespace = function(ns, url) {
  var tags = os.nsmap_[ns];
  if (! os.nsmap_.hasOwnProperty(ns)) {
    tags = {};
    os.nsmap_[ns] = tags;
    opensocial.xmlutil.NSMAP[ns] = url;
  } else if (opensocial.xmlutil.NSMAP[ns] == null ) {
    // Lazily register an auto-created namespace.
    opensocial.xmlutil.NSMAP[ns] = url;
  } else if (opensocial.xmlutil.NSMAP[ns] != url) {
    throw("Namespace " + ns + " already defined with url " +
        opensocial.xmlutil.NSMAP[ns]);
  }
  return tags;
};

/**
 * Returns the namespace object for a given prefix.
 */
os.getNamespace = function(prefix) {
  return os.nsmap_[prefix];
};

os.addNamespace = function(ns, url, nsObj) {
  if (! os.nsmap_.hasOwnProperty(ns)) {
    if (opensocial.xmlutil.NSMAP[ns] == null) {
      // Lazily register an auto-created namespace.
      opensocial.xmlutil.NSMAP[ns] = url;
      return;
    } else {
      throw ("Namespace '" + ns + "' already exists!");
    }
  }
  os.nsmap_[ns] = nsObj;
  opensocial.xmlutil.NSMAP[ns] = url;
};

os.getCustomTag = function(ns, tag) {
  if (! os.nsmap_.hasOwnProperty(ns)) {
    return null;
  }
  var nsObj = os.nsmap_[ns];
  if (nsObj.getTag) {
    return nsObj.getTag(tag);
  } else {
    return nsObj[tag];
  }
};