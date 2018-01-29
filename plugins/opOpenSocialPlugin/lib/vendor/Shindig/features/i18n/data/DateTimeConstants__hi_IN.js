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
  ERAS:["\u0908\u0938\u093e\u092a\u0942\u0930\u094d\u0935","\u0938\u0928"],
  ERANAMES:["\u0908\u0938\u093e\u092a\u0942\u0930\u094d\u0935","\u0938\u0928"],
  NARROWMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  MONTHS:["\u091c\u0928\u0935\u0930\u0940","\u092b\u0930\u0935\u0930\u0940","\u092e\u093e\u0930\u094d\u091a","\u0905\u092a\u094d\u0930\u0948\u0932","\u092e\u0908","\u091c\u0942\u0928","\u091c\u0941\u0932\u093e\u0908","\u0905\u0917\u0938\u094d\u0924","\u0938\u093f\u0924\u092e\u094d\u092c\u0930","\u0905\u0915\u094d\u0924\u0942\u092c\u0930","\u0928\u0935\u092e\u094d\u092c\u0930","\u0926\u093f\u0938\u092e\u094d\u092c\u0930"],
  SHORTMONTHS:["\u091c\u0928\u0935\u0930\u0940","\u092b\u0930\u0935\u0930\u0940","\u092e\u093e\u0930\u094d\u091a","\u0905\u092a\u094d\u0930\u0948\u0932","\u092e\u0908","\u091c\u0942\u0928","\u091c\u0941\u0932\u093e\u0908","\u0905\u0917\u0938\u094d\u0924","\u0938\u093f\u0924\u092e\u094d\u092c\u0930","\u0905\u0915\u094d\u0924\u0942\u092c\u0930","\u0928\u0935\u092e\u094d\u092c\u0930","\u0926\u093f\u0938\u092e\u094d\u092c\u0930"],
  WEEKDAYS:["\u0930\u0935\u093f\u0935\u093e\u0930","\u0938\u094b\u092e\u0935\u093e\u0930","\u092e\u0902\u0917\u0932\u0935\u093e\u0930","\u092c\u0941\u0927\u0935\u093e\u0930","\u0917\u0941\u0930\u0941\u0935\u093e\u0930","\u0936\u0941\u0915\u094d\u0930\u0935\u093e\u0930","\u0936\u0928\u093f\u0935\u093e\u0930"],
  SHORTWEEKDAYS:["\u0930\u0935\u093f","\u0938\u094b\u092e","\u092e\u0902\u0917\u0932","\u092c\u0941\u0927","\u0917\u0941\u0930\u0941","\u0936\u0941\u0915\u094d\u0930","\u0936\u0928\u093f"],
  NARROWWEEKDAYS:["\u0930","2","\u092e\u0902","4","\u0917\u0941","6","7"],
  SHORTQUARTERS:["\u092a\u094d\u0930\u0925\u092e \u091a\u094c\u0925\u093e\u0908","Q2","\u0924\u0943\u0924\u0940\u092f \u091a\u094c\u0925\u093e\u0908","\u091a\u0924\u0941\u0930\u094d\u0925 \u091a\u094c\u0925\u093e\u0908"],
  QUARTERS:["\u092a\u094d\u0930\u0925\u092e \u091a\u094c\u0925\u093e\u0908","\u0926\u094d\u0935\u093f\u0924\u0940\u092f \u091a\u094c\u0925\u093e\u0908","\u0924\u0943\u0924\u0940\u092f \u091a\u094c\u0925\u093e\u0908","\u091a\u0924\u0941\u0930\u094d\u0925 \u091a\u094c\u0925\u093e\u0908"],
  AMPMS:["\u092a\u0942\u0930\u094d\u0935\u093e\u0939\u094d\u0928","\u0905\u092a\u0930\u093e\u0939\u094d\u0928"],
  DATEFORMATS:["EEEE d MMMM yyyy","d MMMM yyyy","dd-MM-yyyy","d-M-yy"],
  TIMEFORMATS:["h:mm:ss a v","h:mm:ss a z","h:mm:ss a","h:mm a"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [6, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEMONTHS = gadgets.i18n.DateTimeConstants.MONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
