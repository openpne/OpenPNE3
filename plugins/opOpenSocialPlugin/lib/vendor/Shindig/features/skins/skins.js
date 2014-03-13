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
 * @fileoverview This library provides functions for getting skin information.
 */

var gadgets = gadgets || {};

/**
 * @static
 * @class Provides operations for getting display information about the
 *     currently shown skin.
 * @name gadgets.skins
 */
gadgets.skins = function() {
  var skinProperties = {};

  var requiredConfig = {
    "properties": gadgets.config.ExistsValidator
  };

  gadgets.config.register("skins", requiredConfig, function(config) {
        skinProperties = config["skins"].properties;
      });


  return {
    /**
     * Override the default properties with a new set of properties.
     *
     * @param {Object} properties The mapping of property names to values
     */
    init : function(properties) {
      skinProperties = properties;
    },

    /**
     * Fetches the display property mapped to the given key.
     *
     * @param {String} propertyKey The key to get data for;
     *    keys are defined in <a href="gadgets.skins.Property.html"><code>
     *    gadgets.skins.Property</code></a>
     * @return {String} The data
     *
     * @member gadgets.skins
     */
    getProperty : function(propertyKey) {
      return skinProperties[propertyKey] || "";
    }
  };
}();
/**
 * @static
 * @class
 * All of the display values that can be fetched and used in the gadgets UI.
 * These are the supported keys for the
 * <a href="gadgets.skins.html#getProperty">gadgets.skins.getProperty()</a>
 * method.
 * @name gadgets.skins.Property
 */
gadgets.skins.Property =  gadgets.util.makeEnum([
  /**
   * An image to use in the background of the gadget.
   * @member gadgets.skins.Property
   */
  'BG_IMAGE',

  /**
   * The color of the background of the gadget.
   * @member gadgets.skins.Property
   */
  'BG_COLOR',

  /**
   * The color that the main font should use.
   * @member gadgets.skins.Property
   */
  'FONT_COLOR',

  /**
   * The positioning of the background image
   * @member gadgets.skins.Property
   */
  'BG_POSITION',

  /**
   * The repeat characteristics for the background image
   * @member gadgets.skins.Property
   */
  'BG_REPEAT',

  /**
   * The color that anchor tags should use.
   * @member gadgets.skins.Property
   */
  'ANCHOR_COLOR'
]);
