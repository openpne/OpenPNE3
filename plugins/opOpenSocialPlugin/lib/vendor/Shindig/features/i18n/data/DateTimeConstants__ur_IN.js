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
  ERAS:["\u0642 \u0645","\u0639\u064a\u0633\u0648\u06cc \u0633\u0646"],
  ERANAMES:["\u0642\u0628\u0644 \u0645\u0633\u064a\u062d","\u0639\u064a\u0633\u0648\u06cc \u0633\u0646"],
  NARROWMONTHS:["\u062c","\u0641","\u0645","\u0627","\u0645","\u062c","\u062c","\u0627","\u0633","\u0627","\u0646","\u062f"],
  MONTHS:["\u062c\u0646\u0648\u0631\u06cc","\u0641\u0631\u0648\u0631\u06cc","\u0645\u0627\u0631 \u0686","\u0627\u067e\u0631\u064a\u0644","\u0645\u0626","\u062c\u0648\u0646","\u062c\u0648\u0644\u0627\u0626","\u0627\u06af\u0633\u062a","\u0633\u062a\u0645\u0628\u0631","\u0627\u06a9\u062a\u0648\u0628\u0631","\u0646\u0648\u0645\u0628\u0631","\u062f\u0633\u0645\u0628\u0631"],
  SHORTMONTHS:["\u062c\u0646\u0648\u0631\u06cc","\u0641\u0631\u0648\u0631\u06cc","\u0645\u0627\u0631 \u0686","\u0627\u067e\u0631\u064a\u0644","\u0645\u0626","\u062c\u0648\u0646","\u062c\u0648\u0644\u0627\u0626","\u0627\u06af\u0633\u062a","\u0633\u062a\u0645\u0628\u0631","\u0627\u06a9\u062a\u0648\u0628\u0631","\u0646\u0648\u0645\u0628\u0631","\u062f\u0633\u0645\u0628\u0631"],
  WEEKDAYS:["\u0627\u062a\u0648\u0627\u0631","\u067e\u064a\u0631","\u0645\u0646\u06af\u0644","\u0628\u062f\u0647","\u062c\u0645\u0639\u0631\u0627\u062a","\u062c\u0645\u0639\u06c1","\u06c1\u0641\u062a\u06c1"],
  SHORTWEEKDAYS:["\u0627\u062a\u0648\u0627\u0631","\u067e\u064a\u0631","\u0645\u0646\u06af\u0644","\u0628\u062f\u0647","\u062c\u0645\u0639\u0631\u0627\u062a","\u062c\u0645\u0639\u06c1","\u06c1\u0641\u062a\u06c1"],
  NARROWWEEKDAYS:["\u0627","\u067e","\u0645","\u0628","\u062c","\u062c","\u06c1"],
  STANDALONENARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["1\u0633\u06c1 \u0645\u0627\u06c1\u06cc","2\u0633\u06c1 \u0645\u0627\u06c1\u06cc","3\u0633\u06c1 \u0645\u0627\u06c1\u06cc","4\u0633\u06c1 \u0645\u0627\u06c1\u06cc"],
  QUARTERS:["\u067e\u06c1\u0644\u06cc \u0633\u06c1 \u0645\u0627\u06c1\u06cc","\u062f\u0648\u0633\u0631\u06cc \u0633\u06c1 \u0645\u0627\u06c1\u06cc","\u062a\u064a\u0633\u0631\u06cc \u0633\u06c1 \u0645\u0627\u06c1\u06cc","\u0686\u0648\u062a\u0647\u06cc \u0633\u06c1 \u0645\u0627\u06c1\u06cc"],
  AMPMS:["\u0642\u0628\u0644 \u062f\u0648\u067e\u06c1\u0631","\u0628\u0639\u062f \u062f\u0648\u067e\u06c1\u0631"],
  DATEFORMATS:["EEEE, d, MMMM yyyy","d, MMMM yyyy","d, MMM yyyy","d/M/yy"],
  TIMEFORMATS:["h:mm:ss a v","h:mm:ss a z","h:mm:ss a","h:mm a"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [6, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
