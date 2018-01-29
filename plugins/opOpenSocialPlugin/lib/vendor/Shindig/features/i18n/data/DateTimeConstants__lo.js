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
  ERAS:["\u0e9b\u0eb5\u0e81\u0ec8\u0ead\u0e99\u0e84\u0eb4\u0e94\u0eaa\u0eb0\u0e81\u0eb2\u0e99\u0e97\u0eb5\u0ec8","\u0e84.\u0eaa."],
  ERANAMES:["\u0e9b\u0eb5\u0e81\u0ec8\u0ead\u0e99\u0e84\u0eb4\u0e94\u0eaa\u0eb0\u0e81\u0eb2\u0e99\u0e97\u0eb5\u0ec8","\u0e84\u0eb4\u0e94\u0eaa\u0eb0\u0e81\u0eb2\u0e99\u0e97\u0eb5\u0ec8"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u0ea1\u0eb1\u0e87\u0e81\u0ead\u0e99","\u0e81\u0eb8\u0ea1\u0e9e\u0eb2","\u0ea1\u0eb5\u0e99\u0eb2","\u0ec0\u0ea1\u0eaa\u0eb2","\u0e9e\u0eb6\u0e94\u0eaa\u0eb0\u0e9e\u0eb2","\u0ea1\u0eb4\u0e96\u0eb8\u0e99\u0eb2","\u0e81\u0ecd\u0ea5\u0eb0\u0e81\u0ebb\u0e94","\u0eaa\u0eb4\u0e87\u0eab\u0eb2","\u0e81\u0eb1\u0e99\u0e8d\u0eb2","\u0e95\u0eb8\u0ea5\u0eb2","\u0e9e\u0eb0\u0e88\u0eb4\u0e81","\u0e97\u0eb1\u0e99\u0ea7\u0eb2"],
  SHORTMONTHS:["\u0ea1.\u0e81.","\u0e81.\u0e9e.","\u0ea1\u0eb5.\u0e99.","\u0ea1.\u0eaa..","\u0e9e.\u0e9e.","\u0ea1\u0eb4.\u0e96.","\u0e81.\u0ea5.","\u0eaa.\u0eab.","\u0e81.\u0e8d.","\u0e95.\u0ea5.","\u0e9e.\u0e88.","\u0e97.\u0ea7."],
  WEEKDAYS:["\u0ea7\u0eb1\u0e99\u0ead\u0eb2\u0e97\u0eb4\u0e94","\u0ea7\u0eb1\u0e99\u0e88\u0eb1\u0e99","\u0ea7\u0eb1\u0e99\u0ead\u0eb1\u0e87\u0e84\u0eb2\u0e99","\u0ea7\u0eb1\u0e99\u0e9e\u0eb8\u0e94","\u0ea7\u0eb1\u0e99\u0e9e\u0eb0\u0eab\u0eb1\u0e94","\u0ea7\u0eb1\u0e99\u0eaa\u0eb8\u0e81","\u0ea7\u0eb1\u0e99\u0ec0\u0eaa\u0ebb\u0eb2"],
  SHORTWEEKDAYS:["\u0ead\u0eb2.","\u0e88.","\u0ead.","\u0e9e.","\u0e9e\u0eab.","\u0eaa\u0e81.","\u0eaa."],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["Q1","Q2","Q3","Q4"],
  AMPMS:["\u0e81\u0ec8\u0ead\u0e99\u0e97\u0ec8\u0ebd\u0e87","\u0eab\u0ea5\u0eb1\u0e87\u0e97\u0ec8\u0ebd\u0e87"],
  DATEFORMATS:["EEEE\u0e97\u0eb5  d MMMM G yyyy","d MMMM yyyy","d MMM yyyy","d/M/yyyy"],
  TIMEFORMATS:["H\u0ec2\u0ea1\u0e87 m\u0e99\u0eb2\u0e97\u0eb5 ss \u0ea7\u0eb4\u0e99\u0eb2\u0e97\u0eb5v","H \u0ec2\u0ea1\u0e87 m\u0e99\u0eb2\u0e97\u0eb5ss z","H:mm:ss","H:mm"],
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
