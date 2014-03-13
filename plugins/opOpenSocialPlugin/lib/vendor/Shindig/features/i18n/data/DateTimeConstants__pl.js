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
  ERAS:["p.n.e.","n.e."],
  ERANAMES:["p.n.e.","n.e."],
  NARROWMONTHS:["s","l","m","k","m","c","l","s","w","p","l","g"],
  MONTHS:["stycznia","lutego","marca","kwietnia","maja","czerwca","lipca","sierpnia","wrze\u015bnia","pa\u017adziernika","listopada","grudnia"],
  STANDALONEMONTHS:["stycze\u0144","luty","marzec","kwiecie\u0144","maj","czerwiec","lipiec","sierpie\u0144","wrzesie\u0144","pa\u017adziernik","listopad","grudzie\u0144"],
  SHORTMONTHS:["sty","lut","mar","kwi","maj","cze","lip","sie","wrz","pa\u017a","lis","gru"],
  WEEKDAYS:["niedziela","poniedzia\u0142ek","wtorek","\u015broda","czwartek","pi\u0105tek","sobota"],
  SHORTWEEKDAYS:["niedz.","pon.","wt.","\u015br.","czw.","pt.","sob."],
  NARROWWEEKDAYS:["N","P","W","\u015a","C","P","S"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["I kwarta\u0142","II kwarta\u0142","III kwarta\u0142","IV kwarta\u0142"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, d MMMM yyyy","d MMMM yyyy","yyyy-MM-dd","yy-MM-dd"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
