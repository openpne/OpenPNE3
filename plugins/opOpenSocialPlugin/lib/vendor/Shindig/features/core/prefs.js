/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

/**
 * @fileoverview
 *
 * Provides access to user prefs, module dimensions, and messages.
 *
 * Clients can access their preferences by constructing an instance of
 * gadgets.Prefs and passing in their module id.  Example:
 *
 *   var prefs = new gadgets.Prefs();
 *   var name = prefs.getString("name");
 *   var lang = prefs.getLang();
 *
 * Modules with type=url can also use this library to parse arguments passed
 * by URL, but this is not the common case:
 *
 *   &lt;script src="http://apache.org/shindig/prefs.js"&gt;&lt;/script&gt;
 *   &lt;script&gt;
 *   gadgets.Prefs.parseUrl();
 *   var prefs = new gadgets.Prefs();
 *   var name = prefs.getString("name");
 *   &lt;/script&lg;
 */

var gadgets = gadgets || {};

(function() {

var instance = null;
var prefs = {};
var esc = gadgets.util.escapeString;
var messages = {};
var defaultPrefs = {};
var language = "en";
var country = "US";
var moduleId = 0;

/**
 * Parses all parameters from the url and stores them
 * for later use when creating a new gadgets.Prefs object.
 */
function parseUrl() {
  var params = gadgets.util.getUrlParameters();
  for (var i in params) {
    if (params.hasOwnProperty(i)) {
      if (i.indexOf("up_") === 0 && i.length > 3) {
        prefs[i.substr(3)] = String(params[i]);
      } else if (i === "country") {
        country = params[i];
      } else if (i === "lang") {
        language = params[i];
      } else if (i === "mid") {
        moduleId = params[i];
      }
    }
  }
}

/**
 * Sets default pref values for values left unspecified in the
 * rendering call, but with default_value provided in the spec.
 */
function mergeDefaults() {
  for (var name in defaultPrefs) {
    if (typeof prefs[name] === 'undefined') {
      prefs[name] = defaultPrefs[name];
    }
  }
}

/**
 * @class
 * Provides access to user preferences, module dimensions, and messages.
 *
 * Clients can access their preferences by constructing an instance of
 * gadgets.Prefs and passing in their module id.  Example:
 *
<pre>var prefs = new gadgets.Prefs();
var name = prefs.getString("name");
var lang = prefs.getLang();</pre>
 *
 * @description Creates a new Prefs object.
 *
 * Note: this is actually a singleton. All prefs are linked. If you're wondering
 * why this is a singleton and not just a collection of package functions, the
 * simple answer is that it's how the spec is written. The spec is written this
 * way for legacy compatibility with igoogle.
 */
gadgets.Prefs = function() {
  if (!instance) {
    parseUrl();
    mergeDefaults();
    instance = this;
  }
  return instance;
};

/**
 * Sets internal values
 */
gadgets.Prefs.setInternal_ = function(key, value) {
  if (typeof key === "string") {
    prefs[key] = value;
  } else {
    for (var k in key) {
      if (key.hasOwnProperty(k)) {
        prefs[k] = key[k];
      }
    }
  }
};

/**
 * Initializes message bundles.
 */
gadgets.Prefs.setMessages_ = function(msgs) {
  messages = msgs;
};

/**
 * Initializes default user prefs values.
 */
gadgets.Prefs.setDefaultPrefs_ = function(defprefs) {
  defaultPrefs = defprefs;
};

/**
 * Retrieves a preference as a string.
 * Returned value will be html entity escaped.
 *
 * @param {String} key The preference to fetch
 * @return {String} The preference; if not set, an empty string
 */
gadgets.Prefs.prototype.getString = function(key) {
  if (key === ".lang") { key = "lang"; }
  return prefs[key] ? esc(prefs[key]) : "";
};

/*
 * Indicates not to escape string values when retrieving them.
 * This is an internal detail used by _IG_Prefs for backward compatibility.
 */
gadgets.Prefs.prototype.setDontEscape_ = function() {
  esc = function(k) { return k; };
};

/**
 * Retrieves a preference as an integer.
 * @param {String} key The preference to fetch
 * @return {Number} The preference; if not set, 0
 */
gadgets.Prefs.prototype.getInt = function(key) {
  var val = parseInt(prefs[key], 10);
  return isNaN(val) ? 0 : val;
};

/**
 * Retrieves a preference as a floating-point value.
 * @param {String} key The preference to fetch
 * @return {Number} The preference; if not set, 0
 */
gadgets.Prefs.prototype.getFloat = function(key) {
  var val = parseFloat(prefs[key]);
  return isNaN(val) ? 0 : val;
};

/**
 * Retrieves a preference as a boolean.
 * @param {String} key The preference to fetch
 * @return {Boolean} The preference; if not set, false
 */
gadgets.Prefs.prototype.getBool = function(key) {
  var val = prefs[key];
  if (val) {
    return val === "true" || val === true || !!parseInt(val, 10);
  }
  return false;
};

/**
 * Stores a preference.
 * To use this call,
 * the gadget must require the feature setprefs.
 *
 * <p class="note">
 * <b>Note:</b>
 * If the gadget needs to store an Array it should use setArray instead of
 * this call.
 * </p>
 *
 * @param {String} key The pref to store
 * @param {Object} val The values to store
 */
gadgets.Prefs.prototype.set = function(key, value) {
  throw new Error("setprefs feature required to make this call.");
};

/**
 * Retrieves a preference as an array.
 * UserPref values that were not declared as lists are treated as
 * one-element arrays.
 *
 * @param {String} key The preference to fetch
 * @return {Array.&lt;String&gt;} The preference; if not set, an empty array
 */
gadgets.Prefs.prototype.getArray = function(key) {
  var val = prefs[key];
  if (val) {
    var arr = val.split("|");
    // Decode pipe characters.
    for (var i = 0, j = arr.length; i < j; ++i) {
      arr[i] = esc(arr[i].replace(/%7C/g, "|"));
    }
    return arr;
  }
  return [];
};

/**
 * Stores an array preference.
 * To use this call,
 * the gadget must require the feature setprefs.
 *
 * @param {String} key The pref to store
 * @param {Array} val The values to store
 */
gadgets.Prefs.prototype.setArray = function(key, val) {
  throw new Error("setprefs feature required to make this call.");
};

/**
 * Fetches an unformatted message.
 * @param {String} key The message to fetch
 * @return {String} The message
 */
gadgets.Prefs.prototype.getMsg = function(key) {
  return messages[key] || "";
};

/**
 * Gets the current country, returned as ISO 3166-1 alpha-2 code.
 *
 * @return {String} The country for this module instance
 */
gadgets.Prefs.prototype.getCountry = function() {
  return country;
};

/**
 * Gets the current language the gadget should use when rendering, returned as a
 * ISO 639-1 language code.
 *
 * @return {String} The language for this module instance
 */
gadgets.Prefs.prototype.getLang = function() {
  return language;
};

/**
 * Gets the module id for the current instance.
 *
 * @return {String | Number} The module id for this module instance
 */
gadgets.Prefs.prototype.getModuleId = function() {
  return moduleId;
};

})();
