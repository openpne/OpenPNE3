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

/*global gadgets,opensocial */

opensocial.Activity.MediaItem = opensocial.MediaItem;
opensocial.newActivityMediaItem = opensocial.newMediaItem;

opensocial.DataRequest.PersonId = opensocial.IdSpec.PersonId;

opensocial.DataRequest.Group = {
  OWNER_FRIENDS : 'OWNER_FRIENDS',
  VIEWER_FRIENDS : 'VIEWER_FRIENDS'
};

opensocial.DataRequest.prototype.newFetchPeopleRequest_v08
    = opensocial.DataRequest.prototype.newFetchPeopleRequest;
opensocial.DataRequest.prototype.newFetchPeopleRequest = function(idSpec,
    opt_params) {
  return this.newFetchPeopleRequest_v08(translateIdSpec(idSpec), opt_params);
};

opensocial.DataRequest.prototype.newFetchPersonAppDataRequest_v08
    = opensocial.DataRequest.prototype.newFetchPersonAppDataRequest;
opensocial.DataRequest.prototype.newFetchPersonAppDataRequest = function(idSpec,
    keys, opt_params) {
  return this.newFetchPersonAppDataRequest_v08(translateIdSpec(idSpec), keys,
      opt_params);
};

opensocial.DataRequest.prototype.newFetchActivitiesRequest_v08
    = opensocial.DataRequest.prototype.newFetchActivitiesRequest;
opensocial.DataRequest.prototype.newFetchActivitiesRequest = function(idSpec,
    opt_params) {
  var request
      = this.newFetchActivitiesRequest_v08(translateIdSpec(idSpec), opt_params);
  request.isActivityRequest = true;
  return request;
};

// TODO: handle making the last param valid json from any given string
// (is it already valid??)
// opensocial.DataRequest.prototype.newUpdatePersonAppDataRequest

opensocial.ResponseItem.prototype.getData_v08
    = opensocial.ResponseItem.prototype.getData;
opensocial.ResponseItem.prototype.getData = function() {
  var oldData = this.getData_v08();
  if (this.getOriginalDataRequest() && this.getOriginalDataRequest().isActivityRequest) {
    // The fetch activities request used to have an extra pointer to
    // the activities
    return {'activities' : oldData};
  }

  return oldData;
};

opensocial.Environment.ObjectType.ACTIVITY_MEDIA_ITEM
    = opensocial.Environment.ObjectType.MEDIA_ITEM;


opensocial.Person.prototype.getField_v08 = opensocial.Person.prototype.getField;
opensocial.Person.prototype.getField = function(key, opt_params) {
  var value =  this.getField_v08(key, opt_params);
  if (key === 'lookingFor' && value) {
    // The lookingFor field used to return a string instead of an enum
    var returnValue = new Array(value.length);
    for (var i = 0; i < value.length; i++) {
      returnValue[i] = value[i].getDisplayValue();
    }
    return returnValue.join();
  } else {
    return value;
  }
};


function translateIdSpec(oldIdSpec) {
  if (oldIdSpec == 'OWNER_FRIENDS') {
    return new opensocial.IdSpec({userId : 'OWNER', groupId : 'FRIENDS'});
  } else if (oldIdSpec == 'VIEWER_FRIENDS') {
    return new opensocial.IdSpec({userId : 'VIEWER', groupId : 'FRIENDS'});
  } else {
    return new opensocial.IdSpec({userId : oldIdSpec});
  }
};
