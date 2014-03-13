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
  ERAS:["v. Chr.","n. Chr."],
  ERANAMES:["Voor Christus","Anno Domini"],
  NARROWMONTHS:["J","F","M","A","M","J","J","A","S","O","N","D"],
  MONTHS:["januari","februari","maart","april","mei","juni","juli","augustus","september","oktober","november","december"],
  SHORTMONTHS:["jan","feb","mrt","apr","mei","jun","jul","aug","sep","okt","nov","dec"],
  WEEKDAYS:["zondag","maandag","dinsdag","woensdag","donderdag","vrijdag","zaterdag"],
  SHORTWEEKDAYS:["zo","ma","di","wo","do","vr","za"],
  NARROWWEEKDAYS:["Z","M","D","W","D","V","Z"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["1e kwartaal","2e kwartaal","3e kwartaal","4e kwartaal"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE d MMMM yyyy","d MMMM yyyy","d MMM yyyy","dd-MM-yy"],
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
