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
  ERAS:["KM","TS"],
  ERANAMES:["ki mu\u02bba","ta\u02bbu \u02bbo S\u012bs\u016b"],
  NARROWMONTHS:["S","F","M","E","M","S","S","A","S","O","N","T"],
  MONTHS:["S\u0101nuali","F\u0113pueli","Ma\u02bbasi","\u02bbEpeleli","M\u0113","Sune","Siulai","\u02bbAokosi","S\u0113pitema","\u02bbOkatopa","N\u014dvema","Tisema"],
  SHORTMONTHS:["S\u0101n","F\u0113p","Ma\u02bba","\u02bbEpe","M\u0113","Sun","Siu","\u02bbAok","S\u0113p","\u02bbOka","N\u014dv","Tis"],
  WEEKDAYS:["S\u0101pate","M\u014dnite","Tusite","Pulelulu","Tu\u02bbapulelulu","Falaite","Tokonaki"],
  SHORTWEEKDAYS:["S\u0101p","M\u014dn","Tus","Pul","Tu\u02bba","Fal","Tok"],
  NARROWWEEKDAYS:["S","M","T","P","T","F","T"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["kuata \u02bbuluaki","kuata ua","kuata tolu","kuata f\u0101"],
  AMPMS:["HH","EA"],
  DATEFORMATS:["EEEE d MMMM yyyy","d MMMM yyyy","d MMM yyyy","dd-MM-yyyy"],
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
