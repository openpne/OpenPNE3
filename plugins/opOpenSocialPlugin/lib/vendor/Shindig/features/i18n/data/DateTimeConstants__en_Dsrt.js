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
  ERAS:["\u10412\u10417","\u10408\u10414"],
  ERANAMES:["\u10412\u10432\u10441\u1042c\u10449 \u10417\u10449\u10434\u10445\u1043b","\u10408\u1044c\u1042c \u10414\u10431\u1044b\u1042e\u1044c\u10428"],
  NARROWMONTHS:["\u10416","\u10419","\u10423","\u10401","\u10423","\u10416","\u10416","\u10402","\u1041d","\u10409","\u10424","\u10414"],
  MONTHS:["\u10416\u10430\u1044c\u10437\u1042d\u1042f\u10449\u10428","\u10419\u1042f\u1043a\u10449\u1042d\u1042f\u10449\u10428","\u10423\u1042a\u10449\u1043d","\u10401\u10439\u10449\u1042e\u1044a","\u10423\u10429","\u10416\u1042d\u1044c","\u10416\u1042d\u1044a\u10434","\u10402\u10440\u10432\u10445\u1043b","\u1041d\u1042f\u10439\u1043b\u1042f\u1044b\u1043a\u10432\u10449","\u10409\u1043f\u1043b\u1042c\u1043a\u10432\u10449","\u10424\u1042c\u10442\u1042f\u1044b\u1043a\u10432\u10449","\u10414\u10428\u10445\u1042f\u1044b\u1043a\u10432\u10449"],
  SHORTMONTHS:["\u10416\u10430\u1044c","\u10419\u1042f\u1043a","\u10423\u1042a\u10449","\u10401\u10439\u10449","\u10423\u10429","\u10416\u1042d\u1044c","\u10416\u1042d\u1044a","\u10402\u10440","\u1041d\u1042f\u10439","\u10409\u1043f\u1043b","\u10424\u1042c\u10442","\u10414\u10428\u10445"],
  WEEKDAYS:["\u1041d\u10432\u1044c\u1043c\u10429","\u10423\u10432\u1044c\u1043c\u10429","\u10413\u1042d\u10446\u1043c\u10429","\u1040e\u1042f\u1044c\u10446\u1043c\u10429","\u1041b\u10432\u10449\u10446\u1043c\u10429","\u10419\u10449\u10434\u1043c\u10429","\u1041d\u10430\u1043b\u10432\u10449\u1043c\u10429"],
  SHORTWEEKDAYS:["\u1041d\u10432\u1044c","\u10423\u10432\u1044c","\u10413\u1042d\u10446","\u1040e\u1042f\u1044c","\u1041b\u10432\u10449","\u10419\u10449\u10434","\u1041d\u10430\u1043b"],
  NARROWWEEKDAYS:["\u1041d","\u10423","\u10413","\u1040e","\u1041b","\u10419","\u1041d"],
  SHORTQUARTERS:["\u104171","\u104172","\u104173","\u104174"],
  QUARTERS:["1\u10445\u1043b \u1043f\u10436\u1042a\u10449\u1043b\u10432\u10449","2\u1044c\u1043c \u1043f\u10436\u1042a\u10449\u1043b\u10432\u10449","3\u10449\u1043c \u1043f\u10436\u1042a\u10449\u1043b\u10432\u10449","4\u10449\u10443 \u1043f\u10436\u1042a\u10449\u1043b\u10432\u10449"],
  AMPMS:["\u10408\u10423","\u10411\u10423"],
  DATEFORMATS:["EEEE, MMMM d, yyyy","MMMM d, yyyy","MMM d, yyyy","M/d/yy"],
  TIMEFORMATS:["h:mm:ss a v","h:mm:ss a z","h:mm:ss a","h:mm a"],
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
