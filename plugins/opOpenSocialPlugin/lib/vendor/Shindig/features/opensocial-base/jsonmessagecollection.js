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
 * Base interface for json-based message objects.
 *
 * @private
 * @constructor
 */
var JsonMessageCollection = function(opt_params) {
  opt_params = opt_params || {};
  opensocial.MessageCollection.call(this, opt_params);
};
JsonMessageCollection.inherits(opensocial.MessageCollection);

JsonMessageCollection.prototype.toJsonObject = function() {
  return JsonMessageCollection.copyFields(this.fields_);
};

// TODO: Pull this method into a common class, it is from jsonperson.js
//JsonMessage.constructArrayObject = function(map, fieldName, className) {
//  var fieldValue = map[fieldName];
//  if (fieldValue) {
//    for (var i = 0; i < fieldValue.length; i++) {
//      fieldValue[i] = new className(fieldValue[i]);
//    }
//  }
//}

// TODO: Pull into common class as well
JsonMessageCollection.copyFields = function(oldObject) {
  var newObject = {};
  for (var field in oldObject) {
    newObject[field] = oldObject[field];
  }
  return newObject;
};


