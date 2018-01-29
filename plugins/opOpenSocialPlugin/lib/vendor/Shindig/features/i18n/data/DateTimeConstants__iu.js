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
  ERAS:["BCE","CE"],
  ERANAMES:["BCE","CE"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u152d\u14d0\u14c4\u140a\u14d5","\u1555\u155d\u1557\u140a\u14d5","\u14ab\u1466\u14ef","\u140a\u1403\u1449\u1433\u14d7","\u14aa\u1403","\u152b\u14c2","\u152a\u14da\u1403","\u140a\u1405\u14a1\u148d\u14ef","\u14f0\u1466\u144f\u155d\u1559","\u1406\u1466\u1451\u155d\u1559","\u14c5\u1559\u1403\u155d\u1559","\u144f\u14f0\u155d\u1559"],
  SHORTMONTHS:["\u152d\u14d0\u14c4\u140a\u14d5","\u1555\u155d\u1557\u140a\u14d5","\u14ab\u1466\u14ef","\u140a\u1403\u1449\u1433\u14d7","\u14aa\u1403","\u152b\u14c2","\u152a\u14da\u1403","\u140a\u1405\u14a1\u148d\u14ef","\u14f0\u1466\u144f\u155d\u1559","\u1406\u1466\u1451\u155d\u1559","\u14c5\u1559\u1403\u155d\u1559","\u144f\u14f0\u155d\u1559"],
  WEEKDAYS:["\u14c8\u1466\u14f0\u1591\u152d","\u14c7\u14a1\u1490\u153e\u152d\u1405","\u14c7\u14a1\u1490\u153e\u152d\u1405\u14d5\u1585\u146d","\u1431\u1593\u1466\u14ef\u1585","\u14ef\u1455\u14bb\u14a5\u1585","\u1455\u14ea\u14d5\u1550\u14a5\u1585","\u14c8\u1466\u14f0\u1591\u152d\u14d5\u1585\u157f"],
  SHORTWEEKDAYS:["\u14c8\u1466\u14f0\u1591\u152d","\u14c7\u14a1\u1490\u153e\u152d\u1405","\u14c7\u14a1\u1490\u153e\u152d\u1405\u14d5\u1585\u146d","\u1431\u1593\u1466\u14ef\u1585","\u14ef\u1455\u14bb\u14a5\u1585","\u1455\u14ea\u14d5\u1550\u14a5\u1585","\u14c8\u1466\u14f0\u1591\u152d\u14d5\u1585\u157f"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yy/MM/dd"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 6,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 2
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
