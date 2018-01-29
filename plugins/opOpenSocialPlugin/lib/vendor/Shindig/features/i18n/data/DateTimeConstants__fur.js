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
  ERAS:["pdC","ddC"],
  ERANAMES:["pdC","ddC"],
  NARROWMONTHS:["Z","F","M","A","M","J","L","A","S","O","N","D"],
  MONTHS:["Zen\u00e2r","Fevr\u00e2r","Mar\u00e7","Avr\u00eel","Mai","Jugn","Lui","Avost","Setembar","Otubar","Novembar","Dicembar"],
  SHORTMONTHS:["Zen","Fev","Mar","Avr","Mai","Jug","Lui","Avo","Set","Otu","Nov","Dic"],
  WEEKDAYS:["domenie","lunis","martars","miercus","joibe","vinars","sabide"],
  SHORTWEEKDAYS:["dom","lun","mar","mie","joi","vin","sab"],
  NARROWWEEKDAYS:["D","L","M","M","J","V","S"],
  SHORTQUARTERS:["T1","T2","T3","T4"],
  QUARTERS:["Prin trimestri","Secont trimestri","Tier\u00e7 trimestri","Cuart trimestri"],
  AMPMS:["a.","p."],
  DATEFORMATS:["EEEE d 'di' MMMM 'dal' yyyy","d MMMM yyyy","d MMM yyyy","d/MM/yy"],
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
