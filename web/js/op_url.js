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

  /**
   * method app_url_for().
   *
   * @param  {strina} application ex: api ...
   * @param  {string} internalUri 'module/action' or '@rule' of the action
   * @param {boolean} absolute  return absolute path?
   * @returns {Deferred} Return a Deferred's Promise object. opUrl.url_for(... snip ...).done(function(url) {... snip ...}).fail(function(xhr, textStatus, errorThrown) {... snip ...})
   */
  app_url_for: function(application, internalUri, absolute)
  {
    return this.call(application, internalUri, absolute);
  },

  /**
   * method url_for().
   *
   * @param {string} internalUri 'module/action' or '@rule' of the action
   * @param {boolean} absolute  return absolute path?
   * @returns {Deferred} Return a Deferred's Promise object. opUrl.url_for(... snip ...).done(function(url) {... snip ...}).fail(function(xhr, textStatus, errorThrown) {... snip ...})
   */
  url_for: function(internalUri, absolute)
  {
    return this.call('', internalUri, absolute);
  },

  call: function(application, internalUri, absolute)
  {
    var key = this.generate_cache_key(application, internalUri, absolute);
    var result = opLocalStorage.get(key);
    if (typeof result === 'string')
    {
      var deferred = $.Deferred();
      deferred.resolve(result);

      return deferred.promise();
    }

    return this.request(application, internalUri, absolute).done(function(data) {
      opLocalStorage.set(key, data);
    });
  },

  request: function(application, internalUri, absolute)
  {
    var deferred = $.Deferred();

    $.ajax({
      type: 'POST',
      url: openpne.urlForUrl,
      data: { application: application, params: [internalUri, Number(absolute)] },
      dataType: 'text',
      success: deferred.resolve,
      error: function(xhr, textStatus, errorThrown) {
        deferred.reject(xhr, textStatus, errorThrown);
      }
    });

    return deferred.promise();
  },

  generate_cache_key: function(application, internalUri, absolute)
  {
    return  'opUrlKey:' + encodeURIComponent(application) + '&' + encodeURIComponent(internalUri) + '&' + encodeURIComponent(String(absolute));
  }
};
