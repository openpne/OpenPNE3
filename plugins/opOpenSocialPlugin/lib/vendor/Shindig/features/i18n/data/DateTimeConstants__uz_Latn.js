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
  ERAS:["BCE","CE"],
  ERANAMES:["BCE","CE"],
  NARROWMONTHS:["Y","F","M","A","M","I","I","A","S","O","N","D"],
  MONTHS:["Muharram","Safar","Rabiul-avval","Rabiul-oxir","Jumodiul-ulo","Jumodiul-uxro","Rajab","Sha\u02bfbon","Ramazon","Shavvol","Zil-qa\u02bfda","Zil-hijja"],
  STANDALONEMONTHS:["Yanvar","Safar","Rabiul-avval","Rabiul-oxir","Jumodiul-ulo","Jumodiul-uxro","Rajab","Sha\u02bfbon","Ramazon","Shavvol","Zil-qa\u02bfda","Zil-hijja"],
  SHORTMONTHS:["Yanv","Fev","Mar","Apr","May","Iyun","Iyul","Avg","Sen","Okt","Noya","Dek"],
  WEEKDAYS:["yakshanba","dushanba","seshanba","chorshanba","payshanba","juma","shanba"],
  SHORTWEEKDAYS:["Yaksh","Dush","Sesh","Chor","Pay","Jum","Shan"],
  NARROWWEEKDAYS:["Y","D","S","C","P","J","S"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yy/MM/dd"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 6,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 5
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
