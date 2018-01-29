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
  ERAS:["QK","WK"],
  ERANAMES:["Qabel Kristu","Wara Kristu"],
  NARROWMONTHS:["J","F","M","A","M","\u0120","L","A","S","O","N","D"],
  MONTHS:["Jannar","Frar","Marzu","April","Mejju","\u0120unju","Lulju","Awissu","Settembru","Ottubru","Novembru","Di\u010bembru"],
  SHORTMONTHS:["Jan","Fra","Mar","Apr","Mej","\u0120un","Lul","Awi","Set","Ott","Nov","Di\u010b"],
  WEEKDAYS:["Il-\u0126add","It-Tnejn","It-Tlieta","L-Erbg\u0127a","Il-\u0126amis","Il-\u0120img\u0127a","Is-Sibt"],
  SHORTWEEKDAYS:["\u0126ad","Tne","Tli","Erb","\u0126am","\u0120im","Sib"],
  NARROWWEEKDAYS:["\u0126","T","T","E","\u0126","\u0120","S"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["K1","K2","K3","K4"],
  AMPMS:["QN","WN"],
  DATEFORMATS:["EEEE, d 'ta'\u2019 MMMM yyyy","d 'ta'\u2019 MMMM yyyy","dd MMM yyyy","dd/MM/yyyy"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 6,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 2
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
