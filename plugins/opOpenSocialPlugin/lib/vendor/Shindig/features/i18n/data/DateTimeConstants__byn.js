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
  ERAS:["\u12ed\u1305","\u12a3\u12f5"],
  ERANAMES:["\u12ed\u1305","\u12a3\u12f5"],
  NARROWMONTHS:["\u120d","\u12ab","\u12ad","\u134b","\u12ad","\u121d","\u12b0","\u121b","\u12eb","\u1218","\u121d","\u1270"],
  MONTHS:["\u120d\u12f0\u1275\u122a","\u12ab\u1265\u12bd\u1265\u1272","\u12ad\u1265\u120b","\u134b\u1305\u12ba\u122a","\u12ad\u1262\u1245\u122a","\u121d\u12aa\u12a4\u120d \u1275\u131f\u1292\u122a","\u12b0\u122d\u12a9","\u121b\u122d\u12eb\u121d \u1275\u122a","\u12eb\u12b8\u1292 \u1218\u1233\u1245\u1208\u122a","\u1218\u1270\u1209","\u121d\u12aa\u12a4\u120d \u1218\u123d\u12c8\u122a","\u1270\u1215\u1233\u1235\u122a"],
  SHORTMONTHS:["\u120d\u12f0\u1275","\u12ab\u1265\u12bd","\u12ad\u1265\u120b","\u134b\u1305\u12ba","\u12ad\u1262\u1245","\u121d/\u1275","\u12b0\u122d","\u121b\u122d\u12eb","\u12eb\u12b8\u1292","\u1218\u1270\u1209","\u121d/\u121d","\u1270\u1215\u1233"],
  WEEKDAYS:["\u1230\u1295\u1260\u122d \u1245\u12f3\u12c5","\u1230\u1291","\u1230\u120a\u131d","\u1208\u1313 \u12c8\u122a \u1208\u1265\u12cb","\u12a3\u121d\u12f5","\u12a3\u122d\u1265","\u1230\u1295\u1260\u122d \u123d\u1313\u12c5"],
  SHORTWEEKDAYS:["\u1230/\u1245","\u1230\u1291","\u1230\u120a\u131d","\u1208\u1313","\u12a3\u121d\u12f5","\u12a3\u122d\u1265","\u1230/\u123d"],
  NARROWWEEKDAYS:["\u1230","\u1230","\u1230","\u1208","\u12a3","\u12a3","\u1230"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u134b\u12f1\u1235 \u1303\u1265","\u134b\u12f1\u1235 \u12f0\u121d\u1262"],
  DATEFORMATS:["EEEE\u1361 dd MMMM \u130d\u122d\u130b yyyy G","dd MMMM yyyy","dd-MMM-yyyy","dd/MM/yy"],
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
