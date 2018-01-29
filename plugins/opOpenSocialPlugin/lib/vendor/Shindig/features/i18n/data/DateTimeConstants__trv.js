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
  ERAS:["BRY","BUY"],
  ERANAMES:["Brah jikan Yisu Thulang","Bukuy jikan Yisu Thulang"],
  NARROWMONTHS:["K","D","T","S","R","M","E","P","A","M","K","D"],
  MONTHS:["Kingal idas","Dha idas","Tru idas","Spat idas","Rima idas","Mataru idas","Empitu idas","Maspat idas","Mngari idas","Maxal idas","Maxal kingal idas","Maxal dha idas"],
  SHORTMONTHS:["Kii","Dhi","Tri","Spi","Rii","Mti","Emi","Mai","Mni","Mxi","Mxk","Mxd"],
  WEEKDAYS:["Jiyax sngayan","tgKingal jiyax iyax sngayan","tgDha jiyax iyax sngayan","tgTru jiyax iyax sngayan","tgSpac jiyax iyax sngayan","tgRima jiyax iyax sngayan","tgMataru jiyax iyax sngayan"],
  SHORTWEEKDAYS:["Emp","Kin","Dha","Tru","Spa","Rim","Mat"],
  NARROWWEEKDAYS:["E","K","D","T","S","R","M"],
  SHORTQUARTERS:["mn1","mn2","mn3","mn4"],
  QUARTERS:["mnprxan","mndha","mntru","mnspat"],
  AMPMS:["Brax kndaax","Baubau kndaax"],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yyyy-MM-dd"],
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
