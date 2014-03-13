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
  ERAS:["p\u0159.Kr.","po Kr."],
  ERANAMES:["p\u0159.Kr.","po Kr."],
  NARROWMONTHS:["l","\u00fa","b","d","k","\u010d","\u010d","s","z","\u0159","l","p"],
  MONTHS:["ledna","\u00fanora","b\u0159ezna","dubna","kv\u011btna","\u010dervna","\u010dervence","srpna","z\u00e1\u0159\u00ed","\u0159\u00edjna","listopadu","prosince"],
  STANDALONEMONTHS:["leden","\u00fanor","b\u0159ezen","duben","kv\u011bten","\u010derven","\u010dervenec","srpen","z\u00e1\u0159\u00ed","\u0159\u00edjen","listopad","prosinec"],
  SHORTMONTHS:["1","2","3","4","5","6","7","8","9","10","11","12"],
  STANDALONESHORTMONTHS:["1.","2.","3.","4.","5.","6.","7.","8.","9.","10.","11.","12."],
  WEEKDAYS:["ned\u011ble","pond\u011bl\u00ed","\u00fater\u00fd","st\u0159eda","\u010dtvrtek","p\u00e1tek","sobota"],
  SHORTWEEKDAYS:["ne","po","\u00fat","st","\u010dt","p\u00e1","so"],
  NARROWWEEKDAYS:["N","P","\u00da","S","\u010c","P","S"],
  SHORTQUARTERS:["Q1","Q2","Q3","Q4"],
  QUARTERS:["1. \u010dtvrtlet\u00ed","2. \u010dtvrtlet\u00ed","3. \u010dtvrtlet\u00ed","4. \u010dtvrtlet\u00ed"],
  AMPMS:["dop.","odp."],
  DATEFORMATS:["EEEE, d. MMMM yyyy","d. MMMM yyyy","d.M.yyyy","d.M.yy"],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","H:mm:ss","H:mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
