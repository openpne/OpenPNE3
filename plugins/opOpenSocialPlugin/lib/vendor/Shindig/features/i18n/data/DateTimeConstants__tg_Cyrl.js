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
  ERAS:["\u041f\u0435\u041c","\u041f\u0430\u041c"],
  ERANAMES:["\u041f\u0435\u0448 \u0430\u0437 \u043c\u0438\u043b\u043e\u0434","\u041f\u0430\u041c"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u042f\u043d\u0432\u0430\u0440","\u0424\u0435\u0432\u0440\u0430\u043b","\u041c\u0430\u0440\u0442","\u0410\u043f\u0440\u0435\u043b","\u041c\u0430\u0439","\u0418\u044e\u043d","\u0418\u044e\u043b","\u0410\u0432\u0433\u0443\u0441\u0442","\u0421\u0435\u043d\u0442\u044f\u0431\u0440","\u041e\u043a\u0442\u044f\u0431\u0440","\u041d\u043e\u044f\u0431\u0440","\u0414\u0435\u043a\u0430\u0431\u0440"],
  SHORTMONTHS:["\u042f\u043d\u0432","\u0424\u0435\u0432","\u041c\u0430\u0440","\u0410\u043f\u0440","\u041c\u0430\u0439","\u0418\u044e\u043d","\u0418\u044e\u043b","\u0410\u0432\u0433","\u0421\u0435\u043d","\u041e\u043a\u0442","\u041d\u043e\u044f","\u0414\u0435\u043a"],
  WEEKDAYS:["\u042f\u043a\u0448\u0430\u043d\u0431\u0435","\u0414\u0443\u0448\u0430\u043d\u0431\u0435","\u0421\u0435\u0448\u0430\u043d\u0431\u0435","\u0427\u043e\u0440\u0448\u0430\u043d\u0431\u0435","\u041f\u0430\u043d\u04b7\u0448\u0430\u043d\u0431\u0435","\u04b6\u0443\u043c\u044a\u0430","\u0428\u0430\u043d\u0431\u0435"],
  SHORTWEEKDAYS:["\u042f\u0448\u0431","\u0414\u0448\u0431","\u0421\u0448\u0431","\u0427\u0448\u0431","\u041f\u0448\u0431","\u04b6\u043c\u044a","\u0428\u043d\u0431"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u043f\u0435. \u0447\u043e.","\u043f\u0430. \u0447\u043e."],
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
