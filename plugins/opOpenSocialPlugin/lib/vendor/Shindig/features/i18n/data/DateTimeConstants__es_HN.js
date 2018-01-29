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
  ERANAMES:["antes de Cristo","anno D\u00f3mini"],
  NARROWMONTHS:["E","F","M","A","M","J","J","A","S","O","N","D"],
  MONTHS:["enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre"],
  SHORTMONTHS:["ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic"],
  WEEKDAYS:["domingo","lunes","martes","mi\u00e9rcoles","jueves","viernes","s\u00e1bado"],
  SHORTWEEKDAYS:["dom","lun","mar","mi\u00e9","jue","vie","s\u00e1b"],
  NARROWWEEKDAYS:["D","L","M","M","J","V","S"],
  SHORTQUARTERS:["T1","T2","T3","T4"],
  QUARTERS:["1er trimestre","2\u00ba trimestre","3er trimestre","4\u00ba trimestre"],
  AMPMS:["a.m.","p.m."],
  DATEFORMATS:["EEEE dd 'de' MMMM 'de' yyyy","dd 'de' MMMM 'de' yyyy","dd/MM/yyyy","dd/MM/yy"],
  TIMEFORMATS:["hh:mm:ss a v","HH:mm:ss z","HH:mm:ss","HH:mm"],
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
