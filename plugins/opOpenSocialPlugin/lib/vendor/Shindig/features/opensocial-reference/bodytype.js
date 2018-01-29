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
 * @fileoverview Representation of a body type.
 */


/**
 * @class
 * Base interface for all body type objects.
 *
 * @name opensocial.BodyType
 */


/**
 * Base interface for all body type objects.
 *
 * @private
 * @constructor
 */
opensocial.BodyType = function(opt_params) {
  this.fields_ = opt_params || {};
};


/**
 * @static
 * @class
 * All of the fields that a body type has. These are the supported keys for the
 * <a href="opensocial.BodyType.html#getField">BodyType.getField()</a>
 * method.
 *
 * @name opensocial.BodyType.Field
 */
opensocial.BodyType.Field = {
  /**
   * The build of the person's body, specified as a string.
   * Not supported by all containers.
   * @member opensocial.BodyType.Field
   */
  BUILD : 'build',

  /**
   * The height of the person in meters, specified as a number.
   * Not supported by all containers.
   * @member opensocial.BodyType.Field
   */
  HEIGHT : 'height',

  /**
   * The weight of the person in kilograms, specified as a number.
   * Not supported by all containers.
   * @member opensocial.BodyType.Field
   */
  WEIGHT : 'weight',

  /**
   * The eye color of the person, specified as a string.
   * Not supported by all containers.
   * @member opensocial.BodyType.Field
   */
  EYE_COLOR : 'eyeColor',

  /**
   * The hair color of the person, specified as a string.
   * Not supported by all containers.
   * @member opensocial.BodyType.Field
   */
  HAIR_COLOR : 'hairColor'
};


/**
 * Gets data for this body type that is associated with the specified key.
 *
 * @param {String} key The key to get data for;
 *    keys are defined in <a href="opensocial.BodyType.Field.html"><code>
 *    BodyType.Field</code></a>
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *  opt_params Additional
 *    <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *    to pass to the request.
 * @return {String} The data
 */
opensocial.BodyType.prototype.getField = function(key, opt_params) {
  return opensocial.Container.getField(this.fields_, key, opt_params);
};
