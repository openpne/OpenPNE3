/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/*global gadgets */

/**
 * @fileoverview This library augments gadgets.Prefs with functionality
 * to store prefs dynamically.
 */

/**
 * Stores a preference.
 * @param {String | Object} key The pref to store.
 * @param {String} val The values to store.
 * @private This feature is documented in prefs.js
 */
gadgets.Prefs.prototype.set = function(key, value) {
  if (arguments.length > 2) {
    // For backwards compatibility. This can take the form:
    // prefs.set(key0, value0, key1, value1, key2, value2);

    // prefs.set({key0: value0, key1: value1, key2: value2});
    var obj = {};
    for (var i = 0, j = arguments.length; i < j; i += 2) {
      obj[arguments[i]] = arguments[i + 1];
    }
    gadgets.Prefs.setInternal_(obj);
  } else {
    gadgets.Prefs.setInternal_(key, value);
  }

  var args = [
    null, // go to parent
    "set_pref", // service name
    null, // no callback
    gadgets.util.getUrlParameters().ifpctok || 0 // Legacy IFPC "security".
  ].concat(Array.prototype.slice.call(arguments));

  gadgets.rpc.call.apply(gadgets.rpc, args);
};

/**
 * Stores a preference from the given list.
 * @param {String} key The pref to store.
 * @param {Array.<String | Number>} val The values to store.
 * @private This feature is documented in prefs.js
 */
gadgets.Prefs.prototype.setArray = function(key, val) {
  // We must escape pipe (|) characters to ensure that decoding in
  // getArray actually works properly.
  for (var i = 0, j = val.length; i < j; ++i) {
    if (typeof val[i] !== "number") {
      val[i] = val[i].replace(/\|/g, "%7C");
    }
  }
  this.set(key, val.join('|'));
};

