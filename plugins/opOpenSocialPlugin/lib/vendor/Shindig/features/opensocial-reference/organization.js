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
 * @fileoverview Representation of a organization.
 */


/**
 * @class
 * Base interface for all organization objects.
 *
 * @name opensocial.Organization
 */


/**
 * Base interface for all organization objects.
 *
 * @private
 * @constructor
 */
opensocial.Organization = function(opt_params) {
  this.fields_ = opt_params || {};
};


/**
 * @static
 * @class
 * All of the fields that a organization has. These are the supported keys for
 * the <a href="opensocial.Organization.html#getField">
 * Organization.getField()</a> method.
 *
 * @name opensocial.Organization.Field
 */
opensocial.Organization.Field = {
  /**
   * The name of the organization. For example, could be a school name or a job
   * company. Specified as a string.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  NAME : 'name',

  /**
   * The title or role the person has in the organization, specified as a
   * string. This could be graduate student, or software engineer.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  TITLE : 'title',

  /**
   * A description or notes about the person's work in the organization,
   * specified as a string. This could be the courses taken by a student, or a
   * more detailed description about a Organization role.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  DESCRIPTION : 'description',

  /**
   * The field the organization is in, specified as a string. This could be the
   * degree pursued if the organization is a school.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  FIELD : 'field',

  /**
   * The subfield the Organization is in, specified as a string.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  SUB_FIELD : 'subField',

  /**
   * The date the person started at the organization, specified as a Date.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  START_DATE : 'startDate',

  /**
   * The date the person stopped at the organization, specified as a Date.
   * A null date indicates that the person is still involved with the
   * organization.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  END_DATE : 'endDate',

 /**
   * The salary the person receieves from the organization, specified as a
   * string.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  SALARY : 'salary',

 /**
   * The address of the organization, specified as an opensocial.Address.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  ADDRESS : 'address',

 /**
   * A webpage related to the organization, specified as a string.
   * Not supported by all containers.
   * @member opensocial.Organization.Field
   */
  WEBPAGE : 'webpage'
};


/**
 * Gets data for this body type that is associated with the specified key.
 *
 * @param {String} key The key to get data for;
 *    keys are defined in <a href="opensocial.Organization.Field.html"><code>
 *    Organization.Field</code></a>
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *  opt_params Additional
 *    <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *    to pass to the request
 * @return {String} The data
 */
opensocial.Organization.prototype.getField = function(key, opt_params) {
  return opensocial.Container.getField(this.fields_, key, opt_params);
};
