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

var osapi = osapi || {};
(function() {

  /**
   * Called by the transports for each service method that they expose
   * @param method  The method to expose e.g. "people.get"
   * @param transport The transport used to execute a call for the method
   */
  osapi._registerMethod = function (method, transport) {
    var parts = method.split(".");
    var last = osapi;
    for (var i = 0; i < parts.length - 1; i++) {
      last[parts[i]] = last[parts[i]] || {};
      last = last[parts[i]];
    }

    // Use the batch as the actual executor of calls.
    var apiMethod = function(rpc) {
      var batch = osapi.newBatch();
      var boundCall = {};
      boundCall.execute = function(callback) {
        batch.add(method, this);
        batch.execute(function(batchResult) {
          if (batchResult.error) {
            callback(batchResult.error);
          } else {
            callback(batchResult[method]);
          }
        });
      }

      // TODO: This shouldnt really be necessary. The spec should be clear enough about
      // defaults that we dont have to populate this.
      rpc = rpc || {};
      rpc.userId = rpc.userId || "@viewer";
      rpc.groupId = rpc.groupId || "@self";

      // Decorate the execute method with the information necessary for batching
      boundCall.method = method;
      boundCall.transport = transport;
      boundCall.rpc = rpc;

      return boundCall;
    };

    if (last[parts[parts.length - 1]]) {
      gadgets.warn("Duplicate osapi method definition " + method);
    }
    last[parts[parts.length - 1]] = apiMethod;
  }

})();
