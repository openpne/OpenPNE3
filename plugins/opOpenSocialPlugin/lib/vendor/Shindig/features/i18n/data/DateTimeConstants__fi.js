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
  ERAS:["eKr.","jKr."],
  ERANAMES:["ennen Kristuksen syntym\u00e4\u00e4","j\u00e4lkeen Kristuksen syntym\u00e4n"],
  NARROWMONTHS:["T","H","M","H","T","K","H","E","S","L","M","J"],
  MONTHS:["tammikuuta","helmikuuta","maaliskuuta","huhtikuuta","toukokuuta","kes\u00e4kuuta","hein\u00e4kuuta","elokuuta","syyskuuta","lokakuuta","marraskuuta","joulukuuta"],
  SHORTMONTHS:["tammi","helmi","maalis","huhti","touko","kes\u00e4","hein\u00e4","elo","syys","loka","marras","joulu"],
  WEEKDAYS:["sunnuntaina","maanantaina","tiistaina","keskiviikkona","torstaina","perjantaina","lauantaina"],
  SHORTWEEKDAYS:["su","ma","ti","ke","to","pe","la"],
  NARROWWEEKDAYS:["S","M","T","K","T","P","L"],
  SHORTQUARTERS:["1. nelj.","2. nelj.","3. nelj.","4. nelj."],
  QUARTERS:["1. nelj\u00e4nnes","2. nelj\u00e4nnes","3. nelj\u00e4nnes","4. nelj\u00e4nnes"],
  AMPMS:["ap.","ip."],
  DATEFORMATS:["EEEE d. MMMM yyyy","d. MMMM yyyy","d.M.yyyy","d.M.yyyy"],
  TIMEFORMATS:["H.mm.ss v","H.mm.ss z","H.mm.ss","H.mm"],
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
