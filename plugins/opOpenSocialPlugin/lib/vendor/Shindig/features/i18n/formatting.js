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
 * @fileoverview Functions for dealing with locale-specific formatting
 *
 * Note: Gadgets locale is set at render time. Gadget containers should emit
 * the data files required by the i18n feature by emitting
 * DateTimeConstants__<2 letter language code>_<2 letter country code>.js
 * and NumberFormatConstants__<2 letter language code>_<2 letter countrycode>.js.
 * Data files are located at features/i18n/data. Note the _<2 letter country code>
 * part is optional. The i18n package above will then load the corresponding
 * formatter/parser for that locale if any of the functions in the package are
 * invoked.
 */

var gadgets = gadgets || {};

gadgets.i18n = gadgets.i18n || {};

gadgets.i18n.dtFormatter_ = null;
gadgets.i18n.dtParser_ = null;
gadgets.i18n.numFormatter_ = null;

/**
 * Format the given date object into a string representation using pattern
 * specified.
 * @param {string/number} pattern String to specify patterns or Number used to reference predefined
 *        pattern that a date should be formatted into.
 * @param {Date} date Date object being formatted.
 *
 * @return {string} string representation of date/time.
 */
gadgets.i18n.formatDateTime = function(pattern, date) {
    if (!gadgets.i18n.dtFormatter_) {
        gadgets.i18n.dtFormatter_ = new gadgets.i18n.DateTimeFormat(gadgets.i18n.DateTimeConstants);
        typeof pattern == 'string'
                ? gadgets.i18n.dtFormatter_.applyPattern(pattern)
                : gadgets.i18n.dtFormatter_.applyStandardPattern(pattern);
        gadgets.i18n.dtFormatter_.patternInUse_ = pattern;
    } else if (gadgets.i18n.dtFormatter_.patternInUse_ != pattern) {
        typeof pattern == 'string'
                ? gadgets.i18n.dtFormatter_.applyPattern(pattern)
                : gadgets.i18n.dtFormatter_.applyStandardPattern(pattern);
        gadgets.i18n.dtFormatter_.patternInUse_ = pattern;
    }
    return gadgets.i18n.dtFormatter_.format(date);
};


/**
 * Parse a string using the format as specified in pattern string, and
 * return date in the passed "date" parameter.
 *
 * @param {string/number} pattern String to specify patterns or Number used to
 *        reference predefined
 *        pattern that a date should be parsed from.
 * @param {string} text The string that need to be parsed.
 * @param {number} start The character position in "text" where parse begins.
 * @param {Date} date The date object that will hold parsed value.
 *
 * @return {number} The number of characters advanced or 0 if failed.
 */
gadgets.i18n.parseDateTime = function(pattern, text, start, date) {
    if (!gadgets.i18n.dtParser_) {
        gadgets.i18n.dtParser_ = new gadgets.i18n.DateTimeParse(gadgets.i18n.DateTimeConstants);
        typeof pattern == 'string'
                ? gadgets.i18n.dtParser_.applyPattern(pattern)
                : gadgets.i18n.dtParser_.applyStandardPattern(pattern);
        gadgets.i18n.dtParser_.patternInUse_ = pattern;
    } else if (gadgets.i18n.dtParser_.patternInUse_ != pattern) {
        typeof pattern == 'string'
                ? gadgets.i18n.dtParser_.applyPattern(pattern)
                : gadgets.i18n.dtParser_.applyStandardPattern(pattern);
        gadgets.i18n.dtParser_.patternInUse_ = pattern;
    }
    return gadgets.i18n.dtParser_.parse(text, start, date);
};


/**
 * Format the number using given pattern.
 * @param {string/number} pattern String to specify patterns or Number used to
 *        reference predefined
 *        pattern that a number should be formatted into.
 * @param {number} value The number being formatted.
 * @param {string} opt_currencyCode optional international currency code, it
 *     determines the currency code/symbol should be used in format/parse. If
 *     not given, the currency code for current locale will be used.
 * @return {string} The formatted string.
 */
gadgets.i18n.formatNumber = function(pattern, value, opt_currencyCode) {
    if (!gadgets.i18n.numFormatter_) {
        gadgets.i18n.numFormatter_ = new gadgets.i18n.NumberFormat(gadgets.i18n.NumberFormatConstants);
        typeof pattern == 'string'
                ? gadgets.i18n.numFormatter_.applyPattern(
                  pattern, opt_currencyCode)
                : gadgets.i18n.numFormatter_.applyStandardPattern(
                  pattern, opt_currencyCode);
        gadgets.i18n.numFormatter_.patternInUse_ = pattern;
    } else if (gadgets.i18n.numFormatter_.patternInUse_ != pattern) {
        typeof pattern == 'string'
                ? gadgets.i18n.numFormatter_.applyPattern(
                  pattern, opt_currencyCode)
                : gadgets.i18n.numFormatter_.applyStandardPattern(
                  pattern, opt_currencyCode);
        gadgets.i18n.numFormatter_.patternInUse_ = pattern;
    }
    return gadgets.i18n.numFormatter_.format(value);
};


/**
 * Parse the given text using specified pattern to get a number.
 * @param {string/number} pattern String to specify patterns or Number used
 *        to reference predefined
 *        pattern that a number should be parsed from.
 * @param {string} text input text being parsed.
 * @param {Array} opt_pos optional one element array that holds position
 *     information. It tells from where parse should begin. Upon return, it
 *     holds parse stop position.
 * @param {string} opt_currencyCode optional international currency code, it
 *     determines the currency code/symbol should be used in format/parse. If
 *     not given, the currency code for current locale will be used.
 * @return {number} Parsed number, 0 if in error.
 */
gadgets.i18n.parseNumber = function(pattern, text, opt_pos, opt_currencyCode) {
    if (!gadgets.i18n.numFormatter_) {
        gadgets.i18n.numFormatter_ = new gadgets.i18n.NumberFormat();
        typeof pattern == 'string'
                ? gadgets.i18n.numFormatter_.applyPattern(pattern,
                                                          opt_currencyCode)
                : gadgets.i18n.numFormatter_.applyStandardPattern(
                  pattern, opt_currencyCode);
        gadgets.i18n.numFormatter_.patternInUse_ = pattern;
        gadgets.i18n.numFormatter_.currencyCodeInUse_ = opt_currencyCode;
    } else if (gadgets.i18n.numFormatter_.patternInUse_ != pattern ||
               gadgets.i18n.numFormatter_.currencyCodeInUse_
                       != opt_currencyCode) {
        typeof pattern == 'string'
                ? gadgets.i18n.numFormatter_.applyPattern(pattern,
                                                          opt_currencyCode)
                : gadgets.i18n.numFormatter_.applyStandardPattern(
                  pattern, opt_currencyCode);
        gadgets.i18n.numFormatter_.patternInUse_ = pattern;
        gadgets.i18n.numFormatter_.currencyCodeInUse_ = opt_currencyCode;
    }
    return gadgets.i18n.numFormatter_.parse(text, opt_pos);
};

// Couple of constants to represent predefined Date/Time format type.

/**
 * Format for full representations of dates.
 * @type {number}
 */
gadgets.i18n.FULL_DATE_FORMAT = 0;


/**
 * Format for long representations of dates.
 * @type {number}
 */
gadgets.i18n.LONG_DATE_FORMAT = 1;


/**
 * Format for medium representations of dates.
 * @type {number}
 */
gadgets.i18n.MEDIUM_DATE_FORMAT = 2;


/**
 * Format for short representations of dates.
 * @type {number}
 */
gadgets.i18n.SHORT_DATE_FORMAT = 3;


/**
 * Format for full representations of times.
 * @type {number}
 */
gadgets.i18n.FULL_TIME_FORMAT = 4;


/**
 * Format for long representations of times.
 * @type {number}
 */
gadgets.i18n.LONG_TIME_FORMAT = 5;


/**
 * Format for medium representations of times.
 * @type {number}
 */
gadgets.i18n.MEDIUM_TIME_FORMAT = 6;


/**
 * Format for short representations of times.
 * @type {number}
 */
gadgets.i18n.SHORT_TIME_FORMAT = 7;


/**
 * Format for short representations of datetimes.
 * @type {number}
 */
gadgets.i18n.FULL_DATETIME_FORMAT = 8;


/**
 * Format for short representations of datetimes.
 * @type {number}
 */
gadgets.i18n.LONG_DATETIME_FORMAT = 9;


/**
 * Format for medium representations of datetimes.
 * @type {number}
 */
gadgets.i18n.MEDIUM_DATETIME_FORMAT = 10;


/**
 * Format for short representations of datetimes.
 * @type {number}
 */
gadgets.i18n.SHORT_DATETIME_FORMAT = 11;


/**
 * Predefined number format pattern type. The actual pattern is defined
 * separately for each locale.
 */


/**
 * Pattern for decimal numbers.
 * @type {number}
 */
gadgets.i18n.DECIMAL_PATTERN = 1;


/**
 * Pattern for scientific numbers.
 * @type {number}
 */
gadgets.i18n.SCIENTIFIC_PATTERN = 2;


/**
 * Pattern for percentages.
 * @type {number}
 */
gadgets.i18n.PERCENT_PATTERN = 3;


/**
 * Pattern for currency.
 * @type {number}
 */
gadgets.i18n.CURRENCY_PATTERN = 4;
