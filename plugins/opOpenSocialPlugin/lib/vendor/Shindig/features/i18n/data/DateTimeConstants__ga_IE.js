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
  ERAS:["RC","AD"],
  ERANAMES:["Roimh Chr\u00edost","Anno Domini"],
  NARROWMONTHS:["E","F","M","A","B","M","I","L","M","D","S","N"],
  MONTHS:["Ean\u00e1ir","Feabhra","M\u00e1rta","Aibre\u00e1n","Bealtaine","Meitheamh","I\u00fail","L\u00fanasa","Me\u00e1n F\u00f3mhair","Deireadh F\u00f3mhair","Samhain","Nollaig"],
  SHORTMONTHS:["Ean","Feabh","M\u00e1rta","Aib","Beal","Meith","I\u00fail","L\u00fan","MF\u00f3mh","DF\u00f3mh","Samh","Noll"],
  WEEKDAYS:["D\u00e9 Domhnaigh","D\u00e9 Luain","D\u00e9 M\u00e1irt","D\u00e9 C\u00e9adaoin","D\u00e9ardaoin","D\u00e9 hAoine","D\u00e9 Sathairn"],
  SHORTWEEKDAYS:["Domh","Luan","M\u00e1irt","C\u00e9ad","D\u00e9ar","Aoine","Sath"],
  NARROWWEEKDAYS:["D","L","M","C","D","A","S"],
  SHORTQUARTERS:["R1","R2","R3","R4"],
  QUARTERS:["1\u00fa r\u00e1ithe","2\u00fa r\u00e1ithe","3\u00fa r\u00e1ithe","4\u00fa r\u00e1ithe"],
  AMPMS:["a.m.","p.m."],
  DATEFORMATS:["EEEE d MMMM yyyy","d MMMM yyyy","d MMM yyyy","dd/MM/yyyy"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 6,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 5
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
