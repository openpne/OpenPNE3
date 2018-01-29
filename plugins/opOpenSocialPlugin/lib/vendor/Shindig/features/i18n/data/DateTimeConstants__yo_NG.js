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
  ERAS:["SK","LK"],
  ERANAMES:["Saju Kristi","Lehin Kristi"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["O\u1e63\u00f9 \u1e62\u1eb9\u0301r\u1eb9\u0301","O\u1e63\u00f9 \u00c8r\u00e8l\u00e8","O\u1e63\u00f9 \u1eb8r\u1eb9\u0300n\u00e0","O\u1e63\u00f9 \u00ccgb\u00e9","O\u1e63\u00f9 \u1eb8\u0300bibi","O\u1e63\u00f9 \u00d2k\u00fadu","O\u1e63\u00f9 Ag\u1eb9m\u1ecd","O\u1e63\u00f9 \u00d2g\u00fan","O\u1e63\u00f9 Owewe","O\u1e63\u00f9 \u1ecc\u0300w\u00e0r\u00e0","O\u1e63\u00f9 B\u00e9l\u00fa","O\u1e63\u00f9 \u1ecc\u0300p\u1eb9\u0300"],
  SHORTMONTHS:["\u1e62\u1eb9\u0301r\u1eb9\u0301","\u00c8r\u00e8l\u00e8","\u1eb8r\u1eb9\u0300n\u00e0","\u00ccgb\u00e9","\u1eb8\u0300bibi","\u00d2k\u00fadu","Ag\u1eb9m\u1ecd","\u00d2g\u00fan","Owewe","\u1ecc\u0300w\u00e0r\u00e0","B\u00e9l\u00fa","\u1ecc\u0300p\u1eb9\u0300"],
  WEEKDAYS:["\u1eccj\u1ecd\u0301 \u00c0\u00eck\u00fa","\u1eccj\u1ecd\u0301 Aj\u00e9","\u1eccj\u1ecd\u0301 \u00ccs\u1eb9\u0301gun","\u1eccj\u1ecd\u0301r\u00fa","\u1eccj\u1ecd\u0301 \u00c0\u1e63\u1eb9\u0300\u1e63\u1eb9\u0300d\u00e1iy\u00e9","\u1eccj\u1ecd\u0301 \u1eb8t\u00ec","\u1eccj\u1ecd\u0301 \u00c0b\u00e1m\u1eb9\u0301ta"],
  SHORTWEEKDAYS:["\u00c0\u00eck\u00fa","Aj\u00e9","\u00ccs\u1eb9\u0301gun","\u1eccj\u1ecd\u0301r\u00fa","\u00c0\u1e63\u1eb9\u0300\u1e63\u1eb9\u0300d\u00e1iy\u00e9","\u1eb8t\u00ec","\u00c0b\u00e1m\u1eb9\u0301ta"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u00e0\u00e1r\u1ecd\u0300","\u1ecd\u0300s\u00e1n"],
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
