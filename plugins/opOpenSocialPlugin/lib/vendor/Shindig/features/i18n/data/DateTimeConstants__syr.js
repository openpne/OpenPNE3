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
  MONTHS:["\u070f\u071f\u0722 \u070f\u0712","\u072b\u0712\u071b","\u0710\u0715\u072a","\u0722\u071d\u0723\u0722","\u0710\u071d\u072a","\u071a\u0719\u071d\u072a\u0722","\u072c\u0721\u0718\u0719","\u0710\u0712","\u0710\u071d\u0720\u0718\u0720","\u070f\u072c\u072b \u070f\u0710","\u070f\u072c\u072b \u070f\u0712","\u070f\u071f\u0722 \u070f\u0710"],
  SHORTMONTHS:["\u070f\u071f\u0722\u00a0\u070f\u0712","\u072b\u0712\u071b","\u0710\u0715\u072a","\u0722\u071d\u0723\u0722","\u0710\u071d\u072a","\u071a\u0719\u071d\u072a\u0722","\u072c\u0721\u0718\u0719","\u0710\u0712","\u0710\u071d\u0720\u0718\u0720","\u070f\u072c\u072b\u00a0\u070f\u0710","\u070f\u072c\u072b\u00a0\u070f\u0712","\u070f\u071f\u0722\u00a0\u070f\u0710"],
  WEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTWEEKDAYS:["1","2","3","4","5","6","7"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["dd MMMM, yyyy","dd MMMM, yyyy","dd/MM/yyyy","dd/MM/yyyy"],
  TIMEFORMATS:["h:mm:ss a v","h:mm:ss a z","h:mm:ss","h:mm"],
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
