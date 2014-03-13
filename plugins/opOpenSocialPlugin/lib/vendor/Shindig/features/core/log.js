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
 * @fileoverview
 * Support for basic logging capability for gadgets, meant to replace
 * alert(msg) and window.console.log(msg).
 *
 * Currently only works on browsers with a console (WebKit based browsers
 * or Firefox with Firebug extension).
 *
 * API is designed to be equivalent to existing console.log | warn | error
 * logging APIs supported by Firebug and WebKit based browsers. The only
 * addition is the ability to call gadgets.setLogLevel().
 */

var gadgets = gadgets || {};

/**
 * Log an informational message
 */
gadgets.log = function(message) {
  gadgets.log.logAtLevel(gadgets.log.INFO, message);
};

/**
 * Log a warning
 */
gadgets.warn = function(message) {
  gadgets.log.logAtLevel(gadgets.log.WARNING, message);
};

/**
 * Log an error
 */
gadgets.error = function(message) {
  gadgets.log.logAtLevel(gadgets.log.ERROR, message);
};

/**
 * Sets the log level threshold.
 * @param {Number} logLevel - New log level threshold.
 * @static
 */
gadgets.setLogLevel = function(logLevel) {
  gadgets.log.logLevelThreshold_ = logLevel;
};

/**
 * Logs a log message if output console is available, and log threshold is met.
 * @param {Number} level - the level to log with. Optional, defaults to
 * @param {Object} message - The message to log
 * gadgets.log.INFO.
 * @static
 */
gadgets.log.logAtLevel = function(level, message) {
  if (level < gadgets.log.logLevelThreshold_ || !window.console) {
    return;
  }

  var logger = window.console.log;

  if (level == gadgets.log.WARNING && window.console.warn) {
    logger = window.console.warn;
  } else if (level == gadgets.log.ERROR && window.console.error) {
    logger = window.console.error;
  }

  logger(message);
};

/**
 * Log level for informational logging.
 * @static
 */
gadgets.log.INFO = 1;

/**
 * Log level for warning logging.
 * @static
 */
gadgets.log.WARNING = 2;

/**
 * Log level for error logging.
 * @static
 */
gadgets.log.ERROR = 3;

/**
 * Log level for no logging
 * @static
 */
gadgets.log.NONE = 4;

/**
 * Current log level threshold.
 * @type Number
 * @private
 * @static
 */
gadgets.log.logLevelThreshold_ = gadgets.log.INFO;
