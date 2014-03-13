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
  ERAS:["GM","M"],
  ERANAMES:["Gabanin Miladi","Miladi"],
  NARROWMONTHS:["J","F","M","A","M","Y","Y","A","S","O","N","D"],
  MONTHS:["Janairu","Fabrairu","Maris","Afrilu","Mayu","Yuni","Yuli","Augusta","Satumba","Oktoba","Nuwamba","Disamba"],
  SHORTMONTHS:["Jan","Fab","Mar","Afr","May","Yun","Yul","Aug","Sat","Okt","Nuw","Dis"],
  WEEKDAYS:["Lahadi","Litini","Talata","Laraba","Alhamis","Jumma'a","Asabar"],
  SHORTWEEKDAYS:["Lah","Lit","Tal","Lar","Alh","Jum","Asa"],
  NARROWWEEKDAYS:["L","L","T","L","A","J","A"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, d MMMM, yyyy","d MMMM, yyyy","d MMM, yyyy","d/M/yy"],
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
