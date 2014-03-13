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
  ERAS:["BZ","PZ"],
  ERANAMES:["BZ","PZ"],
  NARROWMONTHS:["\u00e7","s","a","n","g","h","7","8","9","10","11","12"],
  MONTHS:["\u00e7ile","sibat","adar","n\u00eesan","gulan","hez\u00eeran","7","8","9","10","11","12"],
  SHORTMONTHS:["\u00e7il","sib","adr","n\u00ees","gul","hez","t\u00eer","8","9","10","11","12"],
  WEEKDAYS:["yek\u015fem","du\u015fem","\u015f\u00ea","\u00e7ar\u015fem","p\u00eanc\u015fem","\u00een","\u015fem\u00ee"],
  SHORTWEEKDAYS:["y\u015f","d\u015f","s\u015f","\u00e7\u015f","p\u015f","\u00een","\u015f"],
  NARROWWEEKDAYS:["y","d","s","\u00e7","p","\u00ee","\u015f"],
  SHORTQUARTERS:["\u00c71","\u00c72","\u00c73","\u00c74"],
  QUARTERS:["\u00c71","\u00c72","\u00c73","\u00c74"],
  AMPMS:["BN","PN"],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yy/MM/dd"],
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
