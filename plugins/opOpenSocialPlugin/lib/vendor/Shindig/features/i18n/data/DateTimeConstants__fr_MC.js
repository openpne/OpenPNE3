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
  ERAS:["av. J.-C.","ap. J.-C."],
  ERANAMES:["avant J\u00e9sus-Christ","apr\u00e8s J\u00e9sus-Christ"],
  NARROWMONTHS:["J","F","M","A","M","J","J","A","S","O","N","D"],
  MONTHS:["janvier","f\u00e9vrier","mars","avril","mai","juin","juillet","ao\u00fbt","septembre","octobre","novembre","d\u00e9cembre"],
  SHORTMONTHS:["janv.","f\u00e9vr.","mars","avr.","mai","juin","juil.","ao\u00fbt","sept.","oct.","nov.","d\u00e9c."],
  WEEKDAYS:["dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi"],
  SHORTWEEKDAYS:["dim.","lun.","mar.","mer.","jeu.","ven.","sam."],
  NARROWWEEKDAYS:["D","L","M","M","J","V","S"],
  SHORTQUARTERS:["T1","T2","T3","T4"],
  QUARTERS:["1er trimestre","2e trimestre","3e trimestre","4e trimestre"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE d MMMM yyyy","d MMMM yyyy","d MMM yyyy","dd/MM/yy"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
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
