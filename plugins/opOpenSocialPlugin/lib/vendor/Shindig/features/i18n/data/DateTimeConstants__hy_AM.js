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
  ERAS:["\u0554\u2024\u0531\u2024","\u0554\u2024\u0535\u2024"],
  ERANAMES:["\u0554\u2024\u0531\u2024","\u0554\u2024\u0535\u2024"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u0545\u0578\u0582\u0576\u0578\u0582\u0561\u0580","\u0553\u0565\u057f\u0580\u0578\u0582\u0561\u0580","\u0544\u0561\u0580\u057f","\u0531\u057a\u0580\u056b\u056c","\u0544\u0561\u0575\u056b\u057d","\u0545\u0578\u0582\u0576\u056b\u057d","\u0545\u0578\u0582\u056c\u056b\u057d","\u0555\u0563\u0578\u057d\u057f\u0578\u057d","\u054d\u0565\u057a\u057f\u0565\u0574\u0562\u0565\u0580","\u0540\u0578\u056f\u057f\u0565\u0574\u0562\u0565\u0580","\u0546\u0578\u0575\u0565\u0574\u0562\u0565\u0580","\u0534\u0565\u056f\u057f\u0565\u0574\u0562\u0565\u0580"],
  SHORTMONTHS:["\u0545\u0576\u0580","\u0553\u057f\u0580","\u0544\u0580\u057f","\u0531\u057a\u0580","\u0544\u0575\u057d","\u0545\u0576\u057d","\u0545\u056c\u057d","\u0555\u0563\u057d","\u054d\u0565\u057a","\u0540\u0578\u056f","\u0546\u0578\u0575","\u0534\u0565\u056f"],
  WEEKDAYS:["\u053f\u056b\u0580\u0561\u056f\u056b","\u0535\u0580\u056f\u0578\u0582\u0577\u0561\u0562\u0569\u056b","\u0535\u0580\u0565\u0584\u0577\u0561\u0562\u0569\u056b","\u0549\u0578\u0580\u0565\u0584\u0577\u0561\u0562\u0569\u056b","\u0540\u056b\u0576\u0563\u0577\u0561\u0562\u0569\u056b","\u0548\u0582\u0580\u0562\u0561\u0569","\u0547\u0561\u0562\u0561\u0569"],
  SHORTWEEKDAYS:["\u053f\u056b\u0580","\u0535\u0580\u056f","\u0535\u0580\u0584","\u0549\u0578\u0580","\u0540\u0576\u0563","\u0548\u0582\u0580","\u0547\u0561\u0562"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u0531\u057c\u2024","\u0535\u0580\u2024"],
  DATEFORMATS:["EEEE, MMMM d, yyyy","MMMM dd, yyyy","MMM d, yyyy","MM/dd/yy"],
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
