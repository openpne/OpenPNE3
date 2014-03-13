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
  ERAS:["a.C.","d.C."],
  ERANAMES:["antes de Cristo","despois de Cristo"],
  NARROWMONTHS:["X","F","M","A","M","X","X","A","S","O","N","D"],
  MONTHS:["Xaneiro","Febreiro","Marzo","Abril","Maio","Xu\u00f1o","Xullo","Agosto","Setembro","Outubro","Novembro","Decembro"],
  SHORTMONTHS:["Xan","Feb","Mar","Abr","Mai","Xu\u00f1","Xul","Ago","Set","Out","Nov","Dec"],
  WEEKDAYS:["Domingo","Luns","Martes","M\u00e9rcores","Xoves","Venres","S\u00e1bado"],
  SHORTWEEKDAYS:["Dom","Lun","Mar","M\u00e9r","Xov","Ven","S\u00e1b"],
  NARROWWEEKDAYS:["D","L","M","M","X","V","S"],
  SHORTQUARTERS:["T1","T2","T3","T4"],
  QUARTERS:["1o trimestre","2o trimestre","3o trimestre","4o trimestre"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE dd MMMM yyyy","dd MMMM yyyy","d MMM, yyyy","dd/MM/yy"],
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
