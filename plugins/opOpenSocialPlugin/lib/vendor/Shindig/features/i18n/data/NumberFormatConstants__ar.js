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

gadgets.i18n.NumberFormatConstants = {
  DECIMAL_SEP:"\u066B",
  GROUP_SEP:"\u066C",
  PERCENT:"\u066A",
  ZERO_DIGIT:"\u0660",
  PLUS_SIGN:"+",
  MINUS_SIGN:"-",
  EXP_SYMBOL:"\u0627\u0633",
  PERMILL:"\u2030",
  INFINITY:"\u221E",
  NAN:"\u0644\u064A\u0633 \u0631\u0642\u0645",
  DECIMAL_PATTERN:"#,##0.###;#,##0.###-",
  SCIENTIFIC_PATTERN:"#E0",
  PERCENT_PATTERN:"#,##0%",
  CURRENCY_PATTERN:"\u00A4\u00A0#,##0.00;\u00A4\u00A0#,##0.00-",
  DEF_CURRENCY_CODE:"AED"
};

gadgets.i18n.NumberFormatConstants.MONETARY_SEP = gadgets.i18n.NumberFormatConstants.DECIMAL_SEP;
gadgets.i18n.NumberFormatConstants.MONETARY_GROUP_SEP = gadgets.i18n.NumberFormatConstants.GROUP_SEP;
