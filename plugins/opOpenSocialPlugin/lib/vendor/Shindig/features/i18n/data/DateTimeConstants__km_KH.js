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
  ERAS:["\u1798\u17bb\u1793\u200b\u1782.\u179f.","\u1782.\u179f."],
  ERANAMES:["\u1798\u17bb\u1793\u200b\u1782\u17d2\u179a\u17b7\u179f\u17d2\u178f\u179f\u1780\u179a\u17b6\u1787","\u1782\u17d2\u179a\u17b7\u179f\u17d2\u178f\u179f\u1780\u179a\u17b6\u1787"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u1798\u1780\u179a\u17b6","\u1780\u17bb\u1798\u17d2\u1797\u17c8","\u1798\u17b7\u1793\u17b6","\u1798\u17c1\u179f\u17b6","\u17a7\u179f\u1797\u17b6","\u1798\u17b7\u1790\u17bb\u1793\u17b6","\u1780\u1780\u17d2\u1780\u178a\u17b6","\u179f\u17b8\u17a0\u17b6","\u1780\u1789\u17d2\u1789\u17b6","\u178f\u17bb\u179b\u17b6","\u179c\u17b7\u1785\u17d2\u1786\u17b7\u1780\u17b6","\u1792\u17d2\u1793\u17bc"],
  SHORTMONTHS:["\u17e1","\u17e2","\u17e3","\u17e4","\u17e5","\u17e6","\u17e7","\u17e8","\u17e9","\u17e1\u17e0","\u17e1\u17e1","\u17e1\u17e2"],
  WEEKDAYS:["\u1790\u17d2\u1784\u17c3\u17a2\u17b6\u1791\u17b7\u178f\u17d2\u1799","\u200b\u1790\u17d2\u1784\u17c3\u1785\u17d0\u1793\u17d2\u1791","\u1790\u17d2\u1784\u17c3\u17a2\u1784\u17d2\u1782\u17b6\u179a","\u1790\u17d2\u1784\u17c3\u1796\u17bb\u1792","\u1790\u17d2\u1784\u17c3\u1796\u17d2\u179a\u17a0\u179f\u17d2\u1794\u178f\u17b7\u17cd","\u1790\u17d2\u1784\u17c3\u179f\u17bb\u1780\u17d2\u179a","\u1790\u17d2\u1784\u17c3\u179f\u17c5\u179a\u17cd"],
  SHORTWEEKDAYS:["\u17a2\u17b6","\u1785","\u17a2","\u1796\u17bb","\u1796\u17d2\u179a","\u179f\u17bb","\u179f"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["\u178f\u17d2\u179a\u17b8\u17e1","\u178f\u17d2\u179a\u17b8\u17e2","\u178f\u17d2\u179a\u17b8\u17e3","\u178f\u17d2\u179a\u17b8\u17e4"],
  QUARTERS:["\u178f\u17d2\u179a\u17b8\u1798\u17b6\u179f\u1791\u17b8\u17e1","\u178f\u17d2\u179a\u17b8\u1798\u17b6\u179f\u1791\u17b8\u17e2","\u178f\u17d2\u179a\u17b8\u1798\u17b6\u179f\u1791\u17b8\u17e3","\u178f\u17d2\u179a\u17b8\u1798\u17b6\u179f\u1791\u17b8\u17e4"],
  AMPMS:["\u1796\u17d2\u179a\u17b9\u1780","\u179b\u17d2\u1784\u17b6\u1785"],
  DATEFORMATS:["EEEE \u1790\u17d2\u1784\u17c3 d \u1781\u17c2 MMMM \u1786\u17d2\u1793\u17b6\u17c6  yyyy","d \u1781\u17c2 MMMM \u1786\u17d2\u1793\u17b6\u17c6  yyyy","d MMM yyyy","d/M/yyyy"],
  TIMEFORMATS:["H \u1798\u17c9\u17c4\u1784 m \u1793\u17b6\u1791\u17b8 ss \u179c\u17b7\u1793\u17b6\u1791\u17b8\u200b v","H \u1798\u17c9\u17c4\u1784 m \u1793\u17b6\u1791\u17b8 ss \u179c\u17b7\u1793\u17b6\u1791\u17b8\u200bz","H:mm:ss","H:mm"],
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
