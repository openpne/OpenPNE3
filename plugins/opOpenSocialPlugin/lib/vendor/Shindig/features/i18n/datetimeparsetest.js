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

function DateTimeParseTest(name) {
    TestCase.call(this, name);
}

DateTimeParseTest.inherits(TestCase);

var DateTimeConstants_en = {
    ERAS:["BC", "AD"],
    ERANAMES:["Before Christ", "Anno Domini"],
    NARROWMONTHS:["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
    MONTHS:["January", "February", "March", "April", "May", "June", "July",
        "August", "September", "October", "November", "December"],
    SHORTMONTHS:["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep",
        "Oct", "Nov", "Dec"],
    WEEKDAYS:["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday",
        "Saturday"],
    SHORTWEEKDAYS:["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    NARROWWEEKDAYS:["S", "M", "T", "W", "T", "F", "S"],
    SHORTQUARTERS:["Q1", "Q2", "Q3", "Q4"],
    QUARTERS:["1st quarter", "2nd quarter", "3rd quarter", "4th quarter"],
    AMPMS:["AM", "PM"],
    DATEFORMATS:["EEEE, MMMM d, yyyy", "MMMM d, yyyy", "MMM d, yyyy", "M/d/yy"],
    TIMEFORMATS:["h:mm:ss a v", "h:mm:ss a z", "h:mm:ss a", "h:mm a"],
    ZONESTRINGS:null
};

DateTimeConstants_en.STANDALONENARROWMONTHS =
DateTimeConstants_en.NARROWMONTHS;
DateTimeConstants_en.STANDALONEMONTHS = DateTimeConstants_en.MONTHS;
DateTimeConstants_en.STANDALONESHORTMONTHS = DateTimeConstants_en.SHORTMONTHS;
DateTimeConstants_en.STANDALONEWEEKDAYS = DateTimeConstants_en.WEEKDAYS;
DateTimeConstants_en.STANDALONESHORTWEEKDAYS =
DateTimeConstants_en.SHORTWEEKDAYS;
DateTimeConstants_en.STANDALONENARROWWEEKDAYS =
DateTimeConstants_en.NARROWWEEKDAYS;

DateTimeParseTest.prototype.setUp = function() {
    gadgets.i18n.dtParser_
            = new gadgets.i18n.DateTimeParse(DateTimeConstants_en);
};

DateTimeParseTest.prototype.testNegativeYear = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd, yyyy", "11/22, 1999",
            0, date) > 0);
    this.assertEquals(1999, date.getFullYear());
    this.assertEquals(11 - 1, date.getMonth());
    this.assertEquals(22, date.getDate());

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd, yyyy", "11/22, -1999",
            0, date) > 0);
    this.assertEquals(-1999, date.getFullYear());
    this.assertEquals(11 - 1, date.getMonth());
    this.assertEquals(22, date.getDate());
};

DateTimeParseTest.prototype.testEra = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd, yyyyG", "11/22, 1999BC",
            0, date) > 0);
    this.assertEquals(-1998, date.getFullYear());
    this.assertEquals(11 - 1, date.getMonth());
    this.assertEquals(22, date.getDate());

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd, yyyyG", "11/22, 1BC",
            0, date) > 0);
    this.assertEquals(0, date.getFullYear());
    this.assertEquals(11 - 1, date.getMonth());
    this.assertEquals(22, date.getDate());

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd, yyyyG", "11/22, 1999AD",
            0, date) > 0);
    this.assertEquals(1999, date.getFullYear());
    this.assertEquals(11 - 1, date.getMonth());
    this.assertEquals(22, date.getDate());
};

DateTimeParseTest.prototype.testFractionalSeconds = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("hh:mm:ss.SSS", "11:12:13.956",
            0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(12, date.getMinutes());
    this.assertEquals(13, date.getSeconds());
    this.assertEquals(956, date.getTime() % 1000);

    this.assertTrue(gadgets.i18n.parseDateTime("hh:mm:ss.SSS", "11:12:13.95",
            0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(12, date.getMinutes());
    this.assertEquals(13, date.getSeconds());
    this.assertEquals(950, date.getTime() % 1000);

    this.assertTrue(gadgets.i18n.parseDateTime("hh:mm:ss.SSS", "11:12:13.9",
            0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(12, date.getMinutes());
    this.assertEquals(13, date.getSeconds());
    this.assertEquals(900, date.getTime() % 1000);
};

DateTimeParseTest.prototype.testAmbiguousYear = function() {
    var date = new Date();

  // assume this year is 2006, year 27 to 99 will be interpret as 1927 to 1999
    // year 00 to 25 will be 2000 to 2025. Year 26 can be either 1926 or 2026
    // depend on the exact time.
    var org_date = new Date();
    org_date.setFullYear(org_date.getFullYear() + 20);

  // following 2 lines only works in 2006. Keep them here as they explained
    // our intention better.
    //assertTrue(DateTimeParse.parse("01/01/26", 0, "MM/dd/yy", date) > 0);
    //assertTrue(date.getYear() == 2026 - 1900);

    // rewrite so that it works in any year.
    org_date.setMonth(0);
    org_date.setDate(1);
    org_date.setHours(0);
    org_date.setMinutes(0);
    org_date.setSeconds(0);
    org_date.setMilliseconds(1);
    var str = '01/01/' + (org_date.getFullYear() % 100);
    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yy", str, 0, date) > 0);
    this.assertEquals(org_date.getFullYear(), date.getFullYear());

  // following 2 lines only works in 2006. Keep them here as they explained
    // our intention better.
    //assertTrue(DateTimeParse.parse("MM/dd/yy", "12/30/26", 0, date) > 0);
    //assertTrue(date.getYear() == 1926 - 1900);

    // rewrite so that it works in any year.
    org_date.setMonth(11);
    org_date.setDate(31);
    org_date.setHours(23);
    org_date.setMinutes(59);
    org_date.setSeconds(59);
    org_date.setMilliseconds(999);
    str = '12/31/' + (org_date.getFullYear() % 100);
    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yy", str, 0, date) > 0);
    this.assertEquals(org_date.getFullYear(), date.getFullYear() + 100);

    this.assertTrue(
            gadgets.i18n.parseDateTime("yy,MM,dd", "2097,07,21", 0, date) > 0);
    this.assertEquals(2097, date.getFullYear());

  // Test the ability to move the disambiguation century
    gadgets.i18n.DateTimeParse.ambiguousYearCenturyStart = 60;

    org_date.setMonth(0);
    org_date.setDate(1);
    org_date.setHours(0);
    org_date.setMinutes(0);
    org_date.setSeconds(0);
    org_date.setMilliseconds(1);
    str = '01/01/' + (org_date.getFullYear() % 100);
    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yy", str, 0, date) > 0);

    this.assertEquals(org_date.getFullYear(), date.getFullYear());

  // Increment org_date 20 more years
    org_date.setFullYear(date.getFullYear() + 20);
    str = '01/01/' + (org_date.getFullYear() % 100);
    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yy", str, 0, date) > 0);
    this.assertEquals(org_date.getFullYear(), date.getFullYear());

    org_date.setFullYear(date.getFullYear() + 21);
    str = '01/01/' + (org_date.getFullYear() % 100);
    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yy", str, 0, date) > 0);
    this.assertEquals(org_date.getFullYear(), date.getFullYear() + 100);

  // Reset parameter for other test cases
    gadgets.i18n.DateTimeParse.ambiguousYearCenturyStart = 80;
};

DateTimeParseTest.prototype.testLeapYear = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("MMdd, yyyy", "0229, 2001",
            0, date) > 0);
    this.assertEquals(3 - 1, date.getMonth());
    this.assertEquals(1, date.getDate());

    this.assertTrue(gadgets.i18n.parseDateTime("MMdd, yyyy", "0229, 2000",
            0, date) > 0);
    this.assertEquals(2 - 1, date.getMonth());
    this.assertEquals(29, date.getDate());
};

DateTimeParseTest.prototype.testAbutField = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("hhmm", "1122", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmm", "122", 0, date) > 0);
    this.assertEquals(1, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(
            gadgets.i18n.parseDateTime("hhmmss", "112233", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());
    this.assertEquals(33, date.getSeconds());

    this.assertTrue(
            gadgets.i18n.parseDateTime("hhmmss", "12233", 0, date) > 0);
    this.assertEquals(1, date.getHours());
    this.assertEquals(22, date.getMinutes());
    this.assertEquals(33, date.getSeconds());

    this.assertTrue(
            gadgets.i18n.parseDateTime("yyyyMMdd", "19991202", 0, date) > 0);
    this.assertEquals(1999, date.getFullYear());
    this.assertEquals(12 - 1, date.getMonth());
    this.assertEquals(02, date.getDate());

    this.assertTrue(
            gadgets.i18n.parseDateTime("yyyyMMdd", "9991202", 0, date) > 0);
    this.assertTrue(date.getFullYear() == 999);
    this.assertEquals(12 - 1, date.getMonth());
    this.assertEquals(02, date.getDate());

    this.assertTrue(
            gadgets.i18n.parseDateTime("yyyyMMdd", "991202", 0, date) > 0);
    this.assertEquals(99, date.getFullYear());
    this.assertEquals(12 - 1, date.getMonth());
    this.assertEquals(02, date.getDate());

    this.assertTrue(
            gadgets.i18n.parseDateTime("yyyyMMdd", "91202", 0, date) > 0);
    this.assertEquals(9, date.getFullYear());
    this.assertEquals(12 - 1, date.getMonth());
    this.assertEquals(02, date.getDate());
};

DateTimeParseTest.prototype.testYearParsing = function() {
    var date = new Date();

    this.assertTrue(
            gadgets.i18n.parseDateTime("yyMMdd", "991202", 0, date) > 0);
    this.assertEquals(1999, date.getFullYear());
    this.assertEquals(12 - 1, date.getMonth());
    this.assertEquals(02, date.getDate());

    this.assertTrue(
            gadgets.i18n.parseDateTime("yyyyMMdd", "20051202", 0, date) > 0);
    this.assertEquals(2005, date.getFullYear());
    this.assertEquals(12 - 1, date.getMonth());
    this.assertEquals(02, date.getDate());
};

DateTimeParseTest.prototype.testHourParsing_hh = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("hhmm", "0022", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmm", "1122", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmm", "1222", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmm", "2322", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmm", "2422", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "0022am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "1122am", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "1222am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "2322am", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "2422am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "0022pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "1122pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "1222pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "2322pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("hhmma", "2422pm", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());
};

DateTimeParseTest.prototype.testHourParsing_KK = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("KKmm", "0022", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmm", "1122", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmm", "1222", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmm", "2322", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmm", "2422", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());


    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "0022am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "1122am", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "1222am", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "2322am", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "2422am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "0022pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "1122pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "1222pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "2322pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("KKmma", "2422pm", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());
};

DateTimeParseTest.prototype.testHourParsing_kk = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("kkmm", "0022", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmm", "1122", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmm", "1222", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmm", "2322", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmm", "2422", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "0022am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "1122am", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "1222am", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "2322am", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "2422am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "0022pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "1122pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "1222pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "2322pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("kkmma", "2422pm", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());
};

DateTimeParseTest.prototype.testHourParsing_HH = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("HHmm", "0022", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmm", "1122", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmm", "1222", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmm", "2322", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmm", "2422", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "0022am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "1122am", 0, date) > 0);
    this.assertEquals(11, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "1222am", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "2322am", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "2422am", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "0022pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "1122pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "1222pm", 0, date) > 0);
    this.assertEquals(12, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "2322pm", 0, date) > 0);
    this.assertEquals(23, date.getHours());
    this.assertEquals(22, date.getMinutes());

    this.assertTrue(gadgets.i18n.parseDateTime("HHmma", "2422pm", 0, date) > 0);
    this.assertEquals(00, date.getHours());
    this.assertEquals(22, date.getMinutes());
};

DateTimeParseTest.prototype.testEnglishDate = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("yyyy MMM dd hh:mm",
            "2006 Jul 10 15:44", 0, date) > 0);
    this.assertEquals(2006, date.getFullYear());
    this.assertEquals(7 - 1, date.getMonth());
    this.assertEquals(10, date.getDate());
    this.assertEquals(15, date.getHours());
    this.assertEquals(44, date.getMinutes());
};

DateTimeParseTest.prototype.testTimeZone = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yyyy, hh:mm:ss zzz",
            "07/21/2003, 11:22:33 GMT-0700", 0,
            date) > 0);
    var hour_GmtMinus07 = date.getHours();

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yyyy, hh:mm:ss zzz",
            "07/21/2003, 11:22:33 GMT-0600", 0,
            date) > 0);
    var hour_GmtMinus06 = date.getHours();
    this.assertEquals(1, (hour_GmtMinus07 + 24 - hour_GmtMinus06) % 24);

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yyyy, hh:mm:ss zzz",
            "07/21/2003, 11:22:33 GMT-0800", 0,
            date) > 0);
    var hour_GmtMinus08 = date.getHours();
    this.assertEquals(1, (hour_GmtMinus08 + 24 - hour_GmtMinus07) % 24);

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yyyy, HH:mm:ss zzz",
            "07/21/2003, 23:22:33 GMT-0800", 0,
            date) > 0);
    this.assertEquals((date.getHours() + 24 - hour_GmtMinus07) % 24, 13);

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yyyy, HH:mm:ss zzz",
            "07/21/2003, 11:22:33 GMT+0800", 0,
            date) > 0);
    var hour_Gmt08 = date.getHours();
    this.assertEquals(16, (hour_GmtMinus08 + 24 - hour_Gmt08) % 24);

    this.assertTrue(gadgets.i18n.parseDateTime("MM/dd/yyyy, HH:mm:ss zzz",
            "07/21/2003, 11:22:33 GMT0800", 0,
            date) > 0);
    this.assertEquals(hour_Gmt08, date.getHours());
};

DateTimeParseTest.prototype.testWeekDay = function() {
    var date = new Date();

    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/dd/yyyy",
            "Wednesday, 08/16/2006", 0, date) > 0);
    this.assertEquals(2006, date.getFullYear());
    this.assertEquals(8 - 1, date.getMonth());
    this.assertEquals(16, date.getDate());
    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/dd/yyyy",
            "Tuesday, 08/16/2006", 0, date) == 0);
    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/dd/yyyy",
            "Thursday, 08/16/2006", 0, date) == 0);
    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/dd/yyyy",
            "Wed, 08/16/2006", 0, date) > 0);
    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/dd/yyyy",
            "Wasdfed, 08/16/2006", 0, date) == 0);

    date.setDate(25);
    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/yyyy",
            "Wed, 09/2006", 0, date) > 0);
    this.assertEquals(27, date.getDate());

    date.setDate(30);
    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/yyyy",
            "Wed, 09/2006", 0, date) > 0);
    this.assertEquals(27, date.getDate());
    date.setDate(30);
    this.assertTrue(gadgets.i18n.parseDateTime("EEEE, MM/yyyy",
            "Mon, 09/2006", 0, date) > 0);
    this.assertEquals(25, date.getDate());

};
