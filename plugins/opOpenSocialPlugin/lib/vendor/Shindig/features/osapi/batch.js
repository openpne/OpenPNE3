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
   * It is common to batch requests together to make them more efficient.
   *
   * Note: the container config specified endpoints at which services are to be found.
   * When creating a batch, the calls are split out into separate requests based on the
   * transport, as it may get sent to a different server on the backend.
   */
  var newBatch = function() {
    var that = {};

    // An array of requests where each request is
    // { key : <key>
    //   request : {
    //     method : <service-method>
    //     rpc  : <request params>
    //     transport : <rpc dispatcher>
    //  }
    // }
    var keyedRequests = [];

    /**
     * Create a new request in the batch
     * @param {string} key id for the request
     * @param {object} request the opensocial request object which is of the form
     * { method : <service-method>
     *   rpc  : <request>
     *   transport : <rpc dispatcher>
     * }
     */
    var add = function(key, request) {
      if (request && key) {
        keyedRequests.push({"key" : key, "request" : request});
        return that;
      }
    };

    /**
     * Convert our internal request format into a JSON-RPC
     * @param request
     */
    var toJsonRpc = function(request) {
      var jsonRpc = {method : request.request.method, id : request.key};
      if (request.request.rpc) {
        jsonRpc.params = request.request.rpc;
      }
      return jsonRpc;
    };

    /**
     * Call to make a batch execute its requests. Batch will distribute calls over their
     * bound transports and then merge them before calling the userCallback. If the result
     * of an rpc is another rpc request then it will be chained and executed.
     *
     * @param {Function} userCallback the callback to the gadget where results are passed.
     */
    var execute =  function(userCallback) {
      var batchResult = {};

      var perTransportBatch = {};

      // Break requests into their per-transport batches in call order
      var latchCount = 0;
      var transports = [];
      for (var i = 0; i < keyedRequests.length; i++) {
        // Batch requests per-transport
        var transport = keyedRequests[i].request.transport;
        if (!perTransportBatch[transport.name]) {
          transports.push(transport);
          latchCount++;
        }
        perTransportBatch[transport.name] = perTransportBatch[transport.name] || [];

        // Transform the request into JSON-RPC form before sending to the transport
        perTransportBatch[transport.name].push(toJsonRpc(keyedRequests[i]));
      }

      // Define callback for transports
      var transportCallback = function(transportBatchResult) {
        if (transportBatchResult.error) {
          batchResult.error = transportBatchResult.error;
        }
        // Merge transport results into overall result and hoist data.
        // All transport results are required to be of the format
        // { <key> : <JSON-RPC response>, ...}
        for (var i = 0; i < keyedRequests.length; i++) {
          var key = keyedRequests[i].key;
          var response = transportBatchResult[key];
          if (response) {
            if (response.error) {
              // No need to hoist error responses
              batchResult[key] = response;
            } else {
              // Handle both compliant and non-compliant JSON-RPC data responses.
              batchResult[key] = response.data || response.result;
            }
          }
        }

        // Latch on no. of transports before calling user callback
        latchCount--;
        if (latchCount === 0) {
          userCallback(batchResult);
        }
      };

      // For each transport execute its local batch of requests
      for (var j = 0; j < transports.length; j++) {
        transports[j].execute(perTransportBatch[transports[j].name], transportCallback);
      }

      // Force the callback to occur asynchronously even if there were no actual calls
      if (latchCount == 0) {
        window.setTimeout(function(){userCallback(batchResult)}, 0);
      }
    };

    that.execute = execute;
    that.add = add;
    return that;
  };

  osapi.newBatch = newBatch;
})();
