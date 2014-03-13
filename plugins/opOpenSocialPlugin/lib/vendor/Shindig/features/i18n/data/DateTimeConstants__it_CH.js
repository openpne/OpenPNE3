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
  ERAS:["aC","dC"],
  ERANAMES:["a.C.","d.C"],
  NARROWMONTHS:["G","F","M","A","M","G","L","A","S","O","N","D"],
  MONTHS:["gennaio","febbraio","marzo","aprile","maggio","giugno","Luglio","agosto","settembre","ottobre","novembre","dicembre"],
  STANDALONEMONTHS:["Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","agosto","settembre","ottobre","novembre","dicembre"],
  SHORTMONTHS:["gen","feb","mar","apr","mag","giu","lug","ago","set","ott","nov","dic"],
  WEEKDAYS:["domenica","luned\u00ec","marted\u00ec","mercoled\u00ec","gioved\u00ec","venerd\u00ec","sabato"],
  STANDALONEWEEKDAYS:["Domenica","Luned\u00ec","Marted\u00ec","Mercoled\u00ec","Gioved\u00ec","Venerd\u00ec","Sabato"],
  SHORTWEEKDAYS:["dom","lun","mar","mer","gio","ven","sab"],
  NARROWWEEKDAYS:["D","L","M","M","G","V","S"],
  SHORTQUARTERS:["T1","T2","T3","T4"],
  QUARTERS:["1o trimestre","2o trimestre","3o trimestre","4o trimestre"],
  AMPMS:["m.","p."],
  DATEFORMATS:["EEEE, d MMMM yyyy","d MMMM yyyy","d-MMM-yyyy","dd.MM.yy"],
  TIMEFORMATS:["HH.mm:ss 'h' v","HH.mm.ss z","HH.mm.ss","HH.mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 3
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
