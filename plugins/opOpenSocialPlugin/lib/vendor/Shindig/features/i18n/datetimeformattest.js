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
 * @fileoverview Unit Tests - gadgets.i18n.DateTimeFormat.
 */

function DateTimeFormatTest(name) {
    TestCase.call(this, name);
}

DateTimeFormatTest.inherits(TestCase);

var DateTimeConstants_de = {
    ERAS:["v. Chr.", "n. Chr."],
    ERANAMES:["v. Chr.", "n. Chr."],
    NARROWMONTHS:["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
    MONTHS:["Januar", "Februar", "M\u00E4rz", "April", "Mai", "Juni", "Juli",
        "August", "September", "Oktober", "November", "Dezember"],
    SHORTMONTHS:["Jan", "Feb", "Mrz", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep",
        "Okt", "Nov", "Dez"],
    WEEKDAYS:["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag",
        "Freitag", "Samstag"],
    SHORTWEEKDAYS:["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
    NARROWWEEKDAYS:["S", "M", "D", "M", "D", "F", "S"],
    SHORTQUARTERS:["Q1", "Q2", "Q3", "Q4"],
    QUARTERS:["1. Quartal", "2. Quartal", "3. Quartal", "4. Quartal"],
    AMPMS:["vorm.", "nachm."],
    DATEFORMATS:["EEEE, d. MMMM yyyy", "d. MMMM yyyy", "dd.MM.yyyy",
        "dd.MM.yy"],
    TIMEFORMATS:["H:mm' Uhr 'z", "HH:mm:ss z", "HH:mm:ss", "HH:mm"],
    ZONESTRINGS:null
};

DateTimeConstants_de.STANDALONENARROWMONTHS =
DateTimeConstants_de.NARROWMONTHS;
DateTimeConstants_de.STANDALONEMONTHS =
DateTimeConstants_de.MONTHS;
DateTimeConstants_de.STANDALONESHORTMONTHS =
DateTimeConstants_de.SHORTMONTHS;
DateTimeConstants_de.STANDALONEWEEKDAYS = DateTimeConstants_de.WEEKDAYS;
DateTimeConstants_de.STANDALONESHORTWEEKDAYS =
DateTimeConstants_de.SHORTWEEKDAYS;
DateTimeConstants_de.STANDALONENARROWWEEKDAYS =
DateTimeConstants_de.NARROWWEEKDAYS;

// Helpers to make tests work regardless of the timeZone we're in.
function timezoneString(date) {
    return (new gadgets.i18n.DateTimeFormat(DateTimeConstants_de)).formatGMT_(1, date);
}

function timezoneStringRFC(date) {
    return (new gadgets.i18n.DateTimeFormat(DateTimeConstants_de)).formatTimeZoneRFC_(1, date);
}

DateTimeFormatTest.prototype.setUp = function() {
    gadgets.i18n.dtFormatter_
            = new gadgets.i18n.DateTimeFormat(DateTimeConstants_de);
};

DateTimeFormatTest.prototype.testHHmmss = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("13:10:10",
            gadgets.i18n.formatDateTime("HH:mm:ss", date));
};

DateTimeFormatTest.prototype.testhhmmssa = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("1:10:10 nachm.",
            gadgets.i18n.formatDateTime("h:mm:ss a", date));
};

DateTimeFormatTest.prototype.testEEEMMMddyy = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("Do, Jul 27, 06",
            gadgets.i18n.formatDateTime("EEE, MMM d, yy", date));
};

DateTimeFormatTest.prototype.testEEEEMMMddyy = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("Donnerstag,Juli 27, 2006",
            gadgets.i18n.formatDateTime("EEEE,MMMM dd, yyyy", date));
};

DateTimeFormatTest.prototype.testyyyyMMddG = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("2006.07.27 n. Chr. at 13:10:10 " + timezoneString(date),
            gadgets.i18n.formatDateTime("yyyy.MM.dd G 'at' HH:mm:ss vvvv",
                    date));
};

DateTimeFormatTest.prototype.testyyyyyMMMMM = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("2006.J.27 n. Chr. 01:10 nachm.",
            gadgets.i18n.formatDateTime("yyyyy.MMMMM.dd GGG hh:mm aaa", date));
};

DateTimeFormatTest.prototype.testQQQQyy = function() {
    var date = new Date(2006, 0, 27, 13, 10, 10, 250);
    this.assertEquals("1. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 1, 27, 13, 10, 10, 250);
    this.assertEquals("1. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 2, 27, 13, 10, 10, 250);
    this.assertEquals("1. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 3, 27, 13, 10, 10, 250);
    this.assertEquals("2. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 4, 27, 13, 10, 10, 250);
    this.assertEquals("2. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 5, 27, 13, 10, 10, 250);
    this.assertEquals("2. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("3. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 7, 27, 13, 10, 10, 250);
    this.assertEquals("3. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 8, 27, 13, 10, 10, 250);
    this.assertEquals("3. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 9, 27, 13, 10, 10, 250);
    this.assertEquals("4. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 10, 27, 13, 10, 10, 250);
    this.assertEquals("4. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
    date = new Date(2006, 11, 27, 13, 10, 10, 250);
    this.assertEquals("4. Quartal 06",
            gadgets.i18n.formatDateTime("QQQQ yy", date));
};

DateTimeFormatTest.prototype.testQQyyyy = function() {
    var date = new Date(2006, 0, 27, 13, 10, 10, 250);
    this.assertEquals("Q1 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 1, 27, 13, 10, 10, 250);
    this.assertEquals("Q1 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 2, 27, 13, 10, 10, 250);
    this.assertEquals("Q1 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 3, 27, 13, 10, 10, 250);
    this.assertEquals("Q2 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 4, 27, 13, 10, 10, 250);
    this.assertEquals("Q2 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 5, 27, 13, 10, 10, 250);
    this.assertEquals("Q2 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("Q3 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 7, 27, 13, 10, 10, 250);
    this.assertEquals("Q3 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 8, 27, 13, 10, 10, 250);
    this.assertEquals("Q3 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 9, 27, 13, 10, 10, 250);
    this.assertEquals("Q4 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 10, 27, 13, 10, 10, 250);
    this.assertEquals("Q4 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
    date = new Date(2006, 11, 27, 13, 10, 10, 250);
    this.assertEquals("Q4 2006", gadgets.i18n.formatDateTime("QQ yyyy", date));
};

DateTimeFormatTest.prototype.testMMddyyyyHHmmsszzz = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("07/27/2006 13:10:10 " + timezoneString(date),
            gadgets.i18n.formatDateTime("MM/dd/yyyy HH:mm:ss zzz", date));
};

DateTimeFormatTest.prototype.testMMddyyyyHHmmssZ = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("07/27/2006 13:10:10 " + timezoneStringRFC(date),
            gadgets.i18n.formatDateTime("MM/dd/yyyy HH:mm:ss Z", date));
};

DateTimeFormatTest.prototype.testQuote = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 250);
    this.assertEquals("13 o'clock",
            gadgets.i18n.formatDateTime("HH 'o''clock'", date));
    this.assertEquals("13 oclock",
            gadgets.i18n.formatDateTime("HH 'oclock'", date));
    this.assertEquals("13 '", gadgets.i18n.formatDateTime("HH ''", date));
};

DateTimeFormatTest.prototype.testPredefinedFormat = function() {
    var date = new Date(2006, 7, 4, 13, 49, 24, 000);
    this.assertEquals("Freitag, 4. August 2006",
            gadgets.i18n.formatDateTime(gadgets.i18n.FULL_DATE_FORMAT, date));
    this.assertEquals("4. August 2006",
            gadgets.i18n.formatDateTime(gadgets.i18n.LONG_DATE_FORMAT, date));
    this.assertEquals("04.08.2006",
            gadgets.i18n.formatDateTime(gadgets.i18n.MEDIUM_DATE_FORMAT, date));
    this.assertEquals("04.08.06",
            gadgets.i18n.formatDateTime(gadgets.i18n.SHORT_DATE_FORMAT, date));
    this.assertEquals("13:49 Uhr " + timezoneString(date),
            gadgets.i18n.formatDateTime(gadgets.i18n.FULL_TIME_FORMAT, date));
    this.assertEquals("13:49:24 " + timezoneString(date),
            gadgets.i18n.formatDateTime(gadgets.i18n.LONG_TIME_FORMAT, date));
    this.assertEquals("13:49:24",
            gadgets.i18n.formatDateTime(gadgets.i18n.MEDIUM_TIME_FORMAT, date));
    this.assertEquals("13:49",
            gadgets.i18n.formatDateTime(gadgets.i18n.SHORT_TIME_FORMAT, date));
    this.assertEquals("Freitag, 4. August 2006 13:49 Uhr "
            + timezoneString(date),
            gadgets.i18n.formatDateTime(gadgets.i18n.FULL_DATETIME_FORMAT,
                    date));
    this.assertEquals("4. August 2006 13:49:24 " + timezoneString(date),
            gadgets.i18n.formatDateTime(gadgets.i18n.LONG_DATETIME_FORMAT,
                    date));
    this.assertEquals("04.08.2006 13:49:24",
            gadgets.i18n.formatDateTime(gadgets.i18n.MEDIUM_DATETIME_FORMAT,
                    date));
    this.assertEquals("04.08.06 13:49",
            gadgets.i18n.formatDateTime(gadgets.i18n.SHORT_DATETIME_FORMAT,
                    date));
};

DateTimeFormatTest.prototype.testFractionalSeconds = function() {
    var date = new Date(2006, 6, 27, 13, 10, 10, 256);
    this.assertEquals("10:3", gadgets.i18n.formatDateTime("s:S", date));
    this.assertEquals("10:26", gadgets.i18n.formatDateTime("s:SS", date));
    this.assertEquals("10:256", gadgets.i18n.formatDateTime("s:SSS", date));
    this.assertEquals("10:2560", gadgets.i18n.formatDateTime("s:SSSS", date));
    this.assertEquals("10:25600", gadgets.i18n.formatDateTime("s:SSSSS", date));
};
