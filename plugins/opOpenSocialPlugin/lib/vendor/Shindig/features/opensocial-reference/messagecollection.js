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
 * @fileoverview Representation of a message.
 */


/**
 * @class
 * Base interface for all message collection objects.
 *
 * <p>
 *
 * @name opensocial.MessageCollection
 */


/**
 * Base interface for all message collection objects.
 *
 * @param {String} body The main text of the message.
 * @param {Map.<opensocial.MessageCollection.Field, Object>} opt_params Any other
 *    fields that should be set on the message object. All of the defined
 *    Fields are supported.
 * @private
 * @constructor
 */
opensocial.MessageCollection = function(opt_params) {
  this.fields_ = opt_params || {};
};


/**
 * @static
 * @class
 * All of the fields that message collections can have.
 *
 * <p>
 * <b>See also:</b>
 * <a
 * href="opensocial.MessageCollection.html#getField">opensocial.MessageCollection.getField()</a>
 * </p>
 *
 * @name opensocial.MessageCollection.Field
 */
opensocial.MessageCollection.Field = {
  /**
   * The Unique ID of this message collection.
   * @member opensocial.MessageCollection.Field
   */
  ID: 'id',

  /**
   * The title of the message collection. 
   * @member opensocial.MessageCollection.Field
   */
  TITLE : 'title',

  /**
   * The total number of messages in this collection.
   * @member opensocial.MessageCollection.Field
   */
  TOTAL : 'total',

  /**
   * The total number of unread messages in this collection
   * @member opensocial.MessageCollection.Field
   */
  UNREAD : 'unread',

  /**
   * The updated timestamp for this collection
   * @member opensocial.MessageCollection.Field
   */
  UPDATED : 'updated',

  /**
   * Urls associated with this collection
   * @member opensocial.MessageCollection.Field
   */
   URLS : 'urls'
};


/**
 * Gets the message data that's associated with the specified key.
 *
 * @param {String} key The key to get data for;
 *   see the <a href="opensocial.MessageCollection.Field.html">Field</a> class
 * for possible values
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *  opt_params Additional
 *    <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *    to pass to the request.
 * @return {String} The data
 * @member opensocial.MessageCollection
 */
opensocial.MessageCollection.prototype.getField = function(key, opt_params) {
  return opensocial.Container.getField(this.fields_, key, opt_params);
};


/**
 * Sets data for this message associated with the given key.
 *
 * @param {String} key The key to set data for
 * @param {String} data The data to set
 */
opensocial.MessageCollection.prototype.setField = function(key, data) {
  return this.fields_[key] = data;
};
