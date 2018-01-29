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
  ERAS:["\u12a0\u12f3 \u12ce\u12f4","\u130d\u122e\u1270\u1273 \u120b\u12ed\u1273"],
  ERANAMES:["\u12a0\u12f3 \u12ce\u12f4","\u130d\u122e\u1270\u1273 \u120b\u12ed\u1273"],
  NARROWMONTHS:["\u1303","\u134c","\u121b","\u12a4","\u121c","\u1301","\u1301","\u12a6","\u1234","\u12a6","\u1296","\u12f2"],
  MONTHS:["\u1303\u1295\u12e9\u12c8\u122a","\u134c\u1265\u1229\u12c8\u122a","\u121b\u122d\u127d","\u12a4\u1355\u1228\u120d","\u121c\u12ed","\u1301\u1295","\u1301\u120b\u12ed","\u12a6\u1308\u1235\u1275","\u1234\u1355\u1274\u121d\u1260\u122d","\u12a6\u12ad\u1270\u12cd\u1260\u122d","\u1296\u126c\u121d\u1260\u122d","\u12f2\u1234\u121d\u1260\u122d"],
  SHORTMONTHS:["\u1303\u1295\u12e9","\u134c\u1265\u1229","\u121b\u122d\u127d","\u12a4\u1355\u1228","\u121c\u12ed","\u1301\u1295","\u1301\u120b\u12ed","\u12a6\u1308\u1235","\u1234\u1355\u1274","\u12a6\u12ad\u1270","\u1296\u126c\u121d","\u12f2\u1234\u121d"],
  WEEKDAYS:["\u12c8\u130b","\u1233\u12ed\u1296","\u121b\u1246\u1233\u129b","\u12a0\u1229\u12cb","\u1203\u1219\u1233","\u12a0\u122d\u1263","\u1244\u122b"],
  SHORTWEEKDAYS:["\u12c8\u130b","\u1233\u12ed\u1296","\u121b\u1246\u1233","\u12a0\u1229\u12cb","\u1203\u1219\u1233","\u12a0\u122d\u1263","\u1244\u122b"],
  NARROWWEEKDAYS:["\u12c8","\u1233","\u121b","\u12a0","\u1203","\u12a0","\u1244"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u121b\u1208\u12f6","\u1243\u121b"],
  DATEFORMATS:["EEEE\u1365 dd MMMM \u130b\u120b\u1233 yyyy G","dd MMMM yyyy","dd-MMM-yyyy","dd/MM/yy"],
  TIMEFORMATS:["h:mm:ss a v","h:mm:ss a z","h:mm:ss a","h:mm a"],
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
