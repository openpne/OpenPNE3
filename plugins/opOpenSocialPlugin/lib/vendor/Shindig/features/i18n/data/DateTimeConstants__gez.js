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
  NARROWMONTHS:["\u1320","\u12a8","\u1218","\u12a0","\u130d","\u1220","\u1210","\u1290","\u12a8","\u1320","\u1280","\u1280"],
  MONTHS:["\u1320\u1210\u1228","\u12a8\u1270\u1270","\u1218\u1308\u1260","\u12a0\u1280\u12d8","\u130d\u1295\u1263\u1275","\u1220\u1295\u12e8","\u1210\u1218\u1208","\u1290\u1210\u1230","\u12a8\u1228\u1218","\u1320\u1240\u1218","\u1280\u12f0\u1228","\u1280\u1220\u1220"],
  SHORTMONTHS:["\u1320\u1210\u1228","\u12a8\u1270\u1270","\u1218\u1308\u1260","\u12a0\u1280\u12d8","\u130d\u1295\u1263","\u1220\u1295\u12e8","\u1210\u1218\u1208","\u1290\u1210\u1230","\u12a8\u1228\u1218","\u1320\u1240\u1218","\u1280\u12f0\u1228","\u1280\u1220\u1220"],
  WEEKDAYS:["\u12a5\u1281\u12f5","\u1230\u1291\u12ed","\u1220\u1209\u1235","\u122b\u1265\u12d5","\u1210\u1219\u1235","\u12d3\u122d\u1260","\u1240\u12f3\u121a\u1275"],
  SHORTWEEKDAYS:["\u12a5\u1281\u12f5","\u1230\u1291\u12ed","\u1220\u1209\u1235","\u122b\u1265\u12d5","\u1210\u1219\u1235","\u12d3\u122d\u1260","\u1240\u12f3\u121a"],
  NARROWWEEKDAYS:["\u12a5","\u1230","\u1220","\u122b","\u1210","\u12d3","\u1240"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u133d\u1263\u1215","\u121d\u1234\u1275"],
  DATEFORMATS:["EEEE\u1365 dd MMMM \u1218\u12d3\u120d\u1275 yyyy G","dd MMMM yyyy","dd-MMM-yyyy","dd/MM/yy"],
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
