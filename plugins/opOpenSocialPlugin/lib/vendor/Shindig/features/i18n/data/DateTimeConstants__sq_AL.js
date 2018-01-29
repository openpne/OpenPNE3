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
  ERAS:["p.e.r.","n.e.r."],
  ERANAMES:["p.e.r.","n.e.r."],
  NARROWMONTHS:["J","S","M","P","M","Q","K","G","S","T","N","D"],
  MONTHS:["janar","shkurt","mars","prill","maj","qershor","korrik","gusht","shtator","tetor","n\u00ebntor","dhjetor"],
  SHORTMONTHS:["Jan","Shk","Mar","Pri","Maj","Qer","Kor","Gsh","Sht","Tet","N\u00ebn","Dhj"],
  WEEKDAYS:["e diel","e h\u00ebn\u00eb","e mart\u00eb","e m\u00ebrkur\u00eb","e enjte","e premte","e shtun\u00eb"],
  SHORTWEEKDAYS:["Die","H\u00ebn","Mar","M\u00ebr","Enj","Pre","Sht"],
  NARROWWEEKDAYS:["D","H","M","M","E","P","S"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["PD","MD"],
  DATEFORMATS:["EEEE, dd MMMM yyyy","dd MMMM yyyy","yyyy-MM-dd","yy-MM-dd"],
  TIMEFORMATS:["h.mm.ss.a v","h.mm.ss.a z","h:mm:ss.a","h.mm.a"],
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
