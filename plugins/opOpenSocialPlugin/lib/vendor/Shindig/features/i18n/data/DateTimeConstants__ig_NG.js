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
  ERAS:["T.K.","A.K."],
  ERANAMES:["Tupu Kristi","Af\u1ecd Kristi"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["Jen\u1ee5war\u1ecb","Febr\u1ee5war\u1ecb","Maach\u1ecb","Eprel","Mee","Juun","Jula\u1ecb","\u1eccg\u1ecd\u1ecdst","Septemba","\u1eccktoba","Novemba","Disemba"],
  SHORTMONTHS:["Jen","Feb","Maa","Epr","Mee","Juu","Jul","\u1eccg\u1ecd","Sep","\u1ecckt","Nov","Dis"],
  WEEKDAYS:["Mb\u1ecds\u1ecb \u1ee4ka","M\u1ecdnde","Tiuzdee","Wenezdee","T\u1ecd\u1ecdzdee","Fra\u1ecbdee","Sat\u1ecddee"],
  SHORTWEEKDAYS:["\u1ee4ka","M\u1ecdn","Tiu","Wen","T\u1ecd\u1ecd","Fra\u1ecb","Sat"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["A.M.","P.M."],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yy/MM/dd"],
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
