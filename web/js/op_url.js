/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNE url JavaScript helper library
 *
 * @author Shinichi Urabe <urabe@tejimaya.com>
 */

/**
 * opUrl class
 */
var opUrl = {

  dummy_callback: function () {},

  /**
   * method app_url_for().
   *
   * @param {strina} application ex: pc_frontend, api ...
   * @param {string} internalUri 'module/action' or '@rule' of the action
   * @param {boolean} absolute  return absolute path?
   * @param {object} callback {success: function(result) {}, error: function(error) {}}
   */
  app_url_for: function(application, internalUri, absolute, callbacks)
  {
    this.call(application, internalUri, absolute, callbacks);
  },

  /**
   * method url_for().
   *
   * @param {string} internalUri 'module/action' or '@rule' of the action
   * @param {boolean} absolute  return absolute path?
   * @param {object} callback {success: function(result) {}, error: function(error) {}}
   */
  url_for: function(internalUri, absolute, callbacks)
  {
    this.call('', internalUri, absolute, callbacks);
  },

  call: function(application, internalUri, absolute, callbacks)
  {
    if (typeof callbacks === 'function')
    {
      callbacks = { success: callbacks };
    }

    if (typeof callbacks !== 'object')
    {
      callbacks = {};
    }

    if (typeof callbacks.success !== 'function')
    {
      callbacks.success = this.dummy_callback;
    }

    if (typeof callbacks.error !== 'function')
    {
      callbacks.error = this.dummy_callback;
    }

    var key = this.generate_cache_key(application, internalUri, absolute);
    var result = opLocalStorage.get(key);
    if (typeof result === 'string')
    {
      // Local Storage result.
      callbacks.success(result);

      return result;
    }

    this.request(application, internalUri, absolute).done(function(result) {
      opLocalStorage.set(key, result);
      // Ajax response result.
      callbacks.success(result);
    }).fail(function(xhr, textStatus, errorThrown) {
      callbacks.error(xhr, textStatus, errorThrown);
    });
  },

  request: function(application, internalUri, absolute)
  {
    var deferred = $.Deferred();

    return $.ajax({
      type: 'POST',
      url: openpne.urlForUrl,
      data: { application: application, params: [internalUri, Number(absolute)] },
      dataType: 'text',
      success: deferred.resolve,
      error: function (xhr, textStatus, errorThrown) { deferred.reject(xhr, textStatus, errorThrown); }
    });

    return deferred.promise();
  },

  generate_cache_key: function(application, internalUri, absolute)
  {
    return  'opUrlKey:' + encodeURIComponent(application) + '&' + encodeURIComponent(internalUri) + '&' + encodeURIComponent(String(absolute));
  }
};
