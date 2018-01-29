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

/*global opensocial */

/**
 * @class
 * Representation of an activity.
 *
 * <p>Activities are rendered with a title and an optional activity body.</p>
 *
 * <p>You may set the title and body directly as strings when calling
 * opensocial.createActivity.</p>
 *
 * <p>However, it is usually beneficial to create activities using
 * Activity Templates for the title and body. Activity Templates support:</p>
 * <ul>
 *   <li>Internationalization</li>
 *   <li>Replacement variables in the message</li>
 *   <li>Activity Summaries, which are message variations used to summarize
 *     repeated activities that share something in common.</li>
 * </ul>
 *
 * <p>Activity Templates are defined as messages in the gadget specification.
 * To define messages, you create and reference message bundle XML files for
 * each locale you support.</p>
 *
 * <p>Example module spec in gadget XML:
 * <pre>
 * &lt;ModulePrefs title="ListenToThis"&gt;
 *   &lt;Locale messages="http://www.listentostuff.com/messages.xml"/&gt;
 *   &lt;Locale lang="de" messages="http://www.listentostuff.com/messages-DE.xml"/&gt;
 * &lt;/ModulePrefs&gt;
 * </pre>
 * </p>
 *
 * <p>Example message bundle:
 * <pre>
 * &lt;messagebundle&gt;
 *  &lt;msg name="LISTEN_TO_THIS_SONG"&gt;
 *     ${Subject.DisplayName} told ${Owner.DisplayName} to
 *     listen to a song!
 *  &lt;/msg&gt;
 * &lt;/messagebundle&gt;
 * </pre>
 * </p>
 *
 * <p>You can set custom key/value string pairs when posting an activity.
 * These values will be used for variable substitution in the templates.</p>
 * <p>Example JS call:
 * <pre>
 *   var owner = ...;
 *   var viewer = ...;
 *   var activity = opensocial.newActivity('LISTEN_TO_THIS_SONG',
 *    {Song: 'Do That There - (Young Einstein hoo-hoo mix)',
 *     Artist: 'Lyrics Born', Subject: viewer, Owner: owner})
 * </pre>
 * </p>
 *
 * <p> Associated message:
 * <pre>
 * &lt;msg name="LISTEN_TO_THIS_SONG"&gt;
 *     ${Subject.DisplayName} told ${Owner.DisplayName} to listen
 *     to ${Song} by ${Artist}
 * &lt;/msg&gt;
 * </pre>
 * </p>
 *
 * <p>People can also be set as values in key/value pairs when posting
 * an activity. You can then reference the following fields on a person:</p>
 * <ul>
 *  <li>${Person.DisplayName} The person's name</li>
 *  <li>${Person.Id} The user ID of the person</li>
 *  <li>${Person.ProfileUrl} The profile URL of the person</li>
 *  <li>${Person} This will show the display name, but containers may optionally
 *     provide special formatting, such as showing the name as a link</li>
 * </ul>
 *
 * <p>Users will have many activities in their activity streams, and containers
 * will not show every activity that is visible to a user. To help display
 * large numbers of activities, containers will summarize a list of activities
 * from a given source to a single entry.</p>
 *
 * <p>You can provide Activity Summaries to customize the text shown when
 * multiple activities are summarized. If no customization is provided, a
 * container may ignore your activities altogether or provide default text
 * such as "Bob changed his status message + 20 other events like this."</p>
 * <ul>
 *  <li>Activity Summaries will always summarize around a specific key in a
 *   key/value pair. This is so that the summary can say something concrete
 *   (this is clearer in the example below).</li>
 *  <li>Other variables will have synthetic "Count" variables created with
 *   the total number of items summarized.</li>
 *  <li>Message ID of the summary is the message ID of the main template + ":" +
 *   the data key</li>
 * </ul>
 *
 * <p>Example summaries:
 * <pre>
 * &lt;messagebundle&gt;
 *   &lt;msg name="LISTEN_TO_THIS_SONG:Artist"&gt;
 *     ${Subject.Count} of your friends have suggested listening to songs
 *     by ${Artist}!
 *   &lt;/msg&gt;
 *   &lt;msg name="LISTEN_TO_THIS_SONG:Song"&gt;
 *     ${Subject.Count} of your friends have suggested listening to ${Song}
 *   !&lt;/msg&gt;
 *   &lt;msg name="LISTEN_TO_THIS_SONG:Subject"&gt;
 *    ${Subject.DisplayName} has recommended ${Song.Count} songs to you.
 *   &lt;/msg&gt;
 * &lt;/messagebundle&gt;
 * </pre></p>
 *
 * <p>Activity Templates may only have the following HTML tags: &lt;b&gt;,
 * &lt;i&gt;, &lt;a&gt;, &lt;span&gt;. The container also has the option
 * to strip out these tags when rendering the activity.</p>
 *
 * <p>
 * <b>See also:</b>
 * <a href="opensocial.html#newActivity">opensocial.newActivity()</a>,
 * <a href="opensocial.html#requestCreateActivity">
 * opensocial.requestCreateActivity()</a>
 *
 * @name opensocial.Activity
 */


/**
 * Base interface for all activity objects.
 *
 * Private, see opensocial.createActivity() for usage.
 *
 * @param {Map.&lt;opensocial.Activity.Field, Object&gt;} params
 *    Parameters defining the activity.
 * @private
 * @constructor
 */
opensocial.Activity = function(params) {
  this.fields_ = params;
};


/**
 * @static
 * @class
 * All of the fields that activities can have.
 *
 * <p>It is only required to set one of TITLE_ID or TITLE. In addition, if you
 * are using any variables in your title or title template,
 * you must set TEMPLATE_PARAMS.</p>
 *
 * <p>Other possible fields to set are: URL, MEDIA_ITEMS, BODY_ID, BODY,
 * EXTERNAL_ID, PRIORITY, STREAM_TITLE, STREAM_URL, STREAM_SOURCE_URL,
 * and STREAM_FAVICON_URL.</p>
 *
 * <p>Containers are only required to use TITLE_ID or TITLE, they may ignore
 * additional parameters.</p>
 *
 * <p>
 * <b>See also:</b>
 * <a
 * href="opensocial.Activity.html#getField">opensocial.Activity.getField()</a>
 * </p>
 *
 * @name opensocial.Activity.Field
 */
opensocial.Activity.Field = {
  /**
   * <p>A string specifying the title template message ID in the gadget
   *   spec.</p>
   *
   * <p>The title is the primary text of an activity.</p>
   *
   * <p>Titles may only have the following HTML tags: &lt;b&gt; &lt;i&gt;,
   * &lt;a&gt;, &lt;span&gt;.
   * The container may ignore this formatting when rendering the activity.</p>
   *
   * @member opensocial.Activity.Field
   */
  TITLE_ID : 'titleId',

  /**
   * <p>A string specifying the primary text of an activity.</p>
   *
   * <p>Titles may only have the following HTML tags: &lt;b&gt; &lt;i&gt;,
   * &lt;a&gt;, &lt;span&gt;.
   * The container may ignore this formatting when rendering the activity.</p>
   *
   * @member opensocial.Activity.Field
   */
  TITLE : 'title',

  /**
   * <p>A map of custom keys to values associated with this activity.
   * These will be used for evaluation in templates.</p>
   *
   * <p>The data has type <code>Map&lt;String, Object&gt;</code>. The
   * object may be either a String or an opensocial.Person.</p>
   *
   * <p>When passing in a person with key PersonKey, can use the following
   * replacement variables in the template:</p>
   * <ul>
   *  <li>PersonKey.DisplayName - Display name for the person</li>
   *  <li>PersonKey.ProfileUrl. URL of the person's profile</li>
   *  <li>PersonKey.Id -  The ID of the person</li>
   *  <li>PersonKey - Container may replace with DisplayName, but may also
   *     optionally link to the user.</li>
   * </ul>
   *
   * @member opensocial.Activity.Field
   */
  TEMPLATE_PARAMS : 'templateParams',

  /**
   * A string specifying the
   * URL that represents this activity.
   * @member opensocial.Activity.Field
   */
  URL : 'url',

  /**
   * Any photos, videos, or images that should be associated
   * with the activity. Higher priority ones are higher in the list.
   * The data has type <code>Array&lt;
   * <a href="opensocial.MediaItem.html">MediaItem</a>&gt;</code>.
   * @member opensocial.Activity.Field
   */
  MEDIA_ITEMS : 'mediaItems',

  /**
   * <p>A string specifying the body template message ID in the gadget spec.</p>
   *
   * <p>The body is an optional expanded version of an activity.</p>
   *
   * <p>Bodies may only have the following HTML tags: &lt;b&gt; &lt;i&gt;,
   * &lt;a&gt;, &lt;span&gt;.
   * The container may ignore this formatting when rendering the activity.</p>
   *
   * @member opensocial.Activity.Field
   */
  BODY_ID : 'bodyId',

  /**
   * <p>A string specifying an optional expanded version of an activity.</p>
   *
   * <p>Bodies may only have the following HTML tags: &lt;b&gt; &lt;i&gt;,
   * &lt;a&gt;, &lt;span&gt;.
   * The container may ignore this formatting when rendering the activity.</p>
   *
   * @member opensocial.Activity.Field
   */
  BODY : 'body',

  /**
   * An optional string ID generated by the posting application.
   * @member opensocial.Activity.Field
   */
  EXTERNAL_ID : 'externalId',

  /**
   * A string specifing the title of the stream.
   * @member opensocial.Activity.Field
   */
  STREAM_TITLE : 'streamTitle',

  /**
   * A string specifying the stream's URL.
   * @member opensocial.Activity.Field
   */
  STREAM_URL : 'streamUrl',

  /**
   * A string specifying the stream's source URL.
   * @member opensocial.Activity.Field
   */
  STREAM_SOURCE_URL : 'streamSourceUrl',

  /**
   * A string specifying the URL for the stream's favicon.
   * @member opensocial.Activity.Field
   */
  STREAM_FAVICON_URL : 'streamFaviconUrl',

  /**
   * A number between 0 and 1 representing the relative priority of
   * this activity in relation to other activities from the same source
   * @member opensocial.Activity.Field
   */
  PRIORITY : 'priority',

  /**
   * A string ID that is permanently associated with this activity.
   * This value can not be set.
   * @member opensocial.Activity.Field
   */
  ID : 'id',

  /**
   * The string ID of the user who this activity is for.
   * This value can not be set.
   * @member opensocial.Activity.Field
   */
  USER_ID : 'userId',

  /**
   * A string specifying the application that this activity is associated with.
   * This value can not be set.
   * @member opensocial.Activity.Field
   */
  APP_ID : 'appId',

  /**
   * A string specifying the time at which this activity took place
   * in milliseconds since the epoch.
   * This value can not be set.
   * @member opensocial.Activity.Field
   */
  POSTED_TIME : 'postedTime'
};


/**
 * Gets an ID that can be permanently associated with this activity.
 *
 * @return {String} The ID
 * @member opensocial.Activity
 */
opensocial.Activity.prototype.getId = function() {
  return this.getField(opensocial.Activity.Field.ID);
};


/**
 * Gets the activity data that's associated with the specified key.
 *
 * @param {String} key The key to get data for;
 *   see the <a href="opensocial.Activity.Field.html">Field</a> class
 * for possible values
 * @param {Map.&lt;opensocial.DataRequest.DataRequestFields, Object&gt;}
 *  opt_params Additional
 *    <a href="opensocial.DataRequest.DataRequestFields.html">params</a>
 *    to pass to the request.
 * @return {String} The data
 * @member opensocial.Activity
 */
opensocial.Activity.prototype.getField = function(key, opt_params) {
  return opensocial.Container.getField(this.fields_, key, opt_params);
};


/**
 * Sets data for this activity associated with the given key.
 *
 * @param {String} key The key to set data for
 * @param {String} data The data to set
 */
opensocial.Activity.prototype.setField = function(key, data) {
  return (this.fields_[key] = data);
};