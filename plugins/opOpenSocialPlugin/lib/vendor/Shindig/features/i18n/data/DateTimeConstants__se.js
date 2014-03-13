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
  ERAS:["OK","CE"],
  ERANAMES:["Ovdal Kristtusa","CE"],
  NARROWMONTHS:["O","G","N","C","M","G","S","B","\u010c","G","S","J"],
  MONTHS:["o\u0111\u0111ajagem\u00e1nnu","guovvam\u00e1nnu","njuk\u010dam\u00e1nnu","cuo\u014bom\u00e1nnu","miessem\u00e1nnu","geassem\u00e1nnu","suoidnem\u00e1nnu","borgem\u00e1nnu","\u010dak\u010dam\u00e1nnu","golggotm\u00e1nnu","sk\u00e1bmam\u00e1nnu","juovlam\u00e1nnu"],
  SHORTMONTHS:["o\u0111\u0111j","guov","njuk","cuo","mies","geas","suoi","borg","\u010dak\u010d","golg","sk\u00e1b","juov"],
  WEEKDAYS:["sotnabeaivi","vuoss\u00e1rga","ma\u014b\u014beb\u00e1rga","gaskavahkku","duorasdat","bearjadat","l\u00e1vvardat"],
  SHORTWEEKDAYS:["sotn","vuos","ma\u014b","gask","duor","bear","l\u00e1v"],
  NARROWWEEKDAYS:["s","v","m","g","d","b","l"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yy/MM/dd"],
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
