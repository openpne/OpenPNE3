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

/**
 * @fileoverview This library augments gadets.window with functionality
 * to set the title of a gadget dynamically.
 */

var gadgets = gadgets || {};

/* NOTE: no class comment because one already exists in dynamic-height */
gadgets.window = gadgets.window || {};

/**
 * Sets the gadget title.
 * @param {String} title The preferred title.
 * @scope gadgets.window
 */
gadgets.window.setTitle = function(title) {
  gadgets.rpc.call(null, "set_title", null, title);
};

// Alias for legacy code
var _IG_SetTitle = gadgets.window.setTitle;

