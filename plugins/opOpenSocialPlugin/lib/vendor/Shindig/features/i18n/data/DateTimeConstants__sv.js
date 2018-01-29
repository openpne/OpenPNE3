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
  ERAS:["f.Kr.","e.Kr."],
  ERANAMES:["f\u00f6re Kristus","efter Kristus"],
  NARROWMONTHS:["J","F","M","A","M","J","J","A","S","O","N","D"],
  MONTHS:["januari","februari","mars","april","maj","juni","juli","augusti","september","oktober","november","december"],
  SHORTMONTHS:["jan","feb","mar","apr","maj","jun","jul","aug","sep","okt","nov","dec"],
  WEEKDAYS:["s\u00f6ndag","m\u00e5ndag","tisdag","onsdag","torsdag","fredag","l\u00f6rdag"],
  SHORTWEEKDAYS:["s\u00f6n","m\u00e5n","tis","ons","tors","fre","l\u00f6r"],
  NARROWWEEKDAYS:["S","M","T","O","T","F","L"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["1:a kvartalet","2:a kvartalet","3:e kvartalet","4:e kvartalet"],
  AMPMS:["fm","em"],
  DATEFORMATS:["EEEE 'den' d MMMM yyyy","d MMMM yyyy","d MMM yyyy","yyyy-MM-dd"],
  TIMEFORMATS:["'kl'. HH.mm.ss v","HH.mm.ss z","HH.mm.ss","HH.mm"],
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
