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
  MONTHS:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
  SHORTMONTHS:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"],
  WEEKDAYS:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],
  SHORTWEEKDAYS:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
  NARROWWEEKDAYS:["1","2","3","4","5","6","7"],
  SHORTQUARTERS:["K1","K2","K3","K4"],
  QUARTERS:["kuartal pertama","kuartal kedua","kuartal ketiga","kuartal keempat"],
  AMPMS:["AM","PM"],
  DATEFORMATS:["EEEE dd MMMM yyyy","dd MMMM yyyy","dd MMM yyyy","dd/MM/yy"],
  TIMEFORMATS:["H:mm:ss v","H:mm:ss z","H:mm:ss","H:mm"],
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
