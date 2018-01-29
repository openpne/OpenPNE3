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
  ERAS:["e.\u0259.","b.e."],
  ERANAMES:["eram\u0131zdan \u0259vv\u0259l","bizim eram\u0131z\u0131n"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["Yanvar","Fevral","Mart","Aprel","May","\u0130yun","\u0130yul","Avqust","Sentyabr","Oktyabr","Noyabr","Dekabr"],
  SHORTMONTHS:["yan","fev","mar","apr","may","iyn","iyl","avq","sen","okt","noy","dek"],
  WEEKDAYS:["bazar","bazar ert\u0259si","\u00e7\u0259r\u015f\u0259nb\u0259 ax\u015fam\u0131","\u00e7\u0259r\u015f\u0259nb\u0259","c\u00fcm\u0259 ax\u015fam\u0131","c\u00fcm\u0259","\u015f\u0259nb\u0259"],
  SHORTWEEKDAYS:["B.","B.E.","\u00c7.A.","\u00c7.","C.A.","C","\u015e."],
  NARROWWEEKDAYS:["7","1","2","3","4","5","6"],
  SHORTQUARTERS:["1-ci kv.","2-ci kv.","3-c\u00fc kv.","4-c\u00fc kv."],
  QUARTERS:["1-ci kvartal","2-ci kvartal","3-c\u00fc kvartal","4-c\u00fc kvartal"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yy/MM/dd"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 6,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 5
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
