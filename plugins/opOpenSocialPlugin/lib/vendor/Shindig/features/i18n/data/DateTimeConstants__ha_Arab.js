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

var gadgets = gadgets || {};

gadgets.i18n = gadgets.i18n || {};

gadgets.i18n.DateTimeConstants = {
  ERAS:["\u063a\u064e\u0628\u064e\u0646\u0650\u0646\u0652 \u0645\u0650\u0644\u064e\u062f\u0650","\u0645\u0650\u0644\u064e\u062f\u0650"],
  ERANAMES:["\u063a\u064e\u0628\u064e\u0646\u0650\u0646\u0652 \u0645\u0650\u0644\u064e\u062f\u0650","\u0645\u0650\u0644\u064e\u062f\u0650"],
  NARROWMONTHS:["J","F","M","A","M","Y","Y","A","S","O","N","D"],
  MONTHS:["\u062c\u064e\u0646\u064e\u064a\u0652\u0631\u064f","\u06a2\u064e\u0628\u0652\u0631\u064e\u064a\u0652\u0631\u064f","\u0645\u064e\u0631\u0650\u0633\u0652","\u0623\u064e\u06a2\u0652\u0631\u0650\u0644\u064f","\u0645\u064e\u064a\u064f","\u064a\u064f\u0648\u0646\u0650","\u064a\u064f\u0648\u0644\u0650","\u0623\u064e\u063a\u064f\u0633\u0652\u062a\u064e","\u0633\u064e\u062a\u064f\u0645\u0652\u0628\u064e","\u0623\u064f\u0643\u0652\u062a\u0648\u064f\u0628\u064e","\u0646\u064f\u0648\u064e\u0645\u0652\u0628\u064e","\u062f\u0650\u0633\u064e\u0645\u0652\u0628\u064e"],
  SHORTMONTHS:["\u062c\u064e\u0646","\u06a2\u064e\u0628","\u0645\u064e\u0631","\u0623\u064e\u06a2\u0652\u0631","\u0645\u064e\u064a","\u064a\u064f\u0648\u0646","\u064a\u064f\u0648\u0644","\u0623\u064e\u063a\u064f","\u0633\u064e\u062a","\u0623\u064f\u0643\u0652\u062a","\u0646\u064f\u0648","\u062f\u0650\u0633"],
  WEEKDAYS:["\u0644\u064e\u062d\u064e\u062f\u0650","\u0644\u0650\u062a\u0650\u0646\u0650\u0646\u0652","\u062a\u064e\u0644\u064e\u062a\u064e","\u0644\u064e\u0631\u064e\u0628\u064e","\u0623\u064e\u0644\u0652\u062d\u064e\u0645\u0650\u0633\u0652","\u062c\u064f\u0645\u064e\u0639\u064e","\u0623\u064e\u0633\u064e\u0628\u064e\u0631\u0652"],
  SHORTWEEKDAYS:["\u0644\u064e\u062d","\u0644\u0650\u062a","\u062a\u064e\u0644","\u0644\u064e\u0631","\u0623\u064e\u0644\u0652\u062d","\u062c\u064f\u0645","\u0623\u064e\u0633\u064e"],
  NARROWWEEKDAYS:["L","L","T","L","A","J","A"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["A.M.","P.M."],
  DATEFORMATS:["EEEE, d MMMM, yyyy","d MMMM, yyyy","d MMM, yyyy","d/M/yy"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
