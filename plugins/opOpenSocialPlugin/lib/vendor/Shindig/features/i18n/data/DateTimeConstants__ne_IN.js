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
  ERAS:["\u0908\u0938\u093e \u092a\u0942\u0930\u094d\u0935","\u0938\u0928\u094d"],
  ERANAMES:["\u0908\u0938\u093e \u092a\u0942\u0930\u094d\u0935","\u0938\u0928\u094d"],
  NARROWMONTHS:["\u0967","\u0968","\u0969","\u096a","\u096b","\u096c","\u096d","\u096e","\u096f","\u0967\u0966","\u0967\u0967","\u0967\u0968"],
  MONTHS:["\u091c\u0928\u0935\u0930\u0940","\u092b\u0947\u092c\u094d\u0930\u0941\u0905\u0930\u0940","\u092e\u093e\u0930\u094d\u091a","\u0905\u092a\u094d\u0930\u093f\u0932","\u092e\u0947","\u091c\u0941\u0928","\u091c\u0941\u0932\u093e\u0908","\u0905\u0917\u0938\u094d\u0924","\u0938\u0947\u092a\u094d\u091f\u0947\u092e\u094d\u092c\u0930","\u0905\u0915\u094d\u091f\u094b\u092c\u0930","\u0928\u094b\u092d\u0947\u092e\u094d\u092c\u0930","\u0921\u093f\u0938\u0947\u092e\u094d\u092c\u0930"],
  SHORTMONTHS:["\u091c\u0928","\u092b\u0947\u092c","\u092e\u093e\u0930\u094d\u091a","\u0905\u092a\u094d\u0930\u093f","\u092e\u0947","\u091c\u0941\u0928","\u091c\u0941\u0932\u093e","\u0905\u0917","\u0938\u0947\u092a\u094d\u091f","\u0905\u0915\u094d\u091f\u094b","\u0928\u094b\u092d\u0947","\u0921\u093f\u0938\u0947"],
  WEEKDAYS:["\u0906\u0907\u0924\u092c\u093e\u0930","\u0938\u094b\u092e\u092c\u093e\u0930","\u092e\u0919\u094d\u0917\u0932\u092c\u093e\u0930","\u092c\u0941\u0927\u092c\u093e\u0930","\u092c\u093f\u0939\u0940\u092c\u093e\u0930","\u0936\u0941\u0915\u094d\u0930\u092c\u093e\u0930","\u0936\u0928\u093f\u092c\u093e\u0930"],
  SHORTWEEKDAYS:["\u0906\u0907\u0924","\u0938\u094b\u092e","\u092e\u0919\u094d\u0917\u0932","\u092c\u0941\u0927","\u092c\u093f\u0939\u0940","\u0936\u0941\u0915\u094d\u0930","\u0936\u0928\u093f"],
  NARROWWEEKDAYS:["\u0967","\u0968","\u0969","\u096a","\u096b","\u096c","\u096d"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["\u092a\u0939\u093f\u0932\u094b \u0938\u0924\u094d\u0930","\u0926\u094b\u0938\u094d\u0930\u094b \u0938\u0924\u094d\u0930","\u0924\u0947\u0938\u094d\u0930\u094b \u0938\u0924\u094d\u0930","\u091a\u094c\u0925\u094b \u0938\u0924\u094d\u0930"],
  AMPMS:["\u092a\u0942\u0930\u094d\u0935 \u092e\u0927\u094d\u092f\u093e\u0928\u094d\u0939","\u0909\u0924\u094d\u0924\u0930 \u092e\u0927\u094d\u092f\u093e\u0928\u094d\u0939"],
  DATEFORMATS:["EEEE, yyyy MMMM dd","yyyy MMMM d","yyyy MMM d","yy/MM/dd"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
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
