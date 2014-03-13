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

/*global opensocial */

/**
 * @fileoverview Representation of an url.
 */


/**
 * @class
 * Base interface for all URL objects.
 *
 * @name opensocial.Url
 */


/**
 * Base interface for all url objects.
 *
 * @private
 * @constructor
 */
opensocial.Url = function(opt_params) {
  this.fields_ = opt_params || {};
};


/**
 * @static
 * @class
 * All of the fields that a url has. These are the supported keys for the
 * <a href="opensocial.Url.html#getField">Url.getField()</a> method.
 *
 * @name opensocial.Url.Field
 */
opensocial.Url.Field = {
  /**
   * The url number type or label. Examples: work, blog feed,
   * website, etc Specified as a String.
   *
   * @member opensocial.Url.Field
   */
  TYPE : 'type',

  /**
   * The text of the link. Specified as a String.
   *
   * @member opensocial.Url.Field
   */
  LINK_TEXT : 'linkText',

  /**
   * The address the url points to. Specified as a String.
   *
   * @member opensocial.Url.Field
   */
  ADDRESS : 'address'
};


/**
 * Gets data for this URL that is associated with the specified key.
 *
 * @param {String} key The key to get data for;
 *    keys are defined in <a href="opensocial.Url.Field.html"><code>
 *    Url.Field</code></a>
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *  opt_params Additional
 *    <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *    to pass to the request.
 * @return {String} The data
 */
opensocial.Url.prototype.getField = function(key, opt_params) {
  return opensocial.Container.getField(this.fields_, key, opt_params);
};
