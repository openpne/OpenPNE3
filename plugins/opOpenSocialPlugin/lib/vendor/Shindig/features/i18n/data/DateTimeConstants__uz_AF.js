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
  ERAS:["\u0642.\u0645.","\u0645."],
  ERANAMES:["\u0642.\u0645.","\u0645."],
  NARROWMONTHS:["\u042f","\u0424","\u041c","\u0410","\u041c","\u0418","\u0418","\u0410","\u0421","\u041e","\u041d","\u0414"],
  MONTHS:["\u062c\u0646\u0648\u0631\u06cc","\u0641\u0628\u0631\u0648\u0631\u06cc","\u0645\u0627\u0631\u0686","\u0627\u067e\u0631\u06cc\u0644","\u0645\u06cc","\u062c\u0648\u0646","\u062c\u0648\u0644\u0627\u06cc","\u0627\u06af\u0633\u062a","\u0633\u067e\u062a\u0645\u0628\u0631","\u0627\u06a9\u062a\u0648\u0628\u0631","\u0646\u0648\u0645\u0628\u0631","\u062f\u0633\u0645\u0628\u0631"],
  SHORTMONTHS:["\u062c\u0646\u0648","\u0641\u0628\u0631","\u0645\u0627\u0631","\u0627\u067e\u0631","\u0645\u0640\u06cc","\u062c\u0648\u0646","\u062c\u0648\u0644","\u0627\u06af\u0633","\u0633\u067e\u062a","\u0627\u06a9\u062a","\u0646\u0648\u0645","\u062f\u0633\u0645"],
  WEEKDAYS:["\u06cc\u06a9\u0634\u0646\u0628\u0647","\u062f\u0648\u0634\u0646\u0628\u0647","\u0633\u0647\u200c\u0634\u0646\u0628\u0647","\u0686\u0647\u0627\u0631\u0634\u0646\u0628\u0647","\u067e\u0646\u062c\u0634\u0646\u0628\u0647","\u062c\u0645\u0639\u0647","\u0634\u0646\u0628\u0647"],
  SHORTWEEKDAYS:["\u06cc.","\u062f.","\u0633.","\u0686.","\u067e.","\u062c.","\u0634."],
  NARROWWEEKDAYS:["\u042f","\u0414","\u0421","\u0427","\u041f","\u0416","\u0428"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["yyyy \u0646\u0686\u06cc \u06cc\u06cc\u0644 d \u0646\u0686\u06cc MMMM EEEE \u06a9\u0648\u0646\u06cc","d \u0646\u0686\u06cc MMMM yyyy","d MMM yyyy","yyyy/M/d"],
  TIMEFORMATS:["H:mm:ss (v)","H:mm:ss (z)","H:mm:ss","H:mm"],
  FIRSTDAYOFWEEK: 5,
  WEEKENDRANGE: [3, 4],
  FIRSTWEEKCUTOFFDAY: 4
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
