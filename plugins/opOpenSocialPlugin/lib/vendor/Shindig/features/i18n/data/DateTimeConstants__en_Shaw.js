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
  ERAS:["\u1045a\u00b7\u10452","\u10468\u1045b"],
  ERANAMES:["\u1045a\u10470\u10453\u1046a\u1046e \u00b7\u10452\u1046e\u10472\u10455\u10451","\u10468\u10459\u10474 \u1045b\u1046a\u10465\u10466\u10459\u10470"],
  NARROWMONTHS:["\u10461","\u10453","\u10465","\u10471","\u10465","\u10461","\u10461","\u1046a","\u10455","\u10477","\u1046f","\u1045b"],
  MONTHS:["\u00b7\u10461\u10468\u10459\u10458\u1046d\u10462\u1047a\u10470","\u00b7\u10453\u10467\u1045a\u10458\u10475\u10462\u1047a\u10470","\u00b7\u10465\u10478\u10457","\u00b7\u10471\u10450\u1046e\u1046d\u10464","\u00b7\u10465\u10471","\u00b7\u10461\u10475\u1046f","\u00b7\u10461\u1046b\u10464\u10472","\u00b7\u1046a\u1045c\u1046d\u10455\u10451","\u00b7\u10455\u10467\u10450\u10451\u10467\u10465\u1045a\u10478","\u00b7\u10477\u10452\u10451\u10474\u1045a\u10478","\u00b7\u1046f\u10474\u1045d\u10467\u10465\u1045a\u10478","\u00b7\u1045b\u1046d\u10455\u10467\u10465\u1045a\u10478"],
  SHORTMONTHS:["\u00b7\u10461\u10468","\u00b7\u10453\u10467","\u00b7\u10465\u10478","\u00b7\u10471\u10450","\u00b7\u10465\u10471","\u00b7\u10461\u10475","\u00b7\u10461\u1046b","\u00b7\u1046a\u1045c","\u00b7\u10455\u10467","\u00b7\u10477\u10452","\u00b7\u1046f\u10474","\u00b7\u1045b\u1046d"],
  WEEKDAYS:["\u00b7\u10455\u1046d\u10459\u1045b\u10471","\u00b7\u10465\u1046d\u10459\u1045b\u10471","\u00b7\u10451\u10475\u1045f\u1045b\u10471","\u00b7\u10462\u10467\u10459\u1045f\u1045b\u10471","\u00b7\u10454\u1047b\u1045f\u1045b\u10471","\u00b7\u10453\u1046e\u10472\u1045b\u10471","\u00b7\u10455\u10468\u1045b\u1047b\u1045b\u10471"],
  SHORTWEEKDAYS:["\u00b7\u10455\u1046d","\u00b7\u10465\u1046d","\u00b7\u10451\u10475","\u00b7\u10462\u10467","\u00b7\u10454\u1047b","\u00b7\u10453\u1046e","\u00b7\u10455\u10468"],
  NARROWWEEKDAYS:["\u10455","\u10465","\u10451","\u10462","\u10454","\u10453","\u10455"],
  SHORTQUARTERS:["\u104521","\u104522","\u104523","\u104524"],
  QUARTERS:["1\u10455\u10451 \u10452\u10462\u10478\u1045b\u10478","2\u1046f\u1045b \u10452\u10462\u10478\u1045b\u10478","3\u1047b\u1045b \u10452\u10462\u10478\u1045b\u10478","4\u10479\u10454 \u10452\u10462\u10478\u1045b\u10478"],
  AMPMS:["\u10468\u10465","\u10450\u10465"],
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
