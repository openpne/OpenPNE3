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
var JsonMessage = function(body, opt_params) {
  opt_params = opt_params || {};
  opensocial.Message.call(this, body, opt_params);
};
JsonMessage.inherits(opensocial.Message);

JsonMessage.prototype.toJsonObject = function() {
  return JsonMessage.copyFields(this.fields_);
};

// TODO: Pull into common class as well
JsonMessage.copyFields = function(oldObject) {
  var newObject = {};
  for (var field in oldObject) {
    newObject[field] = oldObject[field];
  }
  return newObject;
};


