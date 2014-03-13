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
  ERAS:["pred n.l.","n.l."],
  ERANAMES:["pred n.l.","n.l."],
  NARROWMONTHS:["j","f","m","a","m","j","j","a","s","o","n","d"],
  MONTHS:["janu\u00e1r","febru\u00e1r","marec","apr\u00edl","m\u00e1j","j\u00fan","j\u00fal","august","september","okt\u00f3ber","november","december"],
  SHORTMONTHS:["jan","feb","mar","apr","m\u00e1j","j\u00fan","j\u00fal","aug","sep","okt","nov","dec"],
  WEEKDAYS:["Nede\u013ea","Pondelok","Utorok","Streda","\u0160tvrtok","Piatok","Sobota"],
  SHORTWEEKDAYS:["Ne","Po","Ut","St","\u0160t","Pi","So"],
  NARROWWEEKDAYS:["N","P","U","S","\u0160","P","S"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["1. \u0161tvr\u0165rok","2. \u0161tvr\u0165rok","3. \u0161tvr\u0165rok","4. \u0161tvr\u0165rok"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, d. MMMM yyyy","d. MMMM yyyy","d.M.yyyy","d.M.yyyy"],
  TIMEFORMATS:["H:mm:ss v","H:mm:ss z","H:mm:ss","H:mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 3
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
