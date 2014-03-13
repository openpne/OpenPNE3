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
  ERANAMES:["aC","dC"],
  NARROWMONTHS:["g","f","m","a","m","j","j","a","s","o","n","d"],
  MONTHS:["gener","febrer","mar\u00e7","abril","maig","juny","juliol","agost","setembre","octubre","novembre","desembre"],
  SHORTMONTHS:["gen.","febr.","mar\u00e7","abr.","maig","juny","jul.","ag.","set.","oct.","nov.","des."],
  WEEKDAYS:["diumenge","dilluns","dimarts","dimecres","dijous","divendres","dissabte"],
  SHORTWEEKDAYS:["dg.","dl.","dt.","dc.","dj.","dv.","ds."],
  STANDALONESHORTWEEKDAYS:["dg","dl","dt","dc","dj","dv","ds"],
  NARROWWEEKDAYS:["g","l","t","c","j","v","s"],
  SHORTQUARTERS:["1T","2T","3T","4T"],
  QUARTERS:["1r trimestre","2n trimestre","3r trimestre","4t trimestre"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE d 'de' MMMM 'de' yyyy","d 'de' MMMM 'de' yyyy","dd/MM/yyyy","dd/MM/yy"],
  TIMEFORMATS:["H:mm:ss v","H:mm:ss z","H:mm:ss","H:mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
