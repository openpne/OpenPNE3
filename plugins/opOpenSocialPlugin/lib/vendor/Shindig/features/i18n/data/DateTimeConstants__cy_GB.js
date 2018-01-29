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
  ERAS:["CC","OC"],
  ERANAMES:["Cyn Crist","Oed Crist"],
  NARROWMONTHS:["I","C","M","E","M","M","G","A","M","H","T","R"],
  MONTHS:["Ionawr","Chwefror","Mawrth","Ebrill","Mai","Mehefin","Gorffenaf","Awst","Medi","Hydref","Tachwedd","Rhagfyr"],
  STANDALONEMONTHS:["Ionawr","Chwefror","Mawrth","Ebrill","Mai","Mehefin","Gorffennaf","Awst","Medi","Hydref","Tachwedd","Rhagfyr"],
  SHORTMONTHS:["Ion","Chwef","Mawrth","Ebrill","Mai","Meh","Gorff","Awst","Medi","Hyd","Tach","Rhag"],
  STANDALONESHORTMONTHS:["Ion","Chwe","Maw","Ebr","Mai","Meh","Gor","Awst","Medi","Hyd","Tach","Rhag"],
  WEEKDAYS:["Dydd Sul","Dydd Llun","Dydd Mawrth","Dydd Mercher","Dydd Iau","Dydd Gwener","Dydd Sadwrn"],
  SHORTWEEKDAYS:["Sul","Llun","Maw","Mer","Iau","Gwen","Sad"],
  STANDALONESHORTWEEKDAYS:["Sul","Llun","Maw","Mer","Iau","Gwe","Sad"],
  NARROWWEEKDAYS:["S","L","M","M","I","G","S"],
  SHORTQUARTERS:["Ch1","Ch2","Ch3","Ch4"],
  QUARTERS:["Chwarter 1af","2il chwarter","3ydd chwarter","4ydd chwarter"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, dd MMMM yyyy","dd MMMM yyyy","d MMM yyyy","dd/MM/yyyy"],
  TIMEFORMATS:["h:mm:ss a v","h:mm:ss a z","h:mm:ss a","h:mm a"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
