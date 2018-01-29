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
  ERAS:["e.m.a.","m.a.j."],
  ERANAMES:["enne meie aega","meie aja j\u00e4rgi"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["jaanuar","veebruar","m\u00e4rts","aprill","mai","juuni","juuli","august","september","oktoober","november","detsember"],
  SHORTMONTHS:["jaan","veebr","m\u00e4rts","apr","mai","juuni","juuli","aug","sept","okt","nov","dets"],
  WEEKDAYS:["p\u00fchap\u00e4ev","esmasp\u00e4ev","teisip\u00e4ev","kolmap\u00e4ev","neljap\u00e4ev","reede","laup\u00e4ev"],
  SHORTWEEKDAYS:["P","E","T","K","N","R","L"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["1. kvartal","2. kvartal","3. kvartal","4. kvartal"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, d, MMMM yyyy","d MMMM yyyy","dd.MM.yyyy","dd.MM.yy"],
  TIMEFORMATS:["H:mm:ss v","H:mm:ss z","H:mm:ss","H:mm"],
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
