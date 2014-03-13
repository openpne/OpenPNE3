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
 */

var opensocial = opensocial || {};

/**
 * @type {Object} The namespace declaration for this file.
 */
opensocial.data = opensocial.data || {};

/**
 * @type {Object} Global DataContext to contain requested data sets.
 */
opensocial.data.DataContext = function() {
  var listeners = [];
  var dataSets = {};

  /**
   * Puts a data set into the global DataContext object. Fires listeners
   * if they are satisfied by the associated key being inserted.
   *
   * @param {string} key The key to associate with this object.
   * @param {ResponseItem|Object} obj The data object.
   * @param {boolean} opt_fireListeners Default true.
   */
  var putDataSet = function(key, obj, opt_fireListeners) {
    if (typeof obj === 'undefined' || obj === null) {
      return;
    }
  
    dataSets[key] = obj;
    if (!(opt_fireListeners === false)) {
      fireCallbacks(key);
    }
  };
  
  /**
   * Registers a callback listener for a given set of keys.
   * @param {string|Array.<string>} keys Key or set of keys to listen on.
   * @param {Function(Array.<string>)} callback Function to call when a
   * listener is fired.
   * @param {booelan} oneTimeListener Remove this listener after first callback?
   * @param {boolean} fireIfReady Instantly fire this if all data is available?
   */
  var registerListener = function(keys, callback, oneTimeListener, fireIfReady) {
    var oneTime = !!oneTimeListener;
    var listener = {keys : {}, callback : callback, oneTime: oneTime};

    if (typeof keys === 'string') {
      listener.keys[keys] = true;
      if (keys != '*') {
        keys = [ keys ];
      }
    } else {
      for (var i = 0; i < keys.length; i++) {
        listener.keys[keys[i]] = true;
      }
    }
    
    listeners.push(listener);
  
    // Check to see if this one should fire immediately.
    if (fireIfReady && keys !== '*' && isDataReady(listener.keys)) {
      window.setTimeout(function() {
        maybeFireListener(listener, keys);
      }, 1);
    }
  };
  
  /**
   * Checks if the data for a map of keys is available.
   * @param {Object<string, ?>} An map of keys to check.
   * @return {boolean} Data for all the keys is present.
   */
  var isDataReady = function(keys) {
    if (keys['*']) {
      return true;
    }
    
    for (var key in keys) {
      if (typeof dataSets[key] === 'undefined') {
        return false;
      }
    }
    return true;
  };
    
  /**
   * Fires a listener for a key, but only if the data is ready for other
   * keys this listener is bound to.
   * @param {Object} listener The listener object.
   * @param {string} key The key that this listener is being fired for.
   */
  var maybeFireListener = function(listener, key) {
    if (isDataReady(listener.keys)) {
      listener.callback(key);
      if (listener.oneTime) {
        removeListener(listener);
      }
    }
  };    
  
  /**
   * Removes a listener from the list.
   * @param {Object} listener The listener to remove.
   */
  var removeListener = function(listener) {
    for (var i = 0; i < listeners.length; ++i) {
      if (listeners[i] == listener) {
        listeners.splice(i, 1);
        return;
      }
    }
  };
    
  /**
   * Scans all active listeners and fires off any callbacks that inserting this
   * key or list of keys satisfies.
   * @param {string|Array<string>} keys The key that was updated.
   * @private
   */
  var fireCallbacks = function(keys) {
    if (typeof(keys) == "string") {
      keys = [ keys ];
    }
    for (var i = 0; i < listeners.length; ++i) {
      var listener = listeners[i];
      for (var j = 0; j < keys.length; j++) {
        var key = keys[j];
        if (listener.keys[key] || listener.keys['*']) {
          maybeFireListener(listener, keys);
          break;
        }
      }
    }
  };


  return {
    
    /**
     * Returns a map of existing data.
     * @return {Object} A map of current data sets.
     * TODO: Add to the spec API?
     */
    getData : function() {
      var data = {};
      for (var key in dataSets) {
        if (dataSets.hasOwnProperty(key)) {
          data[key] = dataSets[key];
        }
      }
      return data;
    },
    
    /**
     * Registers a callback listener for a given set of keys.
     * @param {string|Array.<string>} keys Key or set of keys to listen on.
     * @param {Function(Array.<string>)} callback Function to call when a 
     * listener is fired.
     */
    registerListener : function(keys, callback) {
      registerListener(keys, callback, false, true);
    },
        
    /**
     * Private version of registerListener which allows one-time listeners to
     * be registered. Not part of the spec. Exposed because needed by 
     * opensocial-templates.
     * @param {string|Array.<string>} keys Key or set of keys to listen on.
     * @param {Function(Array.<string>)} callback Function to call when a 
     */
    registerOneTimeListener_ : function(keys, callback) {
      registerListener(keys, callback, true, true);
    },
    
    /**
     * Private version of registerListener which allows listeners to be
     * registered that do not fire initially, but only after a data change.
     * Exposed because needed by opensocial-templates.
     * @param {string|Array.<string>} keys Key or set of keys to listen on.
     * @param {Function(Array.<string>)} callback Function to call when a
     */
    registerDeferredListener_ : function(keys, callback) {
      registerListener(keys, callback, false, false);
    },
    
    /**
     * Retrieve a data set for a given key.
     * @param {string} key Key for the requested data set.
     * @return {Object} The data set object.
     */
    getDataSet : function(key) {
      return dataSets[key];
    },
        
    /**
     * Puts a data set into the global DataContext object. Fires listeners
     * if they are satisfied by the associated key being inserted.
     *
     * @param {string} key The key to associate with this object.
     * @param {ResponseItem|Object} obj The data object.
     */
    putDataSet : function(key, obj) {
      putDataSet(key, obj, true)
    }, 
    
    /**
     * Inserts multiple data sets from a JSON object.
     * @param {Object<string, Object>} dataSets a JSON object containing Data
     * sets keyed by Data Set Key. All the DataSets are added, before firing
     * listeners.
     */
    putDataSets : function(dataSets) {
      var keys = [];
      for (var key in dataSets) {
        keys.push(key);
        putDataSet(key, dataSets[key], false);
      }
      fireCallbacks(keys);
    }
  }
}();


/**
 * Accessor to the shared, global DataContext.
 */
opensocial.data.getDataContext = function() {
  return opensocial.data.DataContext;
};
