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
  ERAS:["pr.n.\u0161.","po Kr."],
  ERANAMES:["pred na\u0161im \u0161tetjem","na\u0161e \u0161tetje"],
  NARROWMONTHS:["j","f","m","a","m","j","j","a","s","o","n","d"],
  MONTHS:["januar","februar","marec","april","maj","junij","julij","avgust","september","oktober","november","december"],
  SHORTMONTHS:["jan","feb","mar","apr","maj","jun","jul","avg","sep","okt","nov","dec"],
  WEEKDAYS:["nedelja","ponedeljek","torek","sreda","\u010detrtek","petek","sobota"],
  SHORTWEEKDAYS:["ned","pon","tor","sre","\u010det","pet","sob"],
  NARROWWEEKDAYS:["n","p","t","s","\u010d","p","s"],
  SHORTQUARTERS:["Q1","K2","Q3","Q4"],
  QUARTERS:["Prvo \u010detrtletje","Q2","Tretje \u010detrtletje","\u010cetrto \u010detrtletje"],
  AMPMS:["dop.","pop."],
  DATEFORMATS:["EEEE, dd. MMMM yyyy","dd. MMMM yyyy","d.M.yyyy","d.M.yy"],
  TIMEFORMATS:["H:mm:ss v","H:mm:ss z","H:mm:ss","H:mm"],
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
