/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/*global opensocial, gadgets, shindig */
/*global JsonPerson, JsonActivity, JsonMediaItem, FieldTranslations */

/**
 * @fileoverview JSON-RPC based opensocial container.
 */

var JsonRpcContainer = function(configParams) {
  opensocial.Container.call(this);

  var path = configParams.path;
  // Path for social API calls
  this.path_ = path.replace("%host%", document.location.host);

  // Path for calls to invalidate
  var invalidatePath = configParams.invalidatePath;
  this.invalidatePath_ = invalidatePath.replace("%host%",
      document.location.host);

  var supportedFieldsArray = configParams.supportedFields;
  var supportedFieldsMap = {};
  for (var objectType in supportedFieldsArray) {
    if (supportedFieldsArray.hasOwnProperty(objectType)) {
      supportedFieldsMap[objectType] = {};
      for (var i = 0; i < supportedFieldsArray[objectType].length; i++) {
        var supportedField = supportedFieldsArray[objectType][i];
        supportedFieldsMap[objectType][supportedField] = true;
      }
    }
  }

  this.environment_ = new opensocial.Environment(configParams.domain,
      supportedFieldsMap);

  this.securityToken_ = shindig.auth.getSecurityToken();

  gadgets.rpc.register('shindig.requestShareApp_callback',
      JsonRpcContainer.requestShareAppCallback_);
};

(function() {
  var callbackIdStore = {};

  JsonRpcContainer.inherits(opensocial.Container);

  var JsonRpcRequestItem = function(rpc, opt_processData) {
    this.rpc = rpc;
    this.processData = opt_processData ||
                       function (rawJson) {
                         return rawJson;
                       };

    this.processResponse = function(originalDataRequest, rawJson, error, errorMessage) {
      var errorCode = error ? JsonRpcContainer.translateHttpError("Error " + error['code']) : null;
      return new opensocial.ResponseItem(originalDataRequest,
          error ? null : this.processData(rawJson), errorCode, errorMessage);
    };
  };

  JsonRpcContainer.prototype.getEnvironment = function() {
    return this.environment_;
  };

  JsonRpcContainer.prototype.requestShareApp = function(recipientIds, reason,
      opt_callback, opt_params) {
    var callbackId = "cId_" + Math.random();
    callbackIdStore[callbackId] = opt_callback;

    var body = gadgets.util.unescapeString(reason.getField(
        opensocial.Message.Field.BODY));

    if (!body || body.length === 0) {
      var bodyMsgKey = gadgets.util.unescapeString(reason.getField(
        opensocial.Message.Field.BODY_ID));
      body = gadgets.Prefs.getMsg(bodyMsgKey);
    }

    gadgets.rpc.call('..', 'shindig.requestShareApp',
        null,
        callbackId,
        recipientIds,
        body);
  };


  /**
   * Receives the returned results from the parent container.
   *
   * @param {boolean} success if false, the message will not be sent
   * @param {string} opt_errorCode an error code if success is false
   * @param {Array.<string>?} recipientIds an array of recipient IDs,
   *     if success is true
   * @private
   */
  JsonRpcContainer.requestShareAppCallback_ = function(callbackId,
      success, opt_errorCode, recipientIds) {
    callback = callbackIdStore[callbackId];
    if (callback) {
      callbackIdStore[callbackId] = null;

      var data = null;
      if (recipientIds) {
        data = {'recipientIds': recipientIds};
      }

      var responseItem = new opensocial.ResponseItem(null, data, opt_errorCode);
      callback(responseItem);
    }
  };


  JsonRpcContainer.prototype.requestCreateActivity = function(activity, priority,
      opt_callback) {
    opt_callback = opt_callback || function(){};

    var req = opensocial.newDataRequest();
    var viewer = new opensocial.IdSpec({'userId' : 'VIEWER'});
    req.add(this.newCreateActivityRequest(viewer, activity), 'key');
    req.send(function(response) {
      opt_callback(response.get('key'));
    });
  };

  JsonRpcContainer.prototype.requestData = function(dataRequest, callback) {
    callback = callback || function(){};

    var requestObjects = dataRequest.getRequestObjects();
    var totalRequests = requestObjects.length;

    if (totalRequests === 0) {
      window.setTimeout(function() {
        callback(new opensocial.DataResponse({}, true));
      }, 0);
      return;
    }

    var jsonBatchData = new Array(totalRequests);

    for (var j = 0; j < totalRequests; j++) {
      var requestObject = requestObjects[j];

      jsonBatchData[j] = requestObject.request.rpc;
      if (requestObject.key) {
        jsonBatchData[j].id = requestObject.key;
      }
    }

    var sendResponse = function(result) {
      if (result.errors[0]) {
        JsonRpcContainer.generateErrorResponse(result, requestObjects, callback);
        return;
      }

      result = result.data;

      var globalError = false;
      var responseMap = {};

      // Map from indices to ids.
      for (var i = 0; i < result.length; i++) {
        result[result[i].id] = result[i];
      }

      for (var k = 0; k < requestObjects.length; k++) {
        var request = requestObjects[k];
        var response = result[k];

        if (request.key && response.id !== request.key) {
          throw "Request key(" + request.key +
              ") and response id(" + response.id + ") do not match";
        }

        var rawData = response.data;
        var error = response.error;
        var errorMessage = "";

        if (error) {
          errorMessage = error.message;
        }

        var processedData = request.request.processResponse(
            request.request, rawData, error, errorMessage);
        globalError = globalError || processedData.hadError();
        if (request.key) {
          responseMap[request.key] = processedData;
        }
      }

      var dataResponse = new opensocial.DataResponse(responseMap, globalError);
      callback(dataResponse);
    };

    // TODO: get the jsonbatch url from the container config
    var makeRequestParams = {
      "CONTENT_TYPE" : "JSON",
      "METHOD" : "POST",
      "AUTHORIZATION" : "SIGNED",
      "POST_DATA" : gadgets.json.stringify(jsonBatchData)
    };

    var url = [this.path_];
    var token = shindig.auth.getSecurityToken();
    if (token) {
      url.push("?st=", encodeURIComponent(token));
    }

    this.sendRequest(url.join(''), sendResponse, makeRequestParams,
        "application/json");
  };

  JsonRpcContainer.prototype.sendRequest = function(relativeUrl, callback, params, contentType) {
    gadgets.io.makeNonProxiedRequest(relativeUrl, callback, params, contentType);
  };

  JsonRpcContainer.generateErrorResponse = function(result, requestObjects,
      callback) {
    var globalErrorCode =
            JsonRpcContainer.translateHttpError(result.errors[0]
                    || result.data.error)
                    || opensocial.ResponseItem.Error.INTERNAL_ERROR;

    var errorResponseMap = {};
    for (var i = 0; i < requestObjects.length; i++) {
      errorResponseMap[requestObjects[i].key] = new opensocial.ResponseItem(
          requestObjects[i].request, null, globalErrorCode);
    }
    callback(new opensocial.DataResponse(errorResponseMap, true));
  };

  JsonRpcContainer.translateHttpError = function(httpError) {
    if (httpError === "Error 501") {
      return opensocial.ResponseItem.Error.NOT_IMPLEMENTED;
    } else if (httpError === "Error 401") {
      return opensocial.ResponseItem.Error.UNAUTHORIZED;
    } else if (httpError === "Error 403") {
      return opensocial.ResponseItem.Error.FORBIDDEN;
    } else if (httpError === "Error 400") {
      return opensocial.ResponseItem.Error.BAD_REQUEST;
    } else if (httpError === "Error 500") {
      return opensocial.ResponseItem.Error.INTERNAL_ERROR;
    } else if (httpError === "Error 404") {
      return opensocial.ResponseItem.Error.BAD_REQUEST;
    } else if (httpError === "Error 417") {
      return opensocial.ResponseItem.Error.LIMIT_EXCEEDED;
    }
  };

  JsonRpcContainer.prototype.makeIdSpec = function(id) {
    return new opensocial.IdSpec({'userId' : id});
  };

  JsonRpcContainer.prototype.translateIdSpec = function(newIdSpec) {
    var userIds = newIdSpec.getField('userId');
    var groupId = newIdSpec.getField('groupId');

    // Upconvert to array for convenience
    if (!opensocial.Container.isArray(userIds)) {
      userIds = [userIds];
    }

    for (var i = 0; i < userIds.length; i++) {
      if (userIds[i] === 'OWNER') {
        userIds[i] = '@owner';
      } else if (userIds[i] === 'VIEWER') {
        userIds[i] = '@viewer';
      }
    }

    if (groupId === 'FRIENDS') {
      groupId = "@friends";
    } else if (groupId === 'SELF' || !groupId) {
      groupId = "@self";
    }

    return { userId : userIds, groupId : groupId};
  };

  JsonRpcContainer.prototype.newFetchPersonRequest = function(id, opt_params) {
    var peopleRequest = this.newFetchPeopleRequest(
        this.makeIdSpec(id), opt_params);

    var me = this;
    return new JsonRpcRequestItem(peopleRequest.rpc,
            function(rawJson) {
              return me.createPersonFromJson(rawJson, opt_params);
            });
  };

  JsonRpcContainer.prototype.newFetchPeopleRequest = function(idSpec,
      opt_params) {
    var rpc = { method : "people.get" };
    rpc.params = this.translateIdSpec(idSpec);

    FieldTranslations.addAppDataAsProfileFields(opt_params);
    FieldTranslations.translateStandardArguments(opt_params, rpc.params);
    FieldTranslations.translateNetworkDistance(idSpec, rpc.params);

    if (opt_params['profileDetail']) {
      FieldTranslations.translateJsPersonFieldsToServerFields(opt_params['profileDetail']);
      rpc.params.fields = opt_params['profileDetail'];
    }
    var me = this;
    return new JsonRpcRequestItem(rpc,
        function(rawJson) {
          var jsonPeople;
          if (rawJson['list']) {
            // For the array of people response
            jsonPeople = rawJson['list'];
          } else {
            // For the single person response
            jsonPeople = [rawJson];
          }

          var people = [];
          for (var i = 0; i < jsonPeople.length; i++) {
            people.push(me.createPersonFromJson(jsonPeople[i], opt_params));
          }
          return new opensocial.Collection(people,
              rawJson['startIndex'], rawJson['totalResults']);
        });
  };

  JsonRpcContainer.prototype.createPersonFromJson = function(serverJson, opt_params) {
    FieldTranslations.translateServerPersonToJsPerson(serverJson, opt_params);
    return new JsonPerson(serverJson);
  };

  JsonRpcContainer.prototype.getFieldsList = function(keys) {
    // datarequest.js guarantees that keys is an array
    if (this.hasNoKeys(keys) || this.isWildcardKey(keys[0])) {
      return [];
    } else {
      return keys;
    }
  };

  JsonRpcContainer.prototype.hasNoKeys = function(keys) {
    return !keys || keys.length === 0;
  };

  JsonRpcContainer.prototype.isWildcardKey = function(key) {
    // Some containers support * to mean all keys in the js apis.
    // This allows the RESTful apis to be compatible with them.
    return key === "*";
  };

  JsonRpcContainer.prototype.newFetchPersonAppDataRequest = function(idSpec, keys,
      opt_params) {
    var rpc = { method : "appdata.get" };
    rpc.params = this.translateIdSpec(idSpec);
    rpc.params.appId = "@app";
    rpc.params.fields = this.getFieldsList(keys);
    FieldTranslations.translateNetworkDistance(idSpec, rpc.params);

    return new JsonRpcRequestItem(rpc,
        function (appData) {
          return opensocial.Container.escape(appData, opt_params, true);
        });
  };

  JsonRpcContainer.prototype.newUpdatePersonAppDataRequest = function(key,
      value) {
    var rpc = { method : "appdata.update" };
    rpc.params = {userId: ["@viewer"], groupId: "@self"};
    rpc.params.appId = "@app";
    rpc.params.data = {};
    rpc.params.data[key] = value;
    rpc.params.fields = key;
    return new JsonRpcRequestItem(rpc);
  };

  JsonRpcContainer.prototype.newRemovePersonAppDataRequest = function(keys) {
    var rpc = { method : "appdata.delete" };
    rpc.params = {userId: ["@viewer"], groupId: "@self"};
    rpc.params.appId = "@app";
    rpc.params.fields = this.getFieldsList(keys);

    return new JsonRpcRequestItem(rpc);
  };

  JsonRpcContainer.prototype.newFetchActivitiesRequest = function(idSpec,
      opt_params) {
    var rpc = { method : "activities.get" };
    rpc.params = this.translateIdSpec(idSpec);
    rpc.params.appId = "@app";
    FieldTranslations.translateStandardArguments(opt_params, rpc.params);
    FieldTranslations.translateNetworkDistance(idSpec, rpc.params);

    return new JsonRpcRequestItem(rpc,
        function(rawJson) {
          rawJson = rawJson['list'];
          var activities = [];
          for (var i = 0; i < rawJson.length; i++) {
            activities.push(new JsonActivity(rawJson[i]));
          }
          return new opensocial.Collection(activities);
        });
  };

  JsonRpcContainer.prototype.newActivity = function(opt_params) {
    return new JsonActivity(opt_params, true);
  };

  JsonRpcContainer.prototype.newMediaItem = function(mimeType, url, opt_params) {
    opt_params = opt_params || {};
    opt_params['mimeType'] = mimeType;
    opt_params['url'] = url;
    return new JsonMediaItem(opt_params);
  };

  JsonRpcContainer.prototype.newCreateActivityRequest = function(idSpec,
      activity) {
    var rpc = { method : "activities.create" };
    rpc.params = this.translateIdSpec(idSpec);
    rpc.params.appId = "@app";
    FieldTranslations.translateNetworkDistance(idSpec, rpc.params);
    rpc.params.activity = activity.toJsonObject();

    return new JsonRpcRequestItem(rpc);
  };

  JsonRpcContainer.prototype.invalidateCache = function() {
    var rpc = { method : "cache.invalidate" };
    var invalidationKeys = { invalidationKeys : ["@viewer"] };
    rpc.params = invalidationKeys;

    var makeRequestParams = {
      "CONTENT_TYPE" : "JSON",
      "METHOD" : "POST",
      "AUTHORIZATION" : "SIGNED",
      "POST_DATA" : gadgets.json.stringify(rpc)
    };

    var url = [this.invalidatePath_];
    var token = shindig.auth.getSecurityToken();
    if (token) {
      url.push("?st=", encodeURIComponent(token));
    }

    this.sendRequest(url.join(''), null, makeRequestParams,
        "application/json");
  };

})();

JsonRpcContainer.prototype.newMessage = function(body, opt_params) {
  return new JsonMessage(body, opt_params);
};

JsonRpcContainer.prototype.newMessageCollection = function(opt_params) {
  return new JsonMessageCollection(opt_params);
};

JsonRpcContainer.prototype.newFetchMessageCollectionsRequest = function(idSpec, opt_params) {
  var rpc = { method : "messages.get" };
  rpc.params = this.translateIdSpec(idSpec);

  return new JsonRpcRequestItem(rpc,
      function(rawJson) {
        rawJson = rawJson['list'];
        var messagecollections = [];
        for (var i = 0; i < rawJson.length; i++) {
          messagecollections.push(new JsonMessageCollection(rawJson[i]));
        }
        return new opensocial.Collection(messagecollections);
      });
};

JsonRpcContainer.prototype.newFetchMessagesRequest = function(idSpec, msgCollId, opt_params) {
  var rpc = { method : "messages.get" };
  rpc.params = this.translateIdSpec(idSpec);
  rpc.params.msgCollId = msgCollId;

  return new JsonRpcRequestItem(rpc,
      function(rawJson) {
        rawJson = rawJson['list'];
        var messages = [];
        for (var i = 0; i < rawJson.length; i++) {
          messages.push(new JsonMessage(rawJson[i]));
        }
        return new opensocial.Collection(messages);
      });
};


var JsonRpcRequestItem = function(rpc, opt_processData) {
  this.rpc = rpc;
  this.processData = opt_processData ||
                     function (rawJson) {
                       return rawJson;
                     };

  this.processResponse = function(originalDataRequest, rawJson, error,
      errorMessage) {
    var errorCode = error
      ? JsonRpcContainer.translateHttpError("Error " + error['code'])
      : null;
    return new opensocial.ResponseItem(originalDataRequest,
        error ? null : this.processData(rawJson), errorCode, errorMessage);
  };
};
