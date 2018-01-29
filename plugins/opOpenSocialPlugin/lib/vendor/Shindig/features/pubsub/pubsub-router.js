/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations under the License.
 */

/**
 * @fileoverview Container-side message router for PubSub, a gadget-to-gadget
 * communication library.
 */

var gadgets = gadgets || {};

/**
 * @static
 * @class Routes PubSub messages.
 * @name gadgets.pubsubrouter
 */
gadgets.pubsubrouter = function() {
  var gadgetIdToSpecUrl;
  var subscribers = {};
  var onSubscribe;
  var onUnsubscribe;
  var onPublish;

  function router(command, channel, message) {
    var gadgetId = this.f;
    var sender = gadgetIdToSpecUrl(gadgetId);
    if (sender) {
      switch (command) {
      case 'subscribe':
        if (onSubscribe && onSubscribe(gadgetId, channel)) {
          break;
        }
        if (!subscribers[channel]) {
          subscribers[channel] = {};
        }
        subscribers[channel][gadgetId] = true;
        break;
      case 'unsubscribe':
        if (onUnsubscribe && onUnsubscribe(gadgetId, channel)) {
          break;
        }
        if (subscribers[channel]) {
          delete subscribers[channel][gadgetId];
        }
        break;
      case 'publish':
        if (onPublish && onPublish(gadgetId, channel, message)) {
          break;
        }
        var channelSubscribers = subscribers[channel];
        if (channelSubscribers) {
          for (var subscriber in channelSubscribers) {
            gadgets.rpc.call(subscriber, 'pubsub', null, channel, sender, message);
          }
        }
        break;
      default:
        throw new Error('Unknown pubsub command');
      }
    }
  }

  return /** @scope gadgets.pubsubrouter */ {
    /**
     * Initializes the PubSub message router.
     * @param {function} gadgetIdToSpecUrlHandler Function that returns the full
     *                   gadget spec URL of a given gadget id. For example:
     *                   function(id) { return idToUrlMap[id]; }
     * @param {object} opt_callbacks Optional event handlers. Supported handlers:
     *                 opt_callbacks.onSubscribe: function(gadgetId, channel)
     *                   Called when a gadget tries to subscribe to a channel.
     *                 opt_callbacks.onUnsubscribe: function(gadgetId, channel)
     *                   Called when a gadget tries to unsubscribe from a channel.
     *                 opt_callbacks.onPublish: function(gadgetId, channel, message)
     *                   Called when a gadget tries to publish a message.
     *                 All these event handlers may reject a particular PubSub
     *                 request by returning true.
     */
    init: function(gadgetIdToSpecUrlHandler, opt_callbacks) {
      if (typeof gadgetIdToSpecUrlHandler !== 'function') {
        throw new Error('Invalid handler');
      }
      if (typeof opt_callbacks === 'object') {
        onSubscribe = opt_callbacks.onSubscribe;
        onUnsubscribe = opt_callbacks.onUnsubscribe;
        onPublish = opt_callbacks.onPublish;
      }
      gadgetIdToSpecUrl = gadgetIdToSpecUrlHandler;
      gadgets.rpc.register('pubsub', router);
    }
  };
}();

