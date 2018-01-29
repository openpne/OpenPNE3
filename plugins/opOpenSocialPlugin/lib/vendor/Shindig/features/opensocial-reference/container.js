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
 * @fileoverview Interface for containers of people functionality.
 */


/**
 * Base interface for all containers.
 *
 * @constructor
 * @private
 */
opensocial.Container = function() {};


/**
 * The container instance.
 *
 * @type Container
 * @private
 */
opensocial.Container.container_ = null;


/**
 * Set the current container object.
 *
 * @param {opensocial.Container} container The container
 * @private
 */
opensocial.Container.setContainer = function(container) {
  opensocial.Container.container_ = container;
};


/**
 * Get the current container object.
 *
 * @return {opensocial.Container} container The current container
 * @private
 */
opensocial.Container.get = function() {
  return opensocial.Container.container_;
};


/**
 * Gets the current environment for this gadget. You can use the environment to
 * query things like what profile fields and surfaces are supported by this
 * container, what parameters were passed to the current gadget and so forth.
 *
 * @return {opensocial.Environment} The current environment
 *
 * @private
 */
opensocial.Container.prototype.getEnvironment = function() {};

/**
 * Requests the container to send a specific message to the specified users. If
 * the container does not support this method the callback will be called with a
 * opensocial.ResponseItem. The response item will have its error code set to
 * NOT_IMPLEMENTED.
 *
 * @param {Array.&lt;String&gt; | String} recipients An ID, array of IDs, or a
 *     group reference; the supported keys are VIEWER, OWNER, VIEWER_FRIENDS,
 *    OWNER_FRIENDS, or a single ID within one of those groups
 * @param {opensocial.Message} message The message to send to the specified
 *     users.
 * @param {Function} opt_callback The function to call once the request has been
 *    processed; either this callback will be called or the gadget will be
 *    reloaded from scratch. This function will be passed one parameter, an
 *    opensocial.ResponseItem. The error code will be set to reflect whether
 *    there were any problems with the request. If there was no error, the
 *    message was sent. If there was an error, you can use the response item's
 *    getErrorCode method to determine how to proceed. The data on the response
 *    item will not be set.
 *
 * @member opensocial
 * @private
 */
opensocial.Container.prototype.requestSendMessage = function(recipients,
    message, opt_callback, opt_params) {
    gadgets.rpc.call(null, "requestSendMessage", opt_callback, recipients,
        message, opt_callback, opt_params);
};


/**
 * Requests the container to share this gadget with the specified users. If the
 * container does not support this method the callback will be called with a
 * opensocial.ResponseItem. The response item will have its error code set to
 * NOT_IMPLEMENTED.
 *
 * @param {Array.&lt;String&gt; | String} recipients An ID, array of IDs, or a
 *     group reference; the supported keys are VIEWER, OWNER, VIEWER_FRIENDS,
 *    OWNER_FRIENDS, or a single ID within one of those groups
 * @param {opensocial.Message} reason The reason the user wants the gadget to
 *     share itself. This reason can be used by the container when prompting the
 *     user for permission to share the app. It may also be ignored.
 * @param {Function} opt_callback The function to call once the request has been
 *    processed; either this callback will be called or the gadget will be
 *    reloaded from scratch. This function will be passed one parameter, an
 *    opensocial.ResponseItem. The error code will be set to reflect whether
 *    there were any problems with the request. If there was no error, the
 *    sharing request was sent. If there was an error, you can use the response
 *    item's getErrorCode method to determine how to proceed. The data on the
 *    response item will not be set.
 *
 * @member opensocial
 * @private
 */
opensocial.Container.prototype.requestShareApp = function(recipients, reason,
    opt_callback, opt_params) {
  if (opt_callback) {
    window.setTimeout(function() {
      opt_callback(new opensocial.ResponseItem(
          null, null, opensocial.ResponseItem.Error.NOT_IMPLEMENTED, null));
    }, 0);
  }
};


/**
 * Request for the container to make the specified person not a friend.
 *
 * Note: If this is the first activity that has been created for the user and
 * the request is marked as HIGH priority then this call may open a user flow
 * and navigate away from your gadget.
 *
 * @param {Activity} activity The activity to create. The only required field is
 *     title.
 * @param {CreateActivityPriority} priority The priority for this request.
 * @param {Function} opt_callback Function to call once the request has been
 *    processed.
 * @private
 */
opensocial.Container.prototype.requestCreateActivity = function(activity,
    priority, opt_callback) {
  if (opt_callback) {
    window.setTimeout(function() {
      opt_callback(new opensocial.ResponseItem(
          null, null, opensocial.ResponseItem.Error.NOT_IMPLEMENTED, null));
    }, 0);
  }
};


/**
 * Returns whether the current gadget has access to the specified
 * permission.
 *
 * @param {opensocial.Permission | String} permission The permission
 * @return {Boolean} Whether the gadget has access for the permission.
 *
 * @private
 */
opensocial.Container.prototype.hasPermission = function(permission) {
  return false;
};


/**
 * Requests the user grants access to the specified permissions.
 *
 * @param {Array.<opensocial.Permission>} permissions The permissions to request
 *    access to from the viewer
 * @param {String} reason Will be displayed to the user as the reason why these
 *    permissions are needed.
 * @param {Function} opt_callback The function to call once the request has been
 *    processed. This callback will either be called or the gadget will be
 *    reloaded from scratch
 *
 * @private
 */
opensocial.Container.prototype.requestPermission = function(permissions, reason,
    opt_callback) {
  if (opt_callback) {
    window.setTimeout(function () {
      opt_callback(new opensocial.ResponseItem(
          null, null, opensocial.ResponseItem.Error.NOT_IMPLEMENTED, null));
    }, 0);
  }
};


/**
 * Calls the callback function with a dataResponse object containing the data
 * asked for in the dataRequest object.
 *
 * @param {opensocial.DataRequest} dataRequest Specifies which data to get from
 *    the server
 * @param {Function} callback Function to call after the data is fetched
 * @private
 */
opensocial.Container.prototype.requestData = function(dataRequest, callback) {};


/**
 * Request a profile for the specified person id.
 * When processed, returns a Person object.
 *
 * @param {String} id The id of the person to fetch. Can also be standard
 *    person IDs of VIEWER and OWNER.
 * @param {Map.<opensocial.DataRequest.PeopleRequestFields, Object>} opt_params
 *    Additional params to pass to the request. This request supports
 *    PROFILE_DETAILS.
 * @return {Object} a request object
 * @private
 */
opensocial.Container.prototype.newFetchPersonRequest = function(id,
    opt_params) {};


/**
 * Used to request friends from the server.
 * When processed, returns a Collection&lt;Person&gt; object.
 *
 * @param {opensocial.IdSpec} idSpec An IdSpec used to specify which people to
 *     fetch. See also <a href="opensocial.IdSpec.html">IdSpec</a>.
 * @param {Map.<opensocial.DataRequest.PeopleRequestFields, Object>} opt_params
 *    Additional params to pass to the request. This request supports
 *    PROFILE_DETAILS, SORT_ORDER, FILTER, FILTER_OPTIONS, FIRST, and MAX.
 * @return {Object} a request object
 * @private
 */
opensocial.Container.prototype.newFetchPeopleRequest = function(idSpec,
    opt_params) {};


/**
 * Used to request app data for the given people.
 * When processed, returns a Map&lt;person id, Map&lt;String, String&gt;&gt;
 * object.TODO: All of the data values returned will be valid json.
 *
 * @param {opensocial.IdSpec} idSpec An IdSpec used to specify which people to
 *     fetch. See also <a href="opensocial.IdSpec.html">IdSpec</a>.
 * @param {Array.<String> | String} keys The keys you want data for. This
 *     can be an array of key names, a single key name, or "*" to mean
 *     "all keys".
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *  opt_params Additional
 *    <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *    to pass to the request
 * @return {Object} a request object
 * @private
 */
opensocial.Container.prototype.newFetchPersonAppDataRequest = function(idSpec,
    keys, opt_params) {};


/**
 * Creates an item to request an update of an app field for the current VIEWER
 * When processed, does not return any data.
 * App Data is stored as a series of key value pairs of strings, scoped per
 * person, per application. Containers supporting this request SHOULD provide
 * at least 10KB of space per user per application for this data.
 *
 * @param {String} key The name of the key
 * @param {String} value The value
 * @return {Object} a request object
 * @private
 */
opensocial.Container.prototype.newUpdatePersonAppDataRequest = function(
    key, value) {};


/**
 * Deletes the given keys from the datastore for the current VIEWER.
 * When processed, does not return any data.
 *
 * @param {Array.&lt;String&gt; | String} keys The keys you want to delete from
 *     the datastore; this can be an array of key names, a single key name,
 *     or "*" to mean "all keys"
 * @return {Object} A request object
 * @private
 */
opensocial.Container.prototype.newRemovePersonAppDataRequest = function(
    keys) {};


/**
 * Used to request an activity stream from the server.
 *
 * When processed, returns a Collection&lt;Activity&gt;.
 *
 * @param {opensocial.IdSpec} idSpec An IdSpec used to specify which people to
 *     fetch. See also <a href="opensocial.IdSpec.html">IdSpec</a>.
 * @param {Map.<opensocial.DataRequest.ActivityRequestFields, Object>} opt_params
 *    Additional params to pass to the request.
 * @return {Object} a request object
 * @private
 */
opensocial.Container.prototype.newFetchActivitiesRequest = function(idSpec,
    opt_params) {};

opensocial.Container.prototype.newFetchMessageCollectionsRequest = function(idSpec, opt_params) {};
opensocial.Container.prototype.newFetchMessagesRequest = function(idSpec, msgCollId, opt_params) {};

/**
 * Creates a new collection with caja support if enabled.
 * @return {opensocial.Collection} the collection object
 * @private
 */
opensocial.Container.prototype.newCollection = function(array, opt_offset,
    opt_totalSize) {
  return new opensocial.Collection(array, opt_offset, opt_totalSize);
};


/**
 * Creates a new person with caja support if enabled.
 * @return {opensocial.Person} the person object
 * @private
 */
opensocial.Container.prototype.newPerson = function(opt_params, opt_isOwner,
    opt_isViewer) {
  return new opensocial.Person(opt_params, opt_isOwner, opt_isViewer);
};


/**
 * Get an activity object used to create activities on the server
 *
 * @param {opensocial.Activity.Template || String} title The title of an
 *     activity, a template is reccommended, but this field can also be a
 *     string.
 * @param {Map.<opensocial.Activity.Field, Object>} opt_params Any other
 *    fields that should be set on the activity object. All of the defined
 *    Fields are supported.
 * @return {opensocial.Activity} the activity object
 * @private
 */
opensocial.Container.prototype.newActivity = function(opt_params) {
  return new opensocial.Activity(opt_params);
};


/**
 * Creates a media item. Represents images, movies, and audio.
 * Used when creating activities on the server.
 *
 * @param {String} mimeType of the media
 * @param {String} url where the media can be found
 * @param {Map.<opensocial.MediaItem.Field, Object>} opt_params
 *    Any other fields that should be set on the media item object.
 *    All of the defined Fields are supported.
 *
 * @return {opensocial.MediaItem} the media item object
 * @private
 */
opensocial.Container.prototype.newMediaItem = function(mimeType, url,
    opt_params) {
  return new opensocial.MediaItem(mimeType, url, opt_params);
};


/**
 * Creates a media item associated with an activity.
 * Represents images, movies, and audio.
 * Used when creating activities on the server.
 *
 * @param {String} body The main text of the message.
 * @param {Map.&lt;opensocial.Message.Field, Object&gt;} opt_params
 *    Any other fields that should be set on the message object;
 *    all of the defined
 *    <a href="opensocial.Message.Field.html">Field</a>s
 *    are supported
 *
 * @return {opensocial.Message} The new
 *    <a href="opensocial.Message.html">message</a> object
 * @private
 */
opensocial.Container.prototype.newMessage = function(body, opt_params) {
  return new opensocial.Message(body, opt_params);
};


/**
 * Creates an IdSpec object.
 *
 * @param {Map.&lt;opensocial.IdSpec.Field, Object&gt;} parameters
 *    Parameters defining the id spec.
 * @return {opensocial.IdSpec} The new
 *     <a href="opensocial.IdSpec.html">IdSpec</a> object
 * @private
 */
opensocial.Container.prototype.newIdSpec = function(params) {
  return new opensocial.IdSpec(params);
};


/**
 * Creates a NavigationParameters object.
 *
 * @param {Map.&lt;opensocial.NavigationParameters.Field, Object&gt;} parameters
 *     Parameters defining the navigation
 * @return {opensocial.NavigationParameters} The new
 *     <a href="opensocial.NavigationParameters.html">NavigationParameters</a>
 *     object
 * @private
 */
opensocial.Container.prototype.newNavigationParameters = function(params) {
  return new opensocial.NavigationParameters(params);
};


/**
 * Creates a new response item with caja support if enabled.
 * @return {opensocial.ResponseItem} the response item object
 * @private
 */
opensocial.Container.prototype.newResponseItem = function(originalDataRequest,
    data, opt_errorCode, opt_errorMessage) {
  return new opensocial.ResponseItem(originalDataRequest, data, opt_errorCode,
      opt_errorMessage);
};


/**
 * Creates a new data response with caja support if enabled.
 * @return {opensocial.DataResponse} the data response object
 * @private
 */
opensocial.Container.prototype.newDataResponse = function(responseItems,
    opt_globalError) {
  return new opensocial.DataResponse(responseItems, opt_globalError);
};


/**
 * Get a data request object to use for sending and fetching data from the
 * server.
 *
 * @return {opensocial.DataRequest} the request object
 * @private
 */
opensocial.Container.prototype.newDataRequest = function() {
  return new opensocial.DataRequest();
};


/**
 * Get a new environment object.
 *
 * @return {opensocial.Environment} the environment object
 * @private
 */
opensocial.Container.prototype.newEnvironment = function(domain,
    supportedFields) {
  return new opensocial.Environment(domain, supportedFields);
};

/**
 * Invalidates all resources cached for the current viewer.
 */
opensocial.Container.prototype.invalidateCache = function() {
};

/**
 * Returns true if the specified value is an array
 * @param {Object} val Variable to test
 * @return {boolean} Whether variable is an array
 * @private
 */
opensocial.Container.isArray = function(val) {
  return val instanceof Array;
};


/**
 * Returns the value corresponding to the key in the fields map. Escapes
 * the value appropriately.
 * @param {Map<String, Object>} fields All of the values mapped by key.
 * @param {String} key The key to get data for.
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *  opt_params Additional
 *    <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *    to pass to the request.
 * @return {String} The data
 * @private
 */
opensocial.Container.getField = function(fields, key, opt_params) {
  var value = fields[key];
  return opensocial.Container.escape(value, opt_params, false);
};

opensocial.Container.escape = function(value, opt_params, opt_escapeObjects) {
  if (opt_params && opt_params[opensocial.DataRequest.DataRequestFields.ESCAPE_TYPE] == opensocial.EscapeType.NONE) {
    return value;
  } else {
    return gadgets.util.escape(value, opt_escapeObjects);
  }
};


/**
 * Caja Support.  See features/caja/*.js
 */
var cajita;
var ___;
var attachDocumentStub;
// See features/caja/domita.js for uriCallback's contract.
var uriCallback = {
  rewrite: function rewrite(uri, mimeTypes) {
    uri = String(uri);
    // By default, only allow references to anchors.
    if (/^#/.test(uri)) {
      return '#' + encodeURIComponent(decodeURIComponent(uri.substring(1)));
    // and files on the same host
    } else if (/^\/(?:[^\/][^?#]*)?$/.test(uri)) {
      return encodeURI(decodeURI(uri));
    }
    // This callback can be replaced with one that passes the URL through
    // a proxy that checks the mimetype.
    return null;
  }
};

// Take a valija function and wrap it in a plain function so uncajoled
// code can call it.
// TODO(benl): what if we're called from cajita code??? In this case
// we want to do callback.CALL__() instead of $v.cf(callback). But how
// do we know?
function tameCallback($v, callback) {
  return callback && function tamedCallback() {
    return $v.cf(callback, Array.slice(arguments, 0));
  };
};

// Warning: multiple taming styles ahead...
var taming = {
/*
  flash: function() {
    return ___.frozenFunc(function(node, flashStreamer, flwidth, flwmode,
				   flvars) {
      node.node___.innerHTML = "<obj" + "ect id='flashbuddypoke' data='"+flashStreamer+"' height='500' width='"+flwidth+"' type='application/x-shockwave-flash'><param name='menu' value='false'/><param name='allowNetworking' value='all'/><param name='allowScriptAccess' value='always'/><param name='movie' value='"+flashStreamer+"'/><param name='movie' value='"+flashStreamer+"'/><param name='flashvars' value='"+flvars+"'/><param name='wmode' value='"+flwmode+"'/><param name='bgcolor' value='#FFFFFF'/></obj"+"ect>";
    });
  },
*/

  flash: {
    embedFlash: function(orig) {
      return ___.frozenFunc(function tamedEmbedFlash(swfUrl, swfContainer,
						     swfVersion, opt_params) {
	return orig.call(this, swfUrl, swfContainer.node___, swfVersion,
			 opt_params);
      });
    }
  },

  MiniMessage: function($vs) {
    var untamedMiniMessage = gadgets.MiniMessage;
    var tamedMiniMessage = function(opt_moduleId, opt_container) {
      this.mm_ = new untamedMiniMessage(opt_moduleId, opt_container);
    };

    tamedMiniMessage.prototype.createDismissibleMessage = function(message,
								 opt_callback) {
      message = html_sanitize(message);
      return this.mm_.createDismissibleMessage(message,
					       tameCallback($vs, opt_callback));
    };
    tamedMiniMessage.prototype.createStaticMessage = function(message,
							      opt_callback) {
      message = html_sanitize(message);
      return this.mm_.createStaticMessage(message,
					  tameCallback($vs, opt_callback));
    };
    tamedMiniMessage.prototype.createTimerMessage = function(message, seconds,
							     opt_callback) {
      message = html_sanitize(message);
      return this.mm_.createTimerMessage(message, seconds,
					 tameCallback($vs, opt_callback));
    };
    // FIXME: message should be a DOM element within our tree, other
    // than the root (dismissMessage deletes it).
    tamedMiniMessage.prototype.dismissMessage = function(message) {
      return this.mm_.dismissMessage(message);
    };
    return tamedMiniMessage;
  },

  newDataRequest: function($v, orig) {
    return function tamedNewDataRequest() {
      var dr = {
	super_: orig(),

	add: ___.frozenFunc(function(thing, str) {
	  return this.super_.add(thing, str);
	}),
	newFetchPersonAppDataRequest: ___.frozenFunc(function(person, what) {
	  return this.super_.newFetchPersonAppDataRequest(person, what);
	}),
	newFetchPersonRequest: ___.frozenFunc(function(person, opts) {
	  return this.super_.newFetchPersonRequest(person, opts);
	}),
	newFetchPeopleRequest: ___.frozenFunc(function(person, opts) {
	  return this.super_.newFetchPeopleRequest(person, opts);
	}),
	newUpdatePersonAppDataRequest: ___.frozenFunc(function(person, opts) {
	  return this.super_.newUpdatePersonAppDataRequest(person, opts);
	}),
	send: ___.frozenFunc(function(callback) {
	  return this.super_.send(tameCallback($v, callback));
	})
      };
      return dr;
    };
  },

  TabSet: function($v, orig) {
    var tamedTabSet = function(opt_moduleId, opt_defaultTab, opt_container) {
      this.ts_ = new orig(opt_moduleId, opt_defaultTab, opt_container);
    };

    tamedTabSet.prototype.addTab = function(tabName, opt_params) {
      // TODO(benl): tame the rest of opt_params
      if (opt_params) {
	opt_params.contentContainer = opt_params.contentContainer ?
	  undefined : ___.guard(blah) && opt_params.contentContainer.node___;
      }
      this.ts_.addTab(html_sanitize(tabName), opt_params);
    };

    tamedTabSet.prototype.alignTabs = function(align, opt_offset) {
      this.ts_.alignTabs(String(align), Number(opt_offset));
    };

    tamedTabSet.prototype.displayTabs = function(display) {
      this.ts_.displayTabs(Boolean(display));
    };

    return tamedTabSet;
  },

  util: {
    registerOnLoadHandler: function($v, orig) {
      return function tamedRegisterOnLoadHandler(callback) {
        orig(tameCallback($v, callback));
      };
    }
  },

  views: {
    // note, we are going to monkey-patch just this function instead of wrapping the whole of views...
    getCurrentView: function(orig) {
      return function tamedGetCurrentView() {
	// Note, taming decision was s_, so maybe we don't need this?
	var view = orig.call(this);
	___.grantGeneric(view, 'getName');
	___.grantGeneric(view, 'isOnlyVisibleGadget');
	return view;
      };
    }
  }
};

/**
 * Enable Caja support
 *
 * @type Container
 * @private
 */

// TODO(doll): As caja evolves this method should get a lot smaller
opensocial.Container.prototype.enableCaja = function() {

  ___ = window["___"];
  cajita = window["cajita"];
  valijaMaker = window["valijaMaker"];
  attachDocumentStub = window["attachDocumentStub"];

  var imports = ___.copy(___.sharedImports);
  imports.outers = imports;

  var gadgetRoot = document.createElement('div');
  gadgetRoot.className = 'g___';
  document.body.appendChild(gadgetRoot);

  imports.htmlEmitter___ = new HtmlEmitter(gadgetRoot);
  imports.getCssContainer___ = function () {
    return gadgetRoot;
  };

  attachDocumentStub('-g___', uriCallback, imports, gadgetRoot);

  imports.$v = valijaMaker.CALL___(imports.outers);

  ___.getNewModuleHandler().setImports(imports);

  // Taming
  if (gadgets.flash)
    gadgets.flash.embedFlash
      = taming.flash.embedFlash(gadgets.flash.embedFlash);
  gadgets.util.registerOnLoadHandler
    = taming.util.registerOnLoadHandler(imports.$v,
					gadgets.util.registerOnLoadHandler);
  if (gadgets.views)
    gadgets.views.getCurrentView
      = taming.views.getCurrentView(gadgets.views.getCurrentView);
  opensocial.newDataRequest = taming.newDataRequest(imports.$v,
						    opensocial.newDataRequest);
  if (gadgets.MiniMessage)
    gadgets.MiniMessage = taming.MiniMessage(imports.$v);
  if (gadgets.TabSet)
    gadgets.TabSet = taming.TabSet(imports.$v, gadgets.TabSet);

  // Add the opensocial APIs and mark them callable and readable.
  imports.outers.gadgets = gadgets;
  imports.outers.opensocial = opensocial;

  // The below described the opensocial reference APIs.
  // A prefix of "c_" specifies a class, "m_" a method, "f_" a field,
  // and "s_" a static member.
  // Derived from http://code.google.com/apis/opensocial/docs/0.8/reference/ .
  var opensocialSchema = {
    c_gadgets: {
      c_MiniMessage: {
        m_createDismissibleMessage: 0,
        m_createStaticMessage: 0,
        m_createTimerMessage: 0,
        m_dismissMessage: 0
      },
      c_Prefs: {
        m_getArray: 0,
        m_getBool: 0,
        m_getCountry: 0,
        m_getFloat: 0,
        m_getInt: 0,
        m_getLang: 0,
        m_getMsg: 0,
        m_getString: 0,
        m_set: 0,
        m_setArray: 0
      },
      c_Tab: {
        m_getCallback: 0,
        m_getContentContainer: 0,
        m_getIndex: 0,
        m_getName: 0,
        m_getNameContainer: 0
      },
      c_TabSet: {
        m_addTab: 0
//        m_alignTabs: 0,
//        m_displayTabs: 0,
//        m_getHeaderContainer: 0,
//        m_getSelectedTab: 0,
//        m_getTabs: 0,
//        m_removeTab: 0,
//        m_setSelectedTab: 0,
//        m_swapTabs: 0
      },
      c_flash: {
        s_embedCachedFlash: 0,
        s_embedFlash: 0,
        s_getMajorVersion: 0
      },
      c_io: {
        c_AuthorizationType: {
          s_NONE: 0,
          s_OAUTH: 0,
          s_SIGNED: 0
        },
        c_ContentType: {
          s_DOM: 0,
          s_FEED: 0,
          s_JSON: 0,
          s_TEXT: 0
        },
        c_MethodType: {
          s_DELETE: 0,
          s_GET: 0,
          s_HEAD: 0,
          s_POST: 0,
          s_PUT: 0
        },
        c_ProxyUrlRequestParameters: {
          s_REFRESH_INTERVAL: 0
        },
        c_RequestParameters: {
          s_AUTHORIZATION: 0,
          s_CONTENT_TYPE: 0,
          s_GET_SUMMARIES: 0,
          s_HEADERS: 0,
          s_METHOD: 0,
          s_NUM_ENTRIES: 0,
          s_POST_DATA: 0
        },
        s_encodeValues: 0,
        s_getProxyUrl: 0,
        s_makeRequest: 0
      },
      c_json: {
        s_parse: 0,
        s_stringify: 0
      },
      c_pubsub: {
        s_publish: 0,
        s_subscribe: 0,
        s_unsubscribe: 0
      },
      c_rpc: {
        s_call: 0,
        s_register: 0,
        s_registerDefault: 0,
        s_unregister: 0,
        s_unregisterDefault: 0
      },
      c_skins: {
        c_Property: {
          s_ANCHOR_COLOR: 0,
          s_BG_COLOR: 0,
          s_BG_IMAGE: 0,
          s_FONT_COLOR: 0
        },
        s_getProperty: 0
      },
      c_util: {
        s_escapeString: 0,
        s_getFeatureParameters: 0,
        s_hasFeature: 0,
        s_registerOnLoadHandler: 0,
        s_unescapeString: 0
      },
      c_views: {
        c_View: {
          m_bind: 0,
          m_getUrlTemplate: 0,
          m_isOnlyVisibleGadget: 0
        },
        c_ViewType: {
          s_CANVAS: 0,
          s_HOME: 0,
          s_PREVIEW: 0,
          s_PROFILE: 0
        },
        s_bind: 0,
	// FIXME(benl): Why do we think getCurrentView does not use "this"?
        s_getCurrentView: 0,
        s_getParams: 0,
        s_requestNavigateTo: 0
      },
      c_window: {
        s_adjustHeight: 0,
        s_getViewportDimensions: 0,
        s_setTitle: 0
      }
    },
    c_opensocial: {
      c_Activity: {
        c_Field: {
          s_APP_ID: 0,
          s_BODY: 0,
          s_BODY_ID: 0,
          s_EXTERNAL_ID: 0,
          s_ID: 0,
          s_MEDIA_ITEMS: 0,
          s_POSTED_TIME: 0,
          s_PRIORITY: 0,
          s_STREAM_FAVICON_URL: 0,
          s_STREAM_SOURCE_URL: 0,
          s_STREAM_TITLE: 0,
          s_STREAM_URL: 0,
          s_TEMPLATE_PARAMS: 0,
          s_TITLE: 0,
          s_TITLE_ID: 0,
          s_URL: 0,
          s_USER_ID: 0
        },
        m_getField: 0,
        m_getId: 0,
        m_setField: 0
      },
      c_Address: {
        c_Field: {
          s_COUNTRY: 0,
          s_EXTENDED_ADDRESS: 0,
          s_LATITUDE: 0,
          s_LOCALITY: 0,
          s_LONGITUDE: 0,
          s_POSTAL_CODE: 0,
          s_PO_BOX: 0,
          s_REGION: 0,
          s_STREET_ADDRESS: 0,
          s_TYPE: 0,
          s_UNSTRUCTURED_ADDRESS: 0
        },
        m_getField: 0
      },
      c_BodyType: {
        c_Field: {
          s_BUILD: 0,
          s_EYE_COLOR: 0,
          s_HAIR_COLOR: 0,
          s_HEIGHT: 0,
          s_WEIGHT: 0
        },
        m_getField: 0
      },
      c_Collection: {
        m_asArray: 0,
        m_each: 0,
        m_getById: 0,
        m_getOffset: 0,
        m_getTotalSize: 0,
        m_size: 0
      },
      c_CreateActivityPriority: {
        s_HIGH: 0,
        s_LOW: 0
      },
      c_DataRequest: {
        c_DataRequestFields: {
          s_ESCAPE_TYPE: 0
        },
        c_FilterType: {
          s_ALL: 0,
          s_HAS_APP: 0,
          s_TOP_FRIENDS: 0
        },
        c_PeopleRequestFields: {
          s_FILTER: 0,
          s_FILTER_OPTIONS: 0,
          s_FIRST: 0,
          s_MAX: 0,
          s_PROFILE_DETAILS: 0,
          s_SORT_ORDER: 0
        },
        c_SortOrder: {
          s_NAME: 0,
          s_TOP_FRIENDS: 0
        },
        m_add: 0,
        m_newFetchActivitiesRequest: 0,
        m_newFetchPeopleRequest: 0,
        m_newFetchPersonAppDataRequest: 0,
        m_newFetchPersonRequest: 0,
        m_newRemovePersonAppDataRequest: 0,
        m_newUpdatePersonAppDataRequest: 0,
        m_send: 0
      },
      c_DataResponse: {
        m_get: 0,
        m_getErrorMessage: 0,
        m_hadError: 0
      },
      c_Email: {
        c_Field: {
          s_ADDRESS: 0,
          s_TYPE: 0
        },
        m_getField: 0
      },
      c_Enum: {
        c_Drinker: {
          s_HEAVILY: 0,
          s_NO: 0,
          s_OCCASIONALLY: 0,
          s_QUIT: 0,
          s_QUITTING: 0,
          s_REGULARLY: 0,
          s_SOCIALLY: 0,
          s_YES: 0
        },
        c_Gender: {
          s_FEMALE: 0,
          s_MALE: 0
        },
        c_LookingFor: {
          s_ACTIVITY_PARTNERS: 0,
          s_DATING: 0,
          s_FRIENDS: 0,
          s_NETWORKING: 0,
          s_RANDOM: 0,
          s_RELATIONSHIP: 0
        },
        c_Presence: {
          s_AWAY: 0,
          s_CHAT: 0,
          s_DND: 0,
          s_OFFLINE: 0,
          s_ONLINE: 0,
          s_XA: 0
        },
        c_Smoker: {
          s_HEAVILY: 0,
          s_NO: 0,
          s_OCCASIONALLY: 0,
          s_QUIT: 0,
          s_QUITTING: 0,
          s_REGULARLY: 0,
          s_SOCIALLY: 0,
          s_YES: 0
        },
        m_getDisplayValue: 0,
        m_getKey: 0
      },
      c_Environment: {
        c_ObjectType: {
          s_ACTIVITY: 0,
          s_ACTIVITY_MEDIA_ITEM: 0,
          s_ADDRESS: 0,
          s_BODY_TYPE: 0,
          s_EMAIL: 0,
          s_FILTER_TYPE: 0,
          s_MESSAGE: 0,
          s_MESSAGE_TYPE: 0,
          s_NAME: 0,
          s_ORGANIZATION: 0,
          s_PERSON: 0,
          s_PHONE: 0,
          s_SORT_ORDER: 0,
          s_URL: 0
        },
        m_getDomain: 0,
        m_supportsField: 0
      },
      c_EscapeType: {
        s_HTML_ESCAPE: 0,
        s_NONE: 0
      },
      c_IdSpec: {
        c_Field: {
          s_GROUP_ID: 0,
          s_NETWORK_DISTANCE: 0,
          s_USER_ID: 0
        },
        c_PersonId: {
          s_OWNER: 0,
          s_VIEWER: 0
        },
        m_getField: 0,
        m_setField: 0
      },
      c_MediaItem: {
        c_Field: {
          s_MIME_TYPE: 0,
          s_TYPE: 0,
          s_URL: 0
        },
        c_Type: {
          s_AUDIO: 0,
          s_IMAGE: 0,
          s_VIDEO: 0
        },
        m_getField: 0,
        m_setField: 0
      },
      c_Message: {
        c_Field: {
          s_BODY: 0,
          s_BODY_ID: 0,
          s_TITLE: 0,
          s_TITLE_ID: 0,
          s_TYPE: 0
        },
        c_Type: {
          s_EMAIL: 0,
          s_NOTIFICATION: 0,
          s_PRIVATE_MESSAGE: 0,
          s_PUBLIC_MESSAGE: 0
        },
        m_getField: 0,
        m_setField: 0
      },
      c_Name: {
        c_Field: {
          s_ADDITIONAL_NAME: 0,
          s_FAMILY_NAME: 0,
          s_GIVEN_NAME: 0,
          s_HONORIFIC_PREFIX: 0,
          s_HONORIFIC_SUFFIX: 0,
          s_UNSTRUCTURED: 0
        },
        m_getField: 0
      },
      c_NavigationParameters: {
        c_DestinationType: {
          s_RECIPIENT_DESTINATION: 0,
          s_VIEWER_DESTINATION: 0
        },
        c_Field: {
          s_OWNER: 0,
          s_PARAMETERS: 0,
          s_VIEW: 0
        },
        m_getField: 0,
        m_setField: 0
      },
      c_Organization: {
        c_Field: {
          s_ADDRESS: 0,
          s_DESCRIPTION: 0,
          s_END_DATE: 0,
          s_FIELD: 0,
          s_NAME: 0,
          s_SALARY: 0,
          s_START_DATE: 0,
          s_SUB_FIELD: 0,
          s_TITLE: 0,
          s_WEBPAGE: 0
        },
        m_getField: 0
      },
      c_Permission: {
        s_VIEWER: 0
      },
      c_Person: {
        c_Field: {
          s_ABOUT_ME: 0,
          s_ACTIVITIES: 0,
          s_ADDRESSES: 0,
          s_AGE: 0,
          s_BODY_TYPE: 0,
          s_BOOKS: 0,
          s_CARS: 0,
          s_CHILDREN: 0,
          s_CURRENT_LOCATION: 0,
          s_DATE_OF_BIRTH: 0,
          s_DRINKER: 0,
          s_EMAILS: 0,
          s_ETHNICITY: 0,
          s_FASHION: 0,
          s_FOOD: 0,
          s_GENDER: 0,
          s_HAPPIEST_WHEN: 0,
          s_HAS_APP: 0,
          s_HEROES: 0,
          s_HUMOR: 0,
          s_ID: 0,
          s_INTERESTS: 0,
          s_JOBS: 0,
          s_JOB_INTERESTS: 0,
          s_LANGUAGES_SPOKEN: 0,
          s_LIVING_ARRANGEMENT: 0,
          s_LOOKING_FOR: 0,
          s_MOVIES: 0,
          s_MUSIC: 0,
          s_NAME: 0,
          s_NETWORK_PRESENCE: 0,
          s_NICKNAME: 0,
          s_PETS: 0,
          s_PHONE_NUMBERS: 0,
          s_POLITICAL_VIEWS: 0,
          s_PROFILE_SONG: 0,
          s_PROFILE_URL: 0,
          s_PROFILE_VIDEO: 0,
          s_QUOTES: 0,
          s_RELATIONSHIP_STATUS: 0,
          s_RELIGION: 0,
          s_ROMANCE: 0,
          s_SCARED_OF: 0,
          s_SCHOOLS: 0,
          s_SEXUAL_ORIENTATION: 0,
          s_SMOKER: 0,
          s_SPORTS: 0,
          s_STATUS: 0,
          s_TAGS: 0,
          s_THUMBNAIL_URL: 0,
          s_TIME_ZONE: 0,
          s_TURN_OFFS: 0,
          s_TURN_ONS: 0,
          s_TV_SHOWS: 0,
          s_URLS: 0
        },
        m_getDisplayName: 0,
        m_getField: 0,
        m_getId: 0,
        m_isOwner: 0,
        m_isViewer: 0
      },
      c_Phone: {
        c_Field: {
          s_NUMBER: 0,
          s_TYPE: 0
        },
        m_getField: 0
      },
      c_ResponseItem: {
        c_Error: {
          s_BAD_REQUEST: 0,
          s_FORBIDDEN: 0,
          s_INTERNAL_ERROR: 0,
          s_LIMIT_EXCEEDED: 0,
          s_NOT_IMPLEMENTED: 0,
          s_UNAUTHORIZED: 0
        },
        m_getData: 0,
        m_getErrorCode: 0,
        m_getErrorMessage: 0,
        m_getOriginalDataRequest: 0,
        m_hadError: 0
      },
      c_Url: {
        c_Field: {
          s_ADDRESS: 0,
          s_LINK_TEXT: 0,
          s_TYPE: 0
        },
        m_getField: 0
      },
      s_getEnvironment: 0,
      s_hasPermission: 0,
      s_newActivity: 0,
      s_newDataRequest: 0,
      s_newIdSpec: 0,
      s_newMediaItem: 0,
      s_newMessage: 0,
      s_newNavigationParameters: 0,
      s_requestCreateActivity: 0,
      s_requestPermission: 0,
      s_requestSendMessage: 0,
      s_requestShareApp: 0
    }
  };

  function whitelist(schema, obj) {
    if (!obj) { return; }  // Occurs for optional features
    for (var k in schema) {
      if (schema.hasOwnProperty(k)) {
        var m = k.match(/^([mcsa])_(\w+)$/);
        var type = m[1], name = m[2];
        switch (type) {
          case 'c':
            ___.grantRead(obj, name);
            whitelist(schema[k], obj[name]);
            break;
          // grant access to a function that uses "this"
          case 'm':
            ___.grantGeneric(obj.prototype, name);
            break;
          case 'f':
            ___.grantRead(obj.prototype, name);
            break;
          case 'a': // attenuate function
            if ('function' === typeof obj[name] && schema[k]) {
              ___.handleGeneric(obj, name, schema[k](obj[name]));
            }
            break;
          // grant access to a variable or an instance
          // of a function that does not use "this"
          case 's':
            if ('function' === typeof obj[name]) {
              ___.grantFunc(obj, name);
            } else {
              ___.grantRead(obj, name);
            }
            break;
        }
      }
    }
  }
  whitelist(opensocialSchema, imports.outers);
  if (gadgets.MiniMessage)
    ___.ctor(gadgets.MiniMessage, Object, 'MiniMessage');
  if (gadgets.TabSet)
    ___.ctor(gadgets.TabSet, Object, 'TabSet');
};
