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
  ERAS:["\u043c.\u044d.\u04e9","\u043c.\u044d."],
  ERANAMES:["\u043c\u0430\u043d\u0430\u0439 \u044d\u0440\u0438\u043d\u0438\u0439 \u04e9\u043c\u043d\u04e9\u0445","\u043c\u0430\u043d\u0430\u0439 \u044d\u0440\u0438\u043d\u0438\u0439"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u0425\u0443\u043b\u0433\u0430\u043d\u0430","\u04ae\u0445\u044d\u0440","\u0411\u0430\u0440","\u0422\u0443\u0443\u043b\u0430\u0439","\u041b\u0443\u0443","\u041c\u043e\u0433\u043e\u0439","\u041c\u043e\u0440\u044c","\u0425\u043e\u043d\u044c","\u0411\u0438\u0447","\u0422\u0430\u0445\u0438\u0430","\u041d\u043e\u0445\u043e\u0439","\u0413\u0430\u0445\u0430\u0439"],
  SHORTMONTHS:["\u0445\u0443\u043b","\u04af\u0445\u044d","\u0431\u0430\u0440","\u0442\u0443\u0443","\u043b\u0443\u0443","\u043c\u043e\u0433","\u043c\u043e\u0440","\u0445\u043e\u043d","\u0431\u0438\u0447","\u0442\u0430\u0445","\u043d\u043e\u0445","\u0433\u0430\u0445"],
  WEEKDAYS:["\u043d\u044f\u043c","\u0434\u0430\u0432\u0430\u0430","\u043c\u044f\u0433\u043c\u0430\u0440","\u043b\u0445\u0430\u0433\u0432\u0430","\u043f\u04af\u0440\u044d\u0432","\u0431\u0430\u0430\u0441\u0430\u043d","\u0431\u044f\u043c\u0431\u0430"],
  SHORTWEEKDAYS:["\u041d\u044f","\u0414\u0430","\u041c\u044f","\u041b\u0445","\u041f\u04af","\u0411\u0430","\u0411\u044f"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["1/4","2/4","3/4","4/4"],
  QUARTERS:["\u0434\u04e9\u0440\u04e9\u0432\u043d\u0438\u0439 \u043d\u044d\u0433","\u0434\u04e9\u0440\u04e9\u0432\u043d\u0438\u0439 \u0445\u043e\u0451\u0440","\u0434\u04e9\u0440\u04e9\u0432\u043d\u0438\u0439 \u0433\u0443\u0440\u0430\u0432","\u0434\u04e9\u0440\u04e9\u0432\u043d\u0438\u0439 \u0434\u04e9\u0440\u04e9\u0432"],
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
