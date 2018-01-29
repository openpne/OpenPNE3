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
  ERAS:["pr.n.e.","AD"],
  ERANAMES:["Prije Krista","Poslije Krista"],
  NARROWMONTHS:["s","v","o","t","s","l","s","k","r","l","s","p"],
  MONTHS:["sije\u010dnja","velja\u010de","o\u017eujka","travnja","svibnja","lipnja","srpnja","kolovoza","rujna","listopada","studenoga","prosinca"],
  STANDALONEMONTHS:["sije\u010danj","velja\u010da","o\u017eujak","travanj","svibanj","lipanj","srpanj","kolovoz","rujan","listopad","studeni","prosinac"],
  SHORTMONTHS:["sij","vel","o\u017eu","tra","svi","lip","srp","kol","ruj","lis","stu","pro"],
  WEEKDAYS:["nedjelja","ponedjeljak","utorak","srijeda","\u010detvrtak","petak","subota"],
  SHORTWEEKDAYS:["ned","pon","uto","sri","\u010det","pet","sub"],
  NARROWWEEKDAYS:["n","p","u","s","\u010d","p","s"],
  SHORTQUARTERS:["1kv","2kv","3kv","4kv"],
  QUARTERS:["1. kvartal","2. kvartal","3. kvartal","4. kvartal"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE, d. MMMM yyyy.","d. MMMM yyyy.","d. MMM. yyyy.","dd.MM.yyyy."],
  TIMEFORMATS:["HH:mm:ss v","HH:mm:ss z","HH:mm:ss","HH:mm"],
  FIRSTDAYOFWEEK: 0,
  WEEKENDRANGE: [5, 6],
  FIRSTWEEKCUTOFFDAY: 6
};
gadgets.i18n.DateTimeConstants.STANDALONENARROWMONTHS = gadgets.i18n.DateTimeConstants.NARROWMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTMONTHS = gadgets.i18n.DateTimeConstants.SHORTMONTHS;
gadgets.i18n.DateTimeConstants.STANDALONEWEEKDAYS = gadgets.i18n.DateTimeConstants.WEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONESHORTWEEKDAYS = gadgets.i18n.DateTimeConstants.SHORTWEEKDAYS;
gadgets.i18n.DateTimeConstants.STANDALONENARROWWEEKDAYS = gadgets.i18n.DateTimeConstants.NARROWWEEKDAYS;
