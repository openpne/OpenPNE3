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
 * Base interface for json based person objects.
 *
 * @private
 * @constructor
 */
var JsonActivity = function(opt_params, opt_skipConversions) {
  opt_params = opt_params || {};
  if (!opt_skipConversions) {
    JsonActivity.constructArrayObject(opt_params, "mediaItems", JsonMediaItem);
  }
  opensocial.Activity.call(this, opt_params);
};
JsonActivity.inherits(opensocial.Activity);

JsonActivity.prototype.toJsonObject = function() {
  var jsonObject = JsonActivity.copyFields(this.fields_);

  var oldMediaItems = jsonObject['mediaItems'] || [];
  var newMediaItems = [];
  for (var i = 0; i < oldMediaItems.length; i++) {
    newMediaItems[i] = oldMediaItems[i].toJsonObject();
  }
  jsonObject['mediaItems'] = newMediaItems;

  return jsonObject;
};


// TODO: Split into separate class
var JsonMediaItem = function(opt_params) {
  opensocial.MediaItem.call(this, opt_params['mimeType'],
      opt_params['url'], opt_params);
};
JsonMediaItem.inherits(opensocial.MediaItem);

JsonMediaItem.prototype.toJsonObject = function() {
  return JsonActivity.copyFields(this.fields_);
};


// TODO: Pull this method into a common class, it is from jsonperson.js
JsonActivity.constructArrayObject = function(map, fieldName, className) {
  var fieldValue = map[fieldName];
  if (fieldValue) {
    for (var i = 0; i < fieldValue.length; i++) {
      fieldValue[i] = new className(fieldValue[i]);
    }
  }
};

// TODO: Pull into common class as well
JsonActivity.copyFields = function(oldObject) {
  var newObject = {};
  for (var field in oldObject) {
    newObject[field] = oldObject[field];
  }
  return newObject;
};
