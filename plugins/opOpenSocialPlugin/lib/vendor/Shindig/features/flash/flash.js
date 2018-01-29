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

/*global ActiveXObject */

/**
 * @fileoverview This library provides a standard and convenient way to embed
 * Flash content into gadgets.
 */

var gadgets = gadgets || {};

/**
 * @static
 * @class Embeds Flash content in gadgets.
 * @name gadgets.flash
 */
gadgets.flash = gadgets.flash || {};

/**
 * Detects Flash Player and its major version.
 * @return {Number} The major version of Flash Player
 *                  or 0 if Flash is not supported.
 *
 * @member gadgets.flash
 */
gadgets.flash.getMajorVersion = function() {
  var flashMajorVersion = 0;
  if (navigator.plugins && navigator.mimeTypes && navigator.mimeTypes.length) {
    // Flash detection for browsers using Netscape's plugin architecture
    var i = navigator.plugins["Shockwave Flash"];
    if (i && i.description) {
      flashMajorVersion = parseInt(i.description.match(/[0-9]+/)[0], 10);
    }
  } else {
    // Flash detection for IE
    // This is done by trying to create an ActiveX object with the name
    // "ShockwaveFlash.ShockwaveFlash.{majorVersion}".
    for (var version = 10; version > 0; version--) {
      try {
        var dummy = new ActiveXObject("ShockwaveFlash.ShockwaveFlash." + version);
        return version;
      } catch (e) {
      }
    }
  }
  return flashMajorVersion;
};

gadgets.flash.swfContainerId_ = 0;

/**
 * Injects a Flash file into the DOM tree.
 * @param {String} swfUrl SWF URL.
 * @param {String | Object} swfContainer The id or object reference of an
 *     existing html container element.
 * @param {Number} swfVersion Minimal Flash Player version required.
 * @param {Object} opt_params An optional object that may contain any valid html
 *     parameter. All attributes will be passed through to the flash movie on
 *     creation.
 * @return {Boolean} Whether the function call completes successfully.
 *
 * @member gadgets.flash
 */
gadgets.flash.embedFlash = function(swfUrl, swfContainer, swfVersion,
    opt_params) {
  switch (typeof swfContainer) {
    case 'string':
      swfContainer = document.getElementById(swfContainer);
    case 'object':
      if (swfContainer && (typeof swfContainer.innerHTML === 'string')) {
        break;
      }
    default:
      return false;
  }

  switch (typeof opt_params) {
    case 'undefined':
      opt_params = {};
    case 'object':
      break;
    default:
      return false;
  }

  if (swfUrl.indexOf('//') == 0) {
    swfUrl = document.location.protocol + swfUrl;
  }
  
  var ver = gadgets.flash.getMajorVersion();
  if (ver) {
    var swfVer = parseInt(swfVersion, 10);
    if (isNaN(swfVer)) {
      swfVer = 0;
    }
    if (ver >= swfVer) {
      // Set default size
      if (!opt_params.width) {
        opt_params.width = '100%';
      }
      if (!opt_params.height) {
        opt_params.height = '100%';
      }
      // Set the default "base" attribute
      if (typeof opt_params.base !== 'string') {
        opt_params.base = swfUrl.match(/^[^?#]+\//)[0];
      }
      // Set wmode to "opaque" if it's not defined. The default value
      // "window" is undesirable because browsers will render Flash
      // on top of other html elements.
      if (typeof opt_params.wmode !== 'string') {
        opt_params.wmode = 'opaque';
      }
      while (!opt_params.id) {
        var newId = 'swfContainer' + gadgets.flash.swfContainerId_++;
        if (!document.getElementById(newId)) {
          opt_params.id = newId;
        }
      }
      // Prepare html snippet
      var html;
      if (navigator.plugins && navigator.mimeTypes &&
          navigator.mimeTypes.length) {
        // Use <embed> tag for Netscape and Mozilla browsers
        opt_params.type = 'application/x-shockwave-flash';
        opt_params.src = swfUrl;

        html = '<embed';
        for (var prop in opt_params) {
          if (!/^swf_/.test(prop)) {
            html += ' ' + prop + '="' + opt_params[prop] + '"';
          }
        }
        html += ' /></embed>';
      } else {
        // Use <object> tag for IE
        opt_params.movie = swfUrl;
        var attr = {
          width: opt_params.width,
          height: opt_params.height,
          classid: "clsid:D27CDB6E-AE6D-11CF-96B8-444553540000"
        };
        if (opt_params.id) {
          attr.id = opt_params.id;
        }

        html = '<object';
        for (var attrProp in attr) {
          html += ' ' + attrProp + '="' + attr[attrProp] + '"';
        }
        html += '>';
        for (var paramsProp in opt_params) {
          if (!/^swf_/.test(paramsProp) && !attr[paramsProp]) {
            html += '<param name="' + paramsProp +
              '" value="' + opt_params[paramsProp] + '" />';
          }
        }
        html += '</object>';
      }
      // Inject html
      swfContainer.innerHTML = html;
      return true;
    }
  }
  return false;
};

/**
 * Injects a cached Flash file into the DOM tree.
 * Accepts the same parameters as gadgets.flash.embedFlash does.
 * @return {Boolean} Whether the function call completes successfully.
 *
 * @member gadgets.flash
 */
gadgets.flash.embedCachedFlash = function() {
  var args = Array.prototype.slice.call(arguments);
  args[0] = gadgets.io.getProxyUrl(args[0]);
  return gadgets.flash.embedFlash.apply(this, args);
};

// Aliases for legacy code
var _IG_GetFlashMajorVersion = gadgets.flash.getMajorVersion;
var _IG_EmbedFlash = function(swfUrl, swfContainer, opt_params) {
  return gadgets.flash.embedFlash(swfUrl, swfContainer, opt_params.swf_version,
      opt_params);
};

var _IG_EmbedCachedFlash = function(swfUrl, swfContainer, opt_params) {
  return gadgets.flash.embedCachedFlash(swfUrl, swfContainer, opt_params.swf_version,
      opt_params);
};

