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
  ERAS:["\u12d3/\u12d3","\u12d3/\u121d"],
  ERANAMES:["\u12d3/\u12d3","\u12d3/\u121d"],
  NARROWMONTHS:["\u1303","\u134c","\u121b","\u12a4","\u121c","\u1301","\u1301","\u12a6","\u1234","\u12a6","\u1296","\u12f2"],
  MONTHS:["\u1303\u1295\u12e9\u12c8\u122a","\u134c\u1265\u1229\u12c8\u122a","\u121b\u122d\u127d","\u12a4\u1355\u1228\u120d","\u121c\u12ed","\u1301\u1295","\u1301\u120b\u12ed","\u12a6\u1308\u1235\u1275","\u1234\u1355\u1274\u121d\u1260\u122d","\u12a6\u12ad\u1270\u12cd\u1260\u122d","\u1296\u126c\u121d\u1260\u122d","\u12f2\u1234\u121d\u1260\u122d"],
  SHORTMONTHS:["\u1303\u1295\u12e9","\u134c\u1265\u1229","\u121b\u122d\u127d","\u12a4\u1355\u1228","\u121c\u12ed","\u1301\u1295","\u1301\u120b\u12ed","\u12a6\u1308\u1235","\u1234\u1355\u1274","\u12a6\u12ad\u1270","\u1296\u126c\u121d","\u12f2\u1234\u121d"],
  WEEKDAYS:["\u1230\u1295\u1260\u1275 \u12d3\u1263\u12ed","\u1230\u1296","\u1273\u120b\u1238\u1296","\u12a3\u1228\u122d\u1263\u12d3","\u12a8\u121a\u123d","\u1305\u121d\u12d3\u1275","\u1230\u1295\u1260\u1275 \u1295\u12a2\u123d"],
  SHORTWEEKDAYS:["\u1230/\u12d3","\u1230\u1296","\u1273\u120b\u1238","\u12a3\u1228\u122d","\u12a8\u121a\u123d","\u1305\u121d\u12d3","\u1230/\u1295"],
  NARROWWEEKDAYS:["\u1230","\u1230","\u1273","\u12a3","\u12a8","\u1305","\u1230"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u1240\u12f0\u121d \u1230\u122d\u121d\u12d5\u120d","\u1213\u1246 \u1235\u122d\u121d\u12d5\u120d"],
  DATEFORMATS:["EEEE\u1361 dd MMMM \u12ee\u121d yyyy G","dd MMMM yyyy","dd-MMM-yyyy","dd/MM/yy"],
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
