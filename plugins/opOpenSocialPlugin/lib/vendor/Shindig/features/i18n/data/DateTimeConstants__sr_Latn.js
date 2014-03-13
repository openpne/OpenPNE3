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
  ERAS:["p. n. e.","n. e"],
  ERANAMES:["Pre nove ere","Nove ere"],
  NARROWMONTHS:["j","f","m","a","m","j","j","a","s","o","n","d"],
  MONTHS:["januar","februar","mart","april","maj","jun","jul","avgust","septembar","oktobar","novembar","decembar"],
  SHORTMONTHS:["jan","feb","mar","apr","maj","jun","jul","avg","sep","okt","nov","dec"],
  WEEKDAYS:["nedelja","ponedeljak","utorak","sreda","\u010detvrtak","petak","subota"],
  SHORTWEEKDAYS:["ned","pon","uto","sre","\u010det","pet","sub"],
  NARROWWEEKDAYS:["n","p","u","s","\u010d","p","s"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["Prvo tromese\u010dje","Drugo tromese\u010dje","Tre\u0107e tromese\u010dje","\u010cetvrto tromese\u010dje"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, dd. MMMM yyyy.","dd. MMMM yyyy.","dd.MM.yyyy.","d.M.yy."],
  TIMEFORMATS:["HH.mm.ss v","HH.mm.ss z","HH.mm.ss","HH.mm"],
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
