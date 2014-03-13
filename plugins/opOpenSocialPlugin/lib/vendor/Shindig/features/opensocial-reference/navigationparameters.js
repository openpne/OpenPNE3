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
 * @fileoverview Representation of navigation parameters for RequestShareApp.
 */


/**
 * @class
 * Parameters used by RequestShareApp to instruct the container on where to go
 * after the request is made.
 *
 *  It could be used, for example, to specify where viewers get routed
 *  in one of two cases:
 * 1) After a user gets a shareApp invitation or receives a message a gadget
 *     developer should be able to send that user to a context sensitive place.
 * 2) After a viewer actually shares an app with someone else the gadget
 *     developer should be able to redirect the viewer to a context sensitive
 *     place.
 *
 *
 * @name opensocial.NavigationParameters
 */


/**
 * Use this class to hold navigation parameters for RequestShareApp.
 *
 * For example, opensocial.newNavigationParameters({view : 'preview',
 *                                                  owner: 'xx',
 *                                                  parameters: {}).
 *
 * Private, see <a href="opensocial.html#newNavigationParameters">
 *              opensocial.newNavigationParameters()</a> for usage.
 *
 * @private
 * @constructor
 */
opensocial.NavigationParameters = function(opt_params) {
  this.fields_ = opt_params || {};
};


/**
 * @static
 * @class
 * All of the fields that NavigationParameters can have.
 *
 * <p>
 * <b>See also:</b>
 * <a
 * href="opensocial.NavigationParameters.html#getField">
 *         opensocial.NavigationParameters.getField()</a>
 * </p>
 *
 * @name opensocial.NavigationParameters.Field
 */
opensocial.NavigationParameters.Field = {
  /**
   * The <a href="gadgets.views.View.html">View</a> to navigate to.
   *
   * @member opensocial.NavigationParameters.Field
   */
  VIEW : 'view',

  /**
   * A string representing the owner id.
   *
   * @member opensocial.NavigationParameters.Field
   */
  OWNER : 'owner',

  /**
   * An optional list of parameters passed to the gadget once the new view,
   * with the new owner, has been loaded.
   *
   *
   * @member opensocial.NavigationParameters.Field
   */
  PARAMETERS : 'parameters'
};


/**
 * Gets the NavigationParameters' data that's associated with the specified key.
 *
 * @param {String} key The key to get data for;
 *     see the <a href="opensocial.NavigationParameters.Field.html">Field</a>
 *     class for possible values
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *     opt_params Additional
 *     <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *     to pass to the request.
 * @return {String} The data
 * @member opensocial.NavigationParameters
 */
opensocial.NavigationParameters.prototype.getField = function(key, opt_params) {
  return opensocial.Container.getField(this.fields_, key, opt_params);
};


/**
 * Sets data for this NavigationParameters associated with the given key.
 *
 * @param {String} key The key to set data for
 * @param {Object} data The data to set
 */
opensocial.NavigationParameters.prototype.setField = function(key, data) {
  return (this.fields_[key] = data);
};


/**
 * @static
 * @class
 *
 * The destinations available for navigation in
 * <a href="opensocial.html#requestShareApp">requestShareApp</a>
 * and <a href="opensocial.html#requestSendMessage">requestSendMessage</a>.
 *
 * @name opensocial.NavigationParameters.DestinationType
 */
opensocial.NavigationParameters.DestinationType = {
  /** @member opensocial.NavigationParameters.DestinationType */
  VIEWER_DESTINATION : "viewerDestination",

  /** @member opensocial.NavigationParameters.DestinationType  */
  RECIPIENT_DESTINATION : "recipientDestination"
};
