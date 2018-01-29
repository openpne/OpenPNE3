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
  ERAS:["pm\u0113","m\u0113"],
  ERANAMES:["pirms m\u016bsu \u0113ras","m\u016bsu \u0113r\u0101"],
  NARROWMONTHS:["J","F","M","A","M","J","J","A","S","O","N","D"],
  MONTHS:["janv\u0101ris","febru\u0101ris","marts","apr\u012blis","maijs","j\u016bnijs","j\u016blijs","augusts","septembris","oktobris","novembris","decembris"],
  SHORTMONTHS:["Jan","Feb","Mar","Apr","Mai","J\u016bn","J\u016bl","Aug","Sep","Okt","Nov","Dec"],
  WEEKDAYS:["sv\u0113tdiena","pirmdiena","otrdiena","tre\u0161diena","ceturtdiena","piektdiena","sestdiena"],
  SHORTWEEKDAYS:["Sv","P","O","T","C","Pk","S"],
  STANDALONESHORTWEEKDAYS:["Sv","Pr","ot","Tr","Ce","pk","Se"],
  NARROWWEEKDAYS:["S","P","O","T","C","P","S"],
  SHORTQUARTERS:["C1","C2","C3","C4"],
  QUARTERS:["1. ceturksnis","2. ceturksnis","3. ceturksnis","4. ceturksnis"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, yyyy. 'gada' d. MMMM","yyyy. 'gada' d. MMMM","yyyy.d.M","yy.d.M"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
