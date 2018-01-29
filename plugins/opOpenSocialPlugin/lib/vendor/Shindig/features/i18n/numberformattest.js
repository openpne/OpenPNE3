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
 * @fileoverview Unit Tests - gadgets.i18n.NumberFormat.
 */

function NumberFormatTest(name) {
    TestCase.call(this, name);
}

NumberFormatTest.inherits(TestCase);

var NumberFormatConstants_en = {
    DECIMAL_SEP:'.',
    GROUP_SEP:',',
    PERCENT:'%',
    ZERO_DIGIT:'0',
    PLUS_SIGN:'+',
    MINUS_SIGN:'-',
    EXP_SYMBOL:'E',
    PERMILL:'\u2030',
    INFINITY:'\u221E',
    NAN:'NaN',
    MONETARY_SEP:'.',
    MONETARY_GROUP_SEP:',',
    DECIMAL_PATTERN:'#,##0.###',
    SCIENTIFIC_PATTERN:'#E0',
    PERCENT_PATTERN:'#,##0%',
    CURRENCY_PATTERN:'\u00A4#,##0.00',
    DEF_CURRENCY_CODE:'USD'
};

NumberFormatTest.prototype.setUp = function() {
    gadgets.i18n.numFormatter_
            = new gadgets.i18n.NumberFormat(NumberFormatConstants_en);
};

NumberFormatTest.prototype.testStandardFormat = function() {
    var str;
    str = gadgets.i18n.formatNumber(gadgets.i18n.CURRENCY_PATTERN, 1234.579);
    this.assertEquals("$1,234.58", str);
    str = gadgets.i18n.formatNumber(gadgets.i18n.DECIMAL_PATTERN, 1234.579);
    this.assertEquals("1,234.579", str);
    str = gadgets.i18n.formatNumber(gadgets.i18n.PERCENT_PATTERN, 1234.579);
    this.assertEquals("123,458%", str);
    str = gadgets.i18n.formatNumber(gadgets.i18n.SCIENTIFIC_PATTERN, 1234.579);
    this.assertEquals("1E3", str);
};

NumberFormatTest.prototype.testBasicParse = function() {
    var value;

    value = gadgets.i18n.parseNumber("0.0000", "123.4579");
    this.assertEquals(123.4579, value);

    value = gadgets.i18n.parseNumber("0.0000", "+123.4579");
    this.assertEquals(123.4579, value);

    value = gadgets.i18n.parseNumber("0.0000", "-123.4579");
    this.assertEquals(-123.4579, value);
};

NumberFormatTest.prototype.testPrefixParse = function() {
    var value;

    value = gadgets.i18n.parseNumber("0.0;(0.0)", "123.4579");
    this.assertEquals(123.4579, value);

    value = gadgets.i18n.parseNumber("0.0;(0.0)", "(123.4579)");
    this.assertEquals(-123.4579, value);
};

NumberFormatTest.prototype.testPrecentParse = function() {
    var value;

    value = gadgets.i18n.parseNumber("0.0;(0.0)", "123.4579%");
    this.assertEquals((123.4579 / 100), value);

    value = gadgets.i18n.parseNumber("0.0;(0.0)", "(%123.4579)");
    this.assertEquals((-123.4579 / 100), value);

    value = gadgets.i18n.parseNumber("0.0;(0.0)", "123.4579\u2030");
    this.assertEquals((123.4579 / 1000), value);

    value = gadgets.i18n.parseNumber("0.0;(0.0)", "(\u2030123.4579)");
    this.assertEquals((-123.4579 / 1000), value);
};

NumberFormatTest.prototype.testPercentAndPerMillAdvance = function() {
    var value;
    var pos = [0];
    value = gadgets.i18n.parseNumber("0", "120%", pos);
    this.assertEquals(1.2, value);
    this.assertEquals(4, pos[0]);
    pos[0] = 0;
    value = gadgets.i18n.parseNumber("0", "120\u2030", pos);
    this.assertEquals(0.12, value);
    this.assertEquals(4, pos[0]);
};

NumberFormatTest.prototype.testInfinityParse = function() {
    var value;

  // gwt need to add those symbols first
    value = gadgets.i18n.parseNumber("0.0;(0.0)", "\u221e");
    this.assertEquals(Number.POSITIVE_INFINITY, value);

    value = gadgets.i18n.parseNumber("0.0;(0.0)", "(\u221e)");
    this.assertEquals(Number.NEGATIVE_INFINITY, value);
};
NumberFormatTest.prototype.testExponentParse = function() {
    var value;

    value = gadgets.i18n.parseNumber("#E0", "1.234E3");
    this.assertEquals(1.234E+3, value);

    value = gadgets.i18n.parseNumber("0.###E0", "1.234E3");
    this.assertEquals(1.234E+3, value);

    value = gadgets.i18n.parseNumber("#E0", "1.2345E4");
    this.assertEquals(12345.0, value);

    value = gadgets.i18n.parseNumber("0E0", "1.2345E4");
    this.assertEquals(12345.0, value);

    value = gadgets.i18n.parseNumber("0E0", "1.2345E+4");
    this.assertEquals(12345.0, value);
};

NumberFormatTest.prototype.testGroupingParse = function() {
    var value;

    value = gadgets.i18n.parseNumber("#,###", "1,234,567,890");
    this.assertEquals(1234567890, value);
    value = gadgets.i18n.parseNumber("#,####", "12,3456,7890");
    this.assertEquals(1234567890, value);

    value = gadgets.i18n.parseNumber("#", "1234567890");
    this.assertEquals(1234567890, value);
};

/**
 * Add as many tests as you like.
 */
NumberFormatTest.prototype.testBasicFormat = function() {
    var str = gadgets.i18n.formatNumber("0.0000", 123.45789179565757);
    this.assertEquals("123.4579", str);
};

NumberFormatTest.prototype.testGrouping = function() {
    var str;

    str = gadgets.i18n.formatNumber("#,###", 1234567890);
    this.assertEquals("1,234,567,890", str);
    str = gadgets.i18n.formatNumber("#,####", 1234567890);
    this.assertEquals("12,3456,7890", str);

    str = gadgets.i18n.formatNumber("#", 1234567890);
    this.assertEquals("1234567890", str);
};

NumberFormatTest.prototype.testPerMill = function() {
    var str;

    str = gadgets.i18n.formatNumber("###.###\u2030", 0.4857);
    this.assertEquals("485.7\u2030", str);
};

NumberFormatTest.prototype.testCurrency = function() {
    var str;

    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;-\u00a4#,##0.00", 1234.56);
    this.assertEquals("$1,234.56", str);
    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;-\u00a4#,##0.00", -1234.56);
    this.assertEquals("-$1,234.56", str);

    str = gadgets.i18n.formatNumber(
            "\u00a4\u00a4 #,##0.00;-\u00a4\u00a4 #,##0.00", 1234.56);
    this.assertEquals("USD 1,234.56", str);
    str = gadgets.i18n.formatNumber(
            "\u00a4\u00a4 #,##0.00;\u00a4\u00a4 -#,##0.00", -1234.56);
    this.assertEquals("USD -1,234.56", str);

    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;-\u00a4#,##0.00",
            1234.56, "BRL");
    this.assertEquals("R$1,234.56", str);
    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;-\u00a4#,##0.00",
            -1234.56, "BRL");
    this.assertEquals("-R$1,234.56", str);

    str = gadgets.i18n.formatNumber(
            "\u00a4\u00a4 #,##0.00;(\u00a4\u00a4 #,##0.00)", 1234.56, "BRL");
    this.assertEquals("BRL 1,234.56", str);
    str = gadgets.i18n.formatNumber(
            "\u00a4\u00a4 #,##0.00;(\u00a4\u00a4 #,##0.00)", -1234.56, "BRL");
    this.assertEquals("(BRL 1,234.56)", str);
};

NumberFormatTest.prototype.testQuotes = function() {
    var str;

    str = gadgets.i18n.formatNumber("a'fo''o'b#", 123);
    this.assertEquals("afo'ob123", str);

    str = gadgets.i18n.formatNumber("a''b#", 123);
    this.assertEquals("a'b123", str);
};

NumberFormatTest.prototype.testZeros = function() {
    var str;

    str = gadgets.i18n.formatNumber("#.#", 0);
    this.assertEquals("0", str);
    str = gadgets.i18n.formatNumber("#.", 0);
    this.assertEquals("0.", str);
    str = gadgets.i18n.formatNumber(".#", 0);
    this.assertEquals(".0", str);
    str = gadgets.i18n.formatNumber("#", 0);
    this.assertEquals("0", str);

    str = gadgets.i18n.formatNumber("#0.#", 0);
    this.assertEquals("0", str);
    str = gadgets.i18n.formatNumber("#0.", 0);
    this.assertEquals("0.", str);
    str = gadgets.i18n.formatNumber("#.0", 0);
    this.assertEquals(".0", str);
    str = gadgets.i18n.formatNumber("#", 0);
    this.assertEquals("0", str);
    str = gadgets.i18n.formatNumber("000", 0);
    this.assertEquals("000", str);
};

NumberFormatTest.prototype.testExponential = function() {
    var str;

    str = gadgets.i18n.formatNumber("0.####E0", 0.01234);
    this.assertEquals("1.234E-2", str);
    str = gadgets.i18n.formatNumber("00.000E00", 0.01234);
    this.assertEquals("12.340E-03", str);
    str = gadgets.i18n.formatNumber("##0.######E000", 0.01234);
    this.assertEquals("12.34E-003", str);
    str = gadgets.i18n.formatNumber("0.###E0;[0.###E0]", 0.01234);
    this.assertEquals("1.234E-2", str);

    str = gadgets.i18n.formatNumber("0.####E0", 123456789);
    this.assertEquals("1.2346E8", str);
    str = gadgets.i18n.formatNumber("00.000E00", 123456789);
    this.assertEquals("12.346E07", str);
    str = gadgets.i18n.formatNumber("##0.######E000", 123456789);
    this.assertEquals("123.456789E006", str);
    str = gadgets.i18n.formatNumber("0.###E0;[0.###E0]", 123456789);
    this.assertEquals("1.235E8", str);

    str = gadgets.i18n.formatNumber("0.####E0", 1.23e300);
    this.assertEquals("1.23E300", str);
    str = gadgets.i18n.formatNumber("00.000E00", 1.23e300);
    this.assertEquals("12.300E299", str);
    str = gadgets.i18n.formatNumber("##0.######E000", 1.23e300);
    this.assertEquals("1.23E300", str);
    str = gadgets.i18n.formatNumber("0.###E0;[0.###E0]", 1.23e300);
    this.assertEquals("1.23E300", str);

    str = gadgets.i18n.formatNumber("0.####E0", -3.141592653e-271);
    this.assertEquals("-3.1416E-271", str);
    str = gadgets.i18n.formatNumber("00.000E00", -3.141592653e-271);
    this.assertEquals("-31.416E-272", str);
    str = gadgets.i18n.formatNumber("##0.######E000", -3.141592653e-271);
    this.assertEquals("-314.159265E-273", str);
    str = gadgets.i18n.formatNumber("0.###E0;[0.###E0]", -3.141592653e-271);
    this.assertEquals("[3.142E-271]", str);

    str = gadgets.i18n.formatNumber("0.####E0", 0);
    this.assertEquals("0E0", str);
    str = gadgets.i18n.formatNumber("00.000E00", 0);
    this.assertEquals("00.000E00", str);
    str = gadgets.i18n.formatNumber("##0.######E000", 0);
    this.assertEquals("0E000", str);
    str = gadgets.i18n.formatNumber("0.###E0;[0.###E0]", 0);
    this.assertEquals("0E0", str);

    str = gadgets.i18n.formatNumber("0.####E0", -1);
    this.assertEquals("-1E0", str);
    str = gadgets.i18n.formatNumber("00.000E00", -1);
    this.assertEquals("-10.000E-01", str);
    str = gadgets.i18n.formatNumber("##0.######E000", -1);
    this.assertEquals("-1E000", str);
    str = gadgets.i18n.formatNumber("0.###E0;[0.###E0]", -1);
    this.assertEquals("[1E0]", str);

    str = gadgets.i18n.formatNumber("0.####E0", 1);
    this.assertEquals("1E0", str);
    str = gadgets.i18n.formatNumber("00.000E00", 1);
    this.assertEquals("10.000E-01", str);
    str = gadgets.i18n.formatNumber("##0.######E000", 1);
    this.assertEquals("1E000", str);
    str = gadgets.i18n.formatNumber("0.###E0;[0.###E0]", 1);
    this.assertEquals("1E0", str);

    str = gadgets.i18n.formatNumber("#E0", 12345.0);
  //assertEquals(".1E5", str);
    this.assertEquals("1E4", str);
    str = gadgets.i18n.formatNumber("0E0", 12345.0);
    this.assertEquals("1E4", str);
    str = gadgets.i18n.formatNumber("##0.###E0", 12345.0);
    this.assertEquals("12.345E3", str);
    str = gadgets.i18n.formatNumber("##0.###E0", 12345.00001);
    this.assertEquals("12.345E3", str);
    str = gadgets.i18n.formatNumber("##0.###E0", 12345);
    this.assertEquals("12.345E3", str);

    str = gadgets.i18n.formatNumber("##0.####E0", 789.12345e-9);
    this.assertEquals("789.1235E-9", str);
    str = gadgets.i18n.formatNumber("##0.####E0", 780.e-9);
    this.assertEquals("780E-9", str);
    str = gadgets.i18n.formatNumber(".###E0", 45678.0);
    this.assertEquals(".457E5", str);
    str = gadgets.i18n.formatNumber(".###E0", 0);
    this.assertEquals(".0E0", str);

    str = gadgets.i18n.formatNumber("#E0", 45678000);
    this.assertEquals("5E7", str);
    str = gadgets.i18n.formatNumber("##E0", 45678000);
    this.assertEquals("46E6", str);
    str = gadgets.i18n.formatNumber("####E0", 45678000);
    this.assertEquals("4568E4", str);
    str = gadgets.i18n.formatNumber("0E0", 45678000);
    this.assertEquals("5E7", str);
    str = gadgets.i18n.formatNumber("00E0", 45678000);
    this.assertEquals("46E6", str);
    str = gadgets.i18n.formatNumber("000E0", 45678000);
    this.assertEquals("457E5", str);
    str = gadgets.i18n.formatNumber("###E0", 0.0000123);
    this.assertEquals("12E-6", str);
    str = gadgets.i18n.formatNumber("###E0", 0.000123);
    this.assertEquals("123E-6", str);
    str = gadgets.i18n.formatNumber("###E0", 0.00123);
    this.assertEquals("1E-3", str);
    str = gadgets.i18n.formatNumber("###E0", 0.0123);
    this.assertEquals("12E-3", str);
    str = gadgets.i18n.formatNumber("###E0", 0.123);
    this.assertEquals("123E-3", str);
    str = gadgets.i18n.formatNumber("###E0", 1.23);
    this.assertEquals("1E0", str);
    str = gadgets.i18n.formatNumber("###E0", 12.3);
    this.assertEquals("12E0", str);
    str = gadgets.i18n.formatNumber("###E0", 123.0);
    this.assertEquals("123E0", str);
    str = gadgets.i18n.formatNumber("###E0", 1230.0);
    this.assertEquals("1E3", str);
};

NumberFormatTest.prototype.testGroupingParse2 = function() {
    var value;

    value = gadgets.i18n.parseNumber("#,###", "1,234,567,890");
    this.assertEquals(1234567890, value);
    value = gadgets.i18n.parseNumber("#,####", "12,3456,7890");
    this.assertEquals(1234567890, value);

    value = gadgets.i18n.parseNumber("#", "1234567890");
    this.assertEquals(1234567890, value);
};

NumberFormatTest.prototype.testApis = function() {
    var str;

    str = gadgets.i18n.formatNumber("#,###", 1234567890);
    this.assertEquals("1,234,567,890", str);

    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;-\u00a4#,##0.00", 1234.56);
    this.assertEquals("$1,234.56", str);
    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;(\u00a4#,##0.00)",
            -1234.56);
    this.assertEquals("($1,234.56)", str);

    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;-\u00a4#,##0.00",
            1234.56, "SEK");
    this.assertEquals("kr1,234.56", str);
    str = gadgets.i18n.formatNumber("\u00a4#,##0.00;(\u00a4#,##0.00)",
            -1234.56, "SEK");
    this.assertEquals("(kr1,234.56)", str);
};
