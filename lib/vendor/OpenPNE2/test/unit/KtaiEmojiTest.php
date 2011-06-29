<?php

/**
 * @copyright 2005-2011 OpenPNE Project
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 *
 * HOW TO TEST:
 *   $ cd /PATH/TO/APP/ROOT/lib/vendor
 *   $ php OpenPNE2/test/unit/KtaiEmojiTest.php
 *
 * You must modify lib/vendor/OpenPNE2/KtaiEmoji/Common.php
 * (see https://github.com/tozuka/OpenPNE3/commit/813756aced83b3fa71d797283b3516b2c4fe9719)
 */
require_once(dirname(__FILE__).'/../../../symfony/lib/vendor/lime/lime.php');
require_once(dirname(__FILE__).'/../../KtaiEmoji.php');


$t = new lime_test(0xFC00 - 0xF500);

function assertInvalidBetween($t, $since, $until, $expected = '')
{
  for ($c=$since; $c<=$until; $c++)
  {
    $hi = ($c >> 8) & 0xFF;
    $lo = $c & 0xFF;
    $bin = sprintf("%c%c", $hi, $lo);
    $msg = sprintf("\"\\x%02X\\x%02X\" => invalid", $hi, $lo);
    $t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat($bin), $expected, $msg);
  }
}


$t->diag('OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat()');


//
// skipping 00xx - F5xx
//
$t->diag('00-F5 (skipped)');


//
// F6xx - not used on Yahoo! mobile
//
$t->diag('F6 -- not used on Yahoo! mobile');

$t->diag('  F600-F6FF: invalid (not used)');
assertInvalidBetween($t, 0xF600, 0xF6FF, '');


//
// F7xx - partially used on Yahoo! mobile
//
$t->diag('F7');

$t->diag('  F700-F740: invalid');
assertInvalidBetween($t, 0xF700, 0xF740, '[]');

$t->diag('  F741-F77E: Group#2, &#xE101;-&#xE13E;, [s:91]-[s:152]'); // http://creation.mb.softbank.jp/web/web_pic_02.html
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x41"), '[s:91]', '"\xF7\x41" => "&#xE101;" => [s:91]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x42"), '[s:92]', '"\xF7\x42" => "&#xE102;" => [s:92]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x43"), '[s:93]', '"\xF7\x43" => "&#xE103;" => [s:93]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x44"), '[s:94]', '"\xF7\x44" => "&#xE104;" => [s:94]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x45"), '[s:95]', '"\xF7\x45" => "&#xE105;" => [s:95]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x46"), '[s:96]', '"\xF7\x46" => "&#xE106;" => [s:96]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x47"), '[s:97]', '"\xF7\x47" => "&#xE107;" => [s:97]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x48"), '[s:98]', '"\xF7\x48" => "&#xE108;" => [s:98]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x49"), '[s:99]', '"\xF7\x49" => "&#xE109;" => [s:99]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x4A"), '[s:100]', '"\xF7\x4A" => "&#xE10A;" => [s:100]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x4B"), '[s:101]', '"\xF7\x4B" => "&#xE10B;" => [s:101]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x4C"), '[s:102]', '"\xF7\x4C" => "&#xE10C;" => [s:102]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x4D"), '[s:103]', '"\xF7\x4D" => "&#xE10D;" => [s:103]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x4E"), '[s:104]', '"\xF7\x4E" => "&#xE10E;" => [s:104]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x4F"), '[s:105]', '"\xF7\x4F" => "&#xE10F;" => [s:105]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x50"), '[s:106]', '"\xF7\x50" => "&#xE110;" => [s:106]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x51"), '[s:107]', '"\xF7\x51" => "&#xE111;" => [s:107]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x52"), '[s:108]', '"\xF7\x52" => "&#xE112;" => [s:108]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x53"), '[s:109]', '"\xF7\x53" => "&#xE113;" => [s:109]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x54"), '[s:110]', '"\xF7\x54" => "&#xE114;" => [s:110]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x55"), '[s:111]', '"\xF7\x55" => "&#xE115;" => [s:111]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x56"), '[s:112]', '"\xF7\x56" => "&#xE116;" => [s:112]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x57"), '[s:113]', '"\xF7\x57" => "&#xE117;" => [s:113]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x58"), '[s:114]', '"\xF7\x58" => "&#xE118;" => [s:114]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x59"), '[s:115]', '"\xF7\x59" => "&#xE119;" => [s:115]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x5A"), '[s:116]', '"\xF7\x5A" => "&#xE11A;" => [s:116]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x5B"), '[s:117]', '"\xF7\x5B" => "&#xE11B;" => [s:117]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x5C"), '[s:118]', '"\xF7\x5C" => "&#xE11C;" => [s:118]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x5D"), '[s:119]', '"\xF7\x5D" => "&#xE11D;" => [s:119]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x5E"), '[s:120]', '"\xF7\x5E" => "&#xE11E;" => [s:120]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x5F"), '[s:121]', '"\xF7\x5F" => "&#xE11F;" => [s:121]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x60"), '[s:122]', '"\xF7\x60" => "&#xE120;" => [s:122]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x61"), '[s:123]', '"\xF7\x61" => "&#xE121;" => [s:123]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x62"), '[s:124]', '"\xF7\x62" => "&#xE122;" => [s:124]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x63"), '[s:125]', '"\xF7\x63" => "&#xE123;" => [s:125]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x64"), '[s:126]', '"\xF7\x64" => "&#xE124;" => [s:126]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x65"), '[s:127]', '"\xF7\x65" => "&#xE125;" => [s:127]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x66"), '[s:128]', '"\xF7\x66" => "&#xE126;" => [s:128]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x67"), '[s:129]', '"\xF7\x67" => "&#xE127;" => [s:129]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x68"), '[s:130]', '"\xF7\x68" => "&#xE128;" => [s:130]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x69"), '[s:131]', '"\xF7\x69" => "&#xE129;" => [s:131]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x6A"), '[s:132]', '"\xF7\x6A" => "&#xE12A;" => [s:132]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x6B"), '[s:133]', '"\xF7\x6B" => "&#xE12B;" => [s:133]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x6C"), '[s:134]', '"\xF7\x6C" => "&#xE12C;" => [s:134]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x6D"), '[s:135]', '"\xF7\x6D" => "&#xE12D;" => [s:135]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x6E"), '[s:136]', '"\xF7\x6E" => "&#xE12E;" => [s:136]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x6F"), '[s:137]', '"\xF7\x6F" => "&#xE12F;" => [s:137]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x70"), '[s:138]', '"\xF7\x70" => "&#xE130;" => [s:138]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x71"), '[s:139]', '"\xF7\x71" => "&#xE131;" => [s:139]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x72"), '[s:140]', '"\xF7\x72" => "&#xE132;" => [s:140]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x73"), '[s:141]', '"\xF7\x73" => "&#xE133;" => [s:141]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x74"), '[s:142]', '"\xF7\x74" => "&#xE134;" => [s:142]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x75"), '[s:143]', '"\xF7\x75" => "&#xE135;" => [s:143]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x76"), '[s:144]', '"\xF7\x76" => "&#xE136;" => [s:144]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x77"), '[s:145]', '"\xF7\x77" => "&#xE137;" => [s:145]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x78"), '[s:146]', '"\xF7\x78" => "&#xE138;" => [s:146]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x79"), '[s:147]', '"\xF7\x79" => "&#xE139;" => [s:147]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x7A"), '[s:148]', '"\xF7\x7A" => "&#xE13A;" => [s:148]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x7B"), '[s:149]', '"\xF7\x7B" => "&#xE13B;" => [s:149]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x7C"), '[s:150]', '"\xF7\x7C" => "&#xE13C;" => [s:150]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x7D"), '[s:151]', '"\xF7\x7D" => "&#xE13D;" => [s:151]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x7E"), '[s:152]', '"\xF7\x7E" => "&#xE13E;" => [s:152]');

$t->diag('  F77F     : invalid');
assertInvalidBetween($t, 0xF77F, 0xF77F, '[]');

$t->diag('  F780-F79B: Group#2, &#xE13F;-&#xE15A;, [s:153]-[s:180]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x80"), '[s:153]', '"\xF7\x80" => "&#xE13F;" => [s:153]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x81"), '[s:154]', '"\xF7\x81" => "&#xE140;" => [s:154]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x82"), '[s:155]', '"\xF7\x82" => "&#xE141;" => [s:155]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x83"), '[s:156]', '"\xF7\x83" => "&#xE142;" => [s:156]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x84"), '[s:157]', '"\xF7\x84" => "&#xE143;" => [s:157]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x85"), '[s:158]', '"\xF7\x85" => "&#xE144;" => [s:158]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x86"), '[s:159]', '"\xF7\x86" => "&#xE145;" => [s:159]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x87"), '[s:160]', '"\xF7\x87" => "&#xE146;" => [s:160]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x88"), '[s:161]', '"\xF7\x88" => "&#xE147;" => [s:161]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x89"), '[s:162]', '"\xF7\x89" => "&#xE148;" => [s:162]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x8A"), '[s:163]', '"\xF7\x8A" => "&#xE149;" => [s:163]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x8B"), '[s:164]', '"\xF7\x8B" => "&#xE14A;" => [s:164]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x8C"), '[s:165]', '"\xF7\x8C" => "&#xE14B;" => [s:165]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x8D"), '[s:166]', '"\xF7\x8D" => "&#xE14C;" => [s:166]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x8E"), '[s:167]', '"\xF7\x8E" => "&#xE14D;" => [s:167]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x8F"), '[s:168]', '"\xF7\x8F" => "&#xE14E;" => [s:168]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x90"), '[s:169]', '"\xF7\x90" => "&#xE14F;" => [s:169]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x91"), '[s:170]', '"\xF7\x91" => "&#xE150;" => [s:170]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x92"), '[s:171]', '"\xF7\x92" => "&#xE151;" => [s:171]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x93"), '[s:172]', '"\xF7\x93" => "&#xE152;" => [s:172]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x94"), '[s:173]', '"\xF7\x94" => "&#xE153;" => [s:173]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x95"), '[s:174]', '"\xF7\x95" => "&#xE154;" => [s:174]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x96"), '[s:175]', '"\xF7\x96" => "&#xE155;" => [s:175]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x97"), '[s:176]', '"\xF7\x97" => "&#xE156;" => [s:176]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x98"), '[s:177]', '"\xF7\x98" => "&#xE157;" => [s:177]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x99"), '[s:178]', '"\xF7\x99" => "&#xE158;" => [s:178]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x9A"), '[s:179]', '"\xF7\x9A" => "&#xE159;" => [s:179]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\x9B"), '[s:180]', '"\xF7\x9B" => "&#xE15A;" => [s:180]');

$t->diag('  F79C-F7A0: invalid');
assertInvalidBetween($t, 0xF79C, 0xF7A0, '[]');

$t->diag('  F7A1-F7F3: Group#3, &#xE201;-&#xE253;, [s:181]-[s:263]'); // http://creation.mb.softbank.jp/web/web_pic_03.html
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA1"), '[s:181]', '"\xF7\xA1" => "&#xE201;" => [s:181]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA2"), '[s:182]', '"\xF7\xA2" => "&#xE202;" => [s:182]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA3"), '[s:183]', '"\xF7\xA3" => "&#xE203;" => [s:183]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA4"), '[s:184]', '"\xF7\xA4" => "&#xE204;" => [s:184]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA5"), '[s:185]', '"\xF7\xA5" => "&#xE205;" => [s:185]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA6"), '[s:186]', '"\xF7\xA6" => "&#xE206;" => [s:186]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA7"), '[s:187]', '"\xF7\xA7" => "&#xE207;" => [s:187]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA8"), '[s:188]', '"\xF7\xA8" => "&#xE208;" => [s:188]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xA9"), '[s:189]', '"\xF7\xA9" => "&#xE209;" => [s:189]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xAA"), '[s:190]', '"\xF7\xAA" => "&#xE20A;" => [s:190]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xAB"), '[s:191]', '"\xF7\xAB" => "&#xE20B;" => [s:191]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xAC"), '[s:192]', '"\xF7\xAC" => "&#xE20C;" => [s:192]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xAD"), '[s:193]', '"\xF7\xAD" => "&#xE20D;" => [s:193]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xAE"), '[s:194]', '"\xF7\xAE" => "&#xE20E;" => [s:194]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xAF"), '[s:195]', '"\xF7\xAF" => "&#xE20F;" => [s:195]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB0"), '[s:196]', '"\xF7\xB0" => "&#xE210;" => [s:196]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB1"), '[s:197]', '"\xF7\xB1" => "&#xE211;" => [s:197]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB2"), '[s:198]', '"\xF7\xB2" => "&#xE212;" => [s:198]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB3"), '[s:199]', '"\xF7\xB3" => "&#xE213;" => [s:199]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB4"), '[s:200]', '"\xF7\xB4" => "&#xE214;" => [s:200]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB5"), '[s:201]', '"\xF7\xB5" => "&#xE215;" => [s:201]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB6"), '[s:202]', '"\xF7\xB6" => "&#xE216;" => [s:202]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB7"), '[s:203]', '"\xF7\xB7" => "&#xE217;" => [s:203]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB8"), '[s:204]', '"\xF7\xB8" => "&#xE218;" => [s:204]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xB9"), '[s:205]', '"\xF7\xB9" => "&#xE219;" => [s:205]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xBA"), '[s:206]', '"\xF7\xBA" => "&#xE21A;" => [s:206]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xBB"), '[s:207]', '"\xF7\xBB" => "&#xE21B;" => [s:207]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xBC"), '[s:208]', '"\xF7\xBC" => "&#xE21C;" => [s:208]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xBD"), '[s:209]', '"\xF7\xBD" => "&#xE21D;" => [s:209]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xBE"), '[s:210]', '"\xF7\xBE" => "&#xE21E;" => [s:210]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xBF"), '[s:211]', '"\xF7\xBF" => "&#xE21F;" => [s:211]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC0"), '[s:212]', '"\xF7\xC0" => "&#xE220;" => [s:212]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC1"), '[s:213]', '"\xF7\xC1" => "&#xE221;" => [s:213]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC2"), '[s:214]', '"\xF7\xC2" => "&#xE222;" => [s:214]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC3"), '[s:215]', '"\xF7\xC3" => "&#xE223;" => [s:215]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC4"), '[s:216]', '"\xF7\xC4" => "&#xE224;" => [s:216]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC5"), '[s:217]', '"\xF7\xC5" => "&#xE225;" => [s:217]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC6"), '[s:218]', '"\xF7\xC6" => "&#xE226;" => [s:218]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC7"), '[s:219]', '"\xF7\xC7" => "&#xE227;" => [s:219]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC8"), '[s:220]', '"\xF7\xC8" => "&#xE228;" => [s:220]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xC9"), '[s:221]', '"\xF7\xC9" => "&#xE229;" => [s:221]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xCA"), '[s:222]', '"\xF7\xCA" => "&#xE22A;" => [s:222]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xCB"), '[s:223]', '"\xF7\xCB" => "&#xE22B;" => [s:223]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xCC"), '[s:224]', '"\xF7\xCC" => "&#xE22C;" => [s:224]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xCD"), '[s:225]', '"\xF7\xCD" => "&#xE22D;" => [s:225]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xCE"), '[s:226]', '"\xF7\xCE" => "&#xE22E;" => [s:226]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xCF"), '[s:227]', '"\xF7\xCF" => "&#xE22F;" => [s:227]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD0"), '[s:228]', '"\xF7\xD0" => "&#xE230;" => [s:228]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD1"), '[s:229]', '"\xF7\xD1" => "&#xE231;" => [s:229]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD2"), '[s:230]', '"\xF7\xD2" => "&#xE232;" => [s:230]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD3"), '[s:231]', '"\xF7\xD3" => "&#xE233;" => [s:231]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD4"), '[s:232]', '"\xF7\xD4" => "&#xE234;" => [s:232]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD5"), '[s:233]', '"\xF7\xD5" => "&#xE235;" => [s:233]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD6"), '[s:234]', '"\xF7\xD6" => "&#xE236;" => [s:234]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD7"), '[s:235]', '"\xF7\xD7" => "&#xE237;" => [s:235]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD8"), '[s:236]', '"\xF7\xD8" => "&#xE238;" => [s:236]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xD9"), '[s:237]', '"\xF7\xD9" => "&#xE239;" => [s:237]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xDA"), '[s:238]', '"\xF7\xDA" => "&#xE23A;" => [s:238]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xDB"), '[s:239]', '"\xF7\xDB" => "&#xE23B;" => [s:239]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xDC"), '[s:240]', '"\xF7\xDC" => "&#xE23C;" => [s:240]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xDD"), '[s:241]', '"\xF7\xDD" => "&#xE23D;" => [s:241]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xDE"), '[s:242]', '"\xF7\xDE" => "&#xE23E;" => [s:242]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xDF"), '[s:243]', '"\xF7\xDF" => "&#xE23F;" => [s:243]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE0"), '[s:244]', '"\xF7\xE0" => "&#xE240;" => [s:244]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE1"), '[s:245]', '"\xF7\xE1" => "&#xE241;" => [s:245]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE2"), '[s:246]', '"\xF7\xE2" => "&#xE242;" => [s:246]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE3"), '[s:247]', '"\xF7\xE3" => "&#xE243;" => [s:247]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE4"), '[s:248]', '"\xF7\xE4" => "&#xE244;" => [s:248]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE5"), '[s:249]', '"\xF7\xE5" => "&#xE245;" => [s:249]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE6"), '[s:250]', '"\xF7\xE6" => "&#xE246;" => [s:250]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE7"), '[s:251]', '"\xF7\xE7" => "&#xE247;" => [s:251]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE8"), '[s:252]', '"\xF7\xE8" => "&#xE248;" => [s:252]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xE9"), '[s:253]', '"\xF7\xE9" => "&#xE249;" => [s:253]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xEA"), '[s:254]', '"\xF7\xEA" => "&#xE24A;" => [s:254]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xEB"), '[s:255]', '"\xF7\xEB" => "&#xE24B;" => [s:255]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xEC"), '[s:256]', '"\xF7\xEC" => "&#xE24C;" => [s:256]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xED"), '[s:257]', '"\xF7\xED" => "&#xE24D;" => [s:257]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xEE"), '[s:258]', '"\xF7\xEE" => "&#xE24E;" => [s:258]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xEF"), '[s:259]', '"\xF7\xEF" => "&#xE24F;" => [s:259]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xF0"), '[s:260]', '"\xF7\xF0" => "&#xE250;" => [s:260]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xF1"), '[s:261]', '"\xF7\xF1" => "&#xE251;" => [s:261]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xF2"), '[s:262]', '"\xF7\xF2" => "&#xE252;" => [s:262]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF7\xF3"), '[s:263]', '"\xF7\xF3" => "&#xE253;" => [s:263]');

$t->diag('  F7F4-F7FF: invalid');
assertInvalidBetween($t, 0xF7F4, 0xF7FF, '[]');


//
// F8xx - not used on Yahoo! mobile
//
$t->diag('F8 -- not used on Yahoo! mobile');

$t->diag('  F800-F8FF: invalid (not used)');
assertInvalidBetween($t, 0xF800, 0xF8FF, '');


//
// F9xx - partially used on Yahoo! mobile
//
$t->diag('F9');

$t->diag('  F900-F940: invalid');
assertInvalidBetween($t, 0xF900, 0xF940, '[]');

$t->diag('  F941-F97E: Group#1, &#xE001;-&#xE03E;, [s:1]-[s:62]'); // http://creation.mb.softbank.jp/web/web_pic_01.html
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x41"), '[s:1]', '"\xF9\x41" => "&#xE001;" => [s:1]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x42"), '[s:2]', '"\xF9\x42" => "&#xE002;" => [s:2]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x43"), '[s:3]', '"\xF9\x43" => "&#xE003;" => [s:3]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x44"), '[s:4]', '"\xF9\x44" => "&#xE004;" => [s:4]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x45"), '[s:5]', '"\xF9\x45" => "&#xE005;" => [s:5]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x46"), '[s:6]', '"\xF9\x46" => "&#xE006;" => [s:6]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x47"), '[s:7]', '"\xF9\x47" => "&#xE007;" => [s:7]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x48"), '[s:8]', '"\xF9\x48" => "&#xE008;" => [s:8]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x49"), '[s:9]', '"\xF9\x49" => "&#xE009;" => [s:9]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x4A"), '[s:10]', '"\xF9\x4A" => "&#xE00A;" => [s:10]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x4B"), '[s:11]', '"\xF9\x4B" => "&#xE00B;" => [s:11]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x4C"), '[s:12]', '"\xF9\x4C" => "&#xE00C;" => [s:12]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x4D"), '[s:13]', '"\xF9\x4D" => "&#xE00D;" => [s:13]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x4E"), '[s:14]', '"\xF9\x4E" => "&#xE00E;" => [s:14]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x4F"), '[s:15]', '"\xF9\x4F" => "&#xE00F;" => [s:15]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x50"), '[s:16]', '"\xF9\x50" => "&#xE010;" => [s:16]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x51"), '[s:17]', '"\xF9\x51" => "&#xE011;" => [s:17]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x52"), '[s:18]', '"\xF9\x52" => "&#xE012;" => [s:18]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x53"), '[s:19]', '"\xF9\x53" => "&#xE013;" => [s:19]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x54"), '[s:20]', '"\xF9\x54" => "&#xE014;" => [s:20]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x55"), '[s:21]', '"\xF9\x55" => "&#xE015;" => [s:21]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x56"), '[s:22]', '"\xF9\x56" => "&#xE016;" => [s:22]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x57"), '[s:23]', '"\xF9\x57" => "&#xE017;" => [s:23]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x58"), '[s:24]', '"\xF9\x58" => "&#xE018;" => [s:24]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x59"), '[s:25]', '"\xF9\x59" => "&#xE019;" => [s:25]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x5A"), '[s:26]', '"\xF9\x5A" => "&#xE01A;" => [s:26]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x5B"), '[s:27]', '"\xF9\x5B" => "&#xE01B;" => [s:27]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x5C"), '[s:28]', '"\xF9\x5C" => "&#xE01C;" => [s:28]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x5D"), '[s:29]', '"\xF9\x5D" => "&#xE01D;" => [s:29]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x5E"), '[s:30]', '"\xF9\x5E" => "&#xE01E;" => [s:30]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x5F"), '[s:31]', '"\xF9\x5F" => "&#xE01F;" => [s:31]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x60"), '[s:32]', '"\xF9\x60" => "&#xE020;" => [s:32]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x61"), '[s:33]', '"\xF9\x61" => "&#xE021;" => [s:33]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x62"), '[s:34]', '"\xF9\x62" => "&#xE022;" => [s:34]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x63"), '[s:35]', '"\xF9\x63" => "&#xE023;" => [s:35]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x64"), '[s:36]', '"\xF9\x64" => "&#xE024;" => [s:36]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x65"), '[s:37]', '"\xF9\x65" => "&#xE025;" => [s:37]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x66"), '[s:38]', '"\xF9\x66" => "&#xE026;" => [s:38]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x67"), '[s:39]', '"\xF9\x67" => "&#xE027;" => [s:39]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x68"), '[s:40]', '"\xF9\x68" => "&#xE028;" => [s:40]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x69"), '[s:41]', '"\xF9\x69" => "&#xE029;" => [s:41]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x6A"), '[s:42]', '"\xF9\x6A" => "&#xE02A;" => [s:42]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x6B"), '[s:43]', '"\xF9\x6B" => "&#xE02B;" => [s:43]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x6C"), '[s:44]', '"\xF9\x6C" => "&#xE02C;" => [s:44]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x6D"), '[s:45]', '"\xF9\x6D" => "&#xE02D;" => [s:45]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x6E"), '[s:46]', '"\xF9\x6E" => "&#xE02E;" => [s:46]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x6F"), '[s:47]', '"\xF9\x6F" => "&#xE02F;" => [s:47]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x70"), '[s:48]', '"\xF9\x70" => "&#xE030;" => [s:48]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x71"), '[s:49]', '"\xF9\x71" => "&#xE031;" => [s:49]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x72"), '[s:50]', '"\xF9\x72" => "&#xE032;" => [s:50]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x73"), '[s:51]', '"\xF9\x73" => "&#xE033;" => [s:51]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x74"), '[s:52]', '"\xF9\x74" => "&#xE034;" => [s:52]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x75"), '[s:53]', '"\xF9\x75" => "&#xE035;" => [s:53]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x76"), '[s:54]', '"\xF9\x76" => "&#xE036;" => [s:54]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x77"), '[s:55]', '"\xF9\x77" => "&#xE037;" => [s:55]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x78"), '[s:56]', '"\xF9\x78" => "&#xE038;" => [s:56]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x79"), '[s:57]', '"\xF9\x79" => "&#xE039;" => [s:57]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x7A"), '[s:58]', '"\xF9\x7A" => "&#xE03A;" => [s:58]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x7B"), '[s:59]', '"\xF9\x7B" => "&#xE03B;" => [s:59]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x7C"), '[s:60]', '"\xF9\x7C" => "&#xE03C;" => [s:60]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x7D"), '[s:61]', '"\xF9\x7D" => "&#xE03D;" => [s:61]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x7E"), '[s:62]', '"\xF9\x7E" => "&#xE03E;" => [s:62]');

$t->diag('  F97F     : invalid');
assertInvalidBetween($t, 0xF97F, 0xF97F, '[]');

$t->diag('  F980-F99B: Group#1, &#xE03F;-&#xE05A;, [s:63]-[s:90]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x80"), '[s:63]', '"\xF9\x80" => "&#xE03F;" => [s:63]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x81"), '[s:64]', '"\xF9\x81" => "&#xE040;" => [s:64]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x82"), '[s:65]', '"\xF9\x82" => "&#xE041;" => [s:65]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x83"), '[s:66]', '"\xF9\x83" => "&#xE042;" => [s:66]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x84"), '[s:67]', '"\xF9\x84" => "&#xE043;" => [s:67]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x85"), '[s:68]', '"\xF9\x85" => "&#xE044;" => [s:68]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x86"), '[s:69]', '"\xF9\x86" => "&#xE045;" => [s:69]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x87"), '[s:70]', '"\xF9\x87" => "&#xE046;" => [s:70]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x88"), '[s:71]', '"\xF9\x88" => "&#xE047;" => [s:71]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x89"), '[s:72]', '"\xF9\x89" => "&#xE048;" => [s:72]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x8A"), '[s:73]', '"\xF9\x8A" => "&#xE049;" => [s:73]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x8B"), '[s:74]', '"\xF9\x8B" => "&#xE04A;" => [s:74]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x8C"), '[s:75]', '"\xF9\x8C" => "&#xE04B;" => [s:75]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x8D"), '[s:76]', '"\xF9\x8D" => "&#xE04C;" => [s:76]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x8E"), '[s:77]', '"\xF9\x8E" => "&#xE04D;" => [s:77]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x8F"), '[s:78]', '"\xF9\x8F" => "&#xE04E;" => [s:78]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x90"), '[s:79]', '"\xF9\x90" => "&#xE04F;" => [s:79]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x91"), '[s:80]', '"\xF9\x91" => "&#xE050;" => [s:80]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x92"), '[s:81]', '"\xF9\x92" => "&#xE051;" => [s:81]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x93"), '[s:82]', '"\xF9\x93" => "&#xE052;" => [s:82]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x94"), '[s:83]', '"\xF9\x94" => "&#xE053;" => [s:83]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x95"), '[s:84]', '"\xF9\x95" => "&#xE054;" => [s:84]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x96"), '[s:85]', '"\xF9\x96" => "&#xE055;" => [s:85]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x97"), '[s:86]', '"\xF9\x97" => "&#xE056;" => [s:86]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x98"), '[s:87]', '"\xF9\x98" => "&#xE057;" => [s:87]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x99"), '[s:88]', '"\xF9\x99" => "&#xE058;" => [s:88]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x9A"), '[s:89]', '"\xF9\x9A" => "&#xE059;" => [s:89]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\x9B"), '[s:90]', '"\xF9\x9B" => "&#xE05A;" => [s:90]');

$t->diag('  F99C-F9A0: invalid');
assertInvalidBetween($t, 0xF99C, 0xF9A0, '[]');

$t->diag('  F9A1-F9ED: Group#4, &#xE301;-&#xE34D;, [s:271]-[s:347]'); // http://creation.mb.softbank.jp/web/web_pic_04.html
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA1"), '[s:271]', '"\xF9\xA1" => "&#xE301;" => [s:271]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA2"), '[s:272]', '"\xF9\xA2" => "&#xE302;" => [s:272]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA3"), '[s:273]', '"\xF9\xA3" => "&#xE303;" => [s:273]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA4"), '[s:274]', '"\xF9\xA4" => "&#xE304;" => [s:274]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA5"), '[s:275]', '"\xF9\xA5" => "&#xE305;" => [s:275]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA6"), '[s:276]', '"\xF9\xA6" => "&#xE306;" => [s:276]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA7"), '[s:277]', '"\xF9\xA7" => "&#xE307;" => [s:277]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA8"), '[s:278]', '"\xF9\xA8" => "&#xE308;" => [s:278]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xA9"), '[s:279]', '"\xF9\xA9" => "&#xE309;" => [s:279]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xAA"), '[s:280]', '"\xF9\xAA" => "&#xE30A;" => [s:280]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xAB"), '[s:281]', '"\xF9\xAB" => "&#xE30B;" => [s:281]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xAC"), '[s:282]', '"\xF9\xAC" => "&#xE30C;" => [s:282]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xAD"), '[s:283]', '"\xF9\xAD" => "&#xE30D;" => [s:283]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xAE"), '[s:284]', '"\xF9\xAE" => "&#xE30E;" => [s:284]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xAF"), '[s:285]', '"\xF9\xAF" => "&#xE30F;" => [s:285]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB0"), '[s:286]', '"\xF9\xB0" => "&#xE310;" => [s:286]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB1"), '[s:287]', '"\xF9\xB1" => "&#xE311;" => [s:287]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB2"), '[s:288]', '"\xF9\xB2" => "&#xE312;" => [s:288]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB3"), '[s:289]', '"\xF9\xB3" => "&#xE313;" => [s:289]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB4"), '[s:290]', '"\xF9\xB4" => "&#xE314;" => [s:290]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB5"), '[s:291]', '"\xF9\xB5" => "&#xE315;" => [s:291]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB6"), '[s:292]', '"\xF9\xB6" => "&#xE316;" => [s:292]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB7"), '[s:293]', '"\xF9\xB7" => "&#xE317;" => [s:293]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB8"), '[s:294]', '"\xF9\xB8" => "&#xE318;" => [s:294]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xB9"), '[s:295]', '"\xF9\xB9" => "&#xE319;" => [s:295]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xBA"), '[s:296]', '"\xF9\xBA" => "&#xE31A;" => [s:296]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xBB"), '[s:297]', '"\xF9\xBB" => "&#xE31B;" => [s:297]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xBC"), '[s:298]', '"\xF9\xBC" => "&#xE31C;" => [s:298]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xBD"), '[s:299]', '"\xF9\xBD" => "&#xE31D;" => [s:299]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xBE"), '[s:300]', '"\xF9\xBE" => "&#xE31E;" => [s:300]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xBF"), '[s:301]', '"\xF9\xBF" => "&#xE31F;" => [s:301]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC0"), '[s:302]', '"\xF9\xC0" => "&#xE320;" => [s:302]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC1"), '[s:303]', '"\xF9\xC1" => "&#xE321;" => [s:303]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC2"), '[s:304]', '"\xF9\xC2" => "&#xE322;" => [s:304]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC3"), '[s:305]', '"\xF9\xC3" => "&#xE323;" => [s:305]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC4"), '[s:306]', '"\xF9\xC4" => "&#xE324;" => [s:306]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC5"), '[s:307]', '"\xF9\xC5" => "&#xE325;" => [s:307]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC6"), '[s:308]', '"\xF9\xC6" => "&#xE326;" => [s:308]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC7"), '[s:309]', '"\xF9\xC7" => "&#xE327;" => [s:309]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC8"), '[s:310]', '"\xF9\xC8" => "&#xE328;" => [s:310]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xC9"), '[s:311]', '"\xF9\xC9" => "&#xE329;" => [s:311]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xCA"), '[s:312]', '"\xF9\xCA" => "&#xE32A;" => [s:312]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xCB"), '[s:313]', '"\xF9\xCB" => "&#xE32B;" => [s:313]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xCC"), '[s:314]', '"\xF9\xCC" => "&#xE32C;" => [s:314]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xCD"), '[s:315]', '"\xF9\xCD" => "&#xE32D;" => [s:315]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xCE"), '[s:316]', '"\xF9\xCE" => "&#xE32E;" => [s:316]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xCF"), '[s:317]', '"\xF9\xCF" => "&#xE32F;" => [s:317]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD0"), '[s:318]', '"\xF9\xD0" => "&#xE330;" => [s:318]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD1"), '[s:319]', '"\xF9\xD1" => "&#xE331;" => [s:319]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD2"), '[s:320]', '"\xF9\xD2" => "&#xE332;" => [s:320]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD3"), '[s:321]', '"\xF9\xD3" => "&#xE333;" => [s:321]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD4"), '[s:322]', '"\xF9\xD4" => "&#xE334;" => [s:322]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD5"), '[s:323]', '"\xF9\xD5" => "&#xE335;" => [s:323]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD6"), '[s:324]', '"\xF9\xD6" => "&#xE336;" => [s:324]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD7"), '[s:325]', '"\xF9\xD7" => "&#xE337;" => [s:325]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD8"), '[s:326]', '"\xF9\xD8" => "&#xE338;" => [s:326]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xD9"), '[s:327]', '"\xF9\xD9" => "&#xE339;" => [s:327]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xDA"), '[s:328]', '"\xF9\xDA" => "&#xE33A;" => [s:328]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xDB"), '[s:329]', '"\xF9\xDB" => "&#xE33B;" => [s:329]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xDC"), '[s:330]', '"\xF9\xDC" => "&#xE33C;" => [s:330]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xDD"), '[s:331]', '"\xF9\xDD" => "&#xE33D;" => [s:331]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xDE"), '[s:332]', '"\xF9\xDE" => "&#xE33E;" => [s:332]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xDF"), '[s:333]', '"\xF9\xDF" => "&#xE33F;" => [s:333]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE0"), '[s:334]', '"\xF9\xE0" => "&#xE340;" => [s:334]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE1"), '[s:335]', '"\xF9\xE1" => "&#xE341;" => [s:335]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE2"), '[s:336]', '"\xF9\xE2" => "&#xE342;" => [s:336]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE3"), '[s:337]', '"\xF9\xE3" => "&#xE343;" => [s:337]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE4"), '[s:338]', '"\xF9\xE4" => "&#xE344;" => [s:338]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE5"), '[s:339]', '"\xF9\xE5" => "&#xE345;" => [s:339]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE6"), '[s:340]', '"\xF9\xE6" => "&#xE346;" => [s:340]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE7"), '[s:341]', '"\xF9\xE7" => "&#xE347;" => [s:341]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE8"), '[s:342]', '"\xF9\xE8" => "&#xE348;" => [s:342]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xE9"), '[s:343]', '"\xF9\xE9" => "&#xE349;" => [s:343]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xEA"), '[s:344]', '"\xF9\xEA" => "&#xE34A;" => [s:344]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xEB"), '[s:345]', '"\xF9\xEB" => "&#xE34B;" => [s:345]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xEC"), '[s:346]', '"\xF9\xEC" => "&#xE34C;" => [s:346]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xF9\xED"), '[s:347]', '"\xF9\xED" => "&#xE34D;" => [s:347]');

$t->diag('  F9EE-F9FF: invalid');
assertInvalidBetween($t, 0xF9EE, 0xF9FF, '[]');


//
// FAxx - not used on Yahoo! mobile
//
$t->diag('FA -- not used on Yahoo! mobile');

$t->diag('  FA00-FAFF: invalid (not used)');
assertInvalidBetween($t, 0xFA00, 0xFAFF, '');


//
// FBxx - partially used on Yahoo! mobile
//
$t->diag('FB');

$t->diag('  FB00-FB40: invalid');
assertInvalidBetween($t, 0xFB00, 0xFB40, '[]');

$t->diag('  FB41-FB7E: Group#5, &#xE401;-&#xE43E;, [s:348]-[s:409]'); // http://creation.mb.softbank.jp/web/web_pic_05.html
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x41"), '[s:348]', '"\xFB\x41" => "&#xE401;" => [s:348]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x42"), '[s:349]', '"\xFB\x42" => "&#xE402;" => [s:349]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x43"), '[s:350]', '"\xFB\x43" => "&#xE403;" => [s:350]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x44"), '[s:351]', '"\xFB\x44" => "&#xE404;" => [s:351]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x45"), '[s:352]', '"\xFB\x45" => "&#xE405;" => [s:352]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x46"), '[s:353]', '"\xFB\x46" => "&#xE406;" => [s:353]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x47"), '[s:354]', '"\xFB\x47" => "&#xE407;" => [s:354]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x48"), '[s:355]', '"\xFB\x48" => "&#xE408;" => [s:355]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x49"), '[s:356]', '"\xFB\x49" => "&#xE409;" => [s:356]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x4A"), '[s:357]', '"\xFB\x4A" => "&#xE40A;" => [s:357]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x4B"), '[s:358]', '"\xFB\x4B" => "&#xE40B;" => [s:358]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x4C"), '[s:359]', '"\xFB\x4C" => "&#xE40C;" => [s:359]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x4D"), '[s:360]', '"\xFB\x4D" => "&#xE40D;" => [s:360]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x4E"), '[s:361]', '"\xFB\x4E" => "&#xE40E;" => [s:361]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x4F"), '[s:362]', '"\xFB\x4F" => "&#xE40F;" => [s:362]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x50"), '[s:363]', '"\xFB\x50" => "&#xE410;" => [s:363]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x51"), '[s:364]', '"\xFB\x51" => "&#xE411;" => [s:364]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x52"), '[s:365]', '"\xFB\x52" => "&#xE412;" => [s:365]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x53"), '[s:366]', '"\xFB\x53" => "&#xE413;" => [s:366]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x54"), '[s:367]', '"\xFB\x54" => "&#xE414;" => [s:367]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x55"), '[s:368]', '"\xFB\x55" => "&#xE415;" => [s:368]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x56"), '[s:369]', '"\xFB\x56" => "&#xE416;" => [s:369]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x57"), '[s:370]', '"\xFB\x57" => "&#xE417;" => [s:370]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x58"), '[s:371]', '"\xFB\x58" => "&#xE418;" => [s:371]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x59"), '[s:372]', '"\xFB\x59" => "&#xE419;" => [s:372]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x5A"), '[s:373]', '"\xFB\x5A" => "&#xE41A;" => [s:373]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x5B"), '[s:374]', '"\xFB\x5B" => "&#xE41B;" => [s:374]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x5C"), '[s:375]', '"\xFB\x5C" => "&#xE41C;" => [s:375]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x5D"), '[s:376]', '"\xFB\x5D" => "&#xE41D;" => [s:376]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x5E"), '[s:377]', '"\xFB\x5E" => "&#xE41E;" => [s:377]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x5F"), '[s:378]', '"\xFB\x5F" => "&#xE41F;" => [s:378]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x60"), '[s:379]', '"\xFB\x60" => "&#xE420;" => [s:379]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x61"), '[s:380]', '"\xFB\x61" => "&#xE421;" => [s:380]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x62"), '[s:381]', '"\xFB\x62" => "&#xE422;" => [s:381]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x63"), '[s:382]', '"\xFB\x63" => "&#xE423;" => [s:382]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x64"), '[s:383]', '"\xFB\x64" => "&#xE424;" => [s:383]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x65"), '[s:384]', '"\xFB\x65" => "&#xE425;" => [s:384]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x66"), '[s:385]', '"\xFB\x66" => "&#xE426;" => [s:385]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x67"), '[s:386]', '"\xFB\x67" => "&#xE427;" => [s:386]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x68"), '[s:387]', '"\xFB\x68" => "&#xE428;" => [s:387]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x69"), '[s:388]', '"\xFB\x69" => "&#xE429;" => [s:388]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x6A"), '[s:389]', '"\xFB\x6A" => "&#xE42A;" => [s:389]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x6B"), '[s:390]', '"\xFB\x6B" => "&#xE42B;" => [s:390]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x6C"), '[s:391]', '"\xFB\x6C" => "&#xE42C;" => [s:391]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x6D"), '[s:392]', '"\xFB\x6D" => "&#xE42D;" => [s:392]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x6E"), '[s:393]', '"\xFB\x6E" => "&#xE42E;" => [s:393]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x6F"), '[s:394]', '"\xFB\x6F" => "&#xE42F;" => [s:394]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x70"), '[s:395]', '"\xFB\x70" => "&#xE430;" => [s:395]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x71"), '[s:396]', '"\xFB\x71" => "&#xE431;" => [s:396]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x72"), '[s:397]', '"\xFB\x72" => "&#xE432;" => [s:397]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x73"), '[s:398]', '"\xFB\x73" => "&#xE433;" => [s:398]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x74"), '[s:399]', '"\xFB\x74" => "&#xE434;" => [s:399]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x75"), '[s:400]', '"\xFB\x75" => "&#xE435;" => [s:400]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x76"), '[s:401]', '"\xFB\x76" => "&#xE436;" => [s:401]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x77"), '[s:402]', '"\xFB\x77" => "&#xE437;" => [s:402]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x78"), '[s:403]', '"\xFB\x78" => "&#xE438;" => [s:403]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x79"), '[s:404]', '"\xFB\x79" => "&#xE439;" => [s:404]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x7A"), '[s:405]', '"\xFB\x7A" => "&#xE43A;" => [s:405]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x7B"), '[s:406]', '"\xFB\x7B" => "&#xE43B;" => [s:406]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x7C"), '[s:407]', '"\xFB\x7C" => "&#xE43C;" => [s:407]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x7D"), '[s:408]', '"\xFB\x7D" => "&#xE43D;" => [s:408]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x7E"), '[s:409]', '"\xFB\x7E" => "&#xE43E;" => [s:409]');

$t->diag('  FB7F     : invalid');
assertInvalidBetween($t, 0xFB7F, 0xFB7F, '[]');

$t->diag('  FB80-FB8D: Group#5, &#xE43F;-&#xE44C;, [s:410]-[s:423]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x80"), '[s:410]', '"\xFB\x80" => "&#xE43F;" => [s:410]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x81"), '[s:411]', '"\xFB\x81" => "&#xE440;" => [s:411]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x82"), '[s:412]', '"\xFB\x82" => "&#xE441;" => [s:412]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x83"), '[s:413]', '"\xFB\x83" => "&#xE442;" => [s:413]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x84"), '[s:414]', '"\xFB\x84" => "&#xE443;" => [s:414]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x85"), '[s:415]', '"\xFB\x85" => "&#xE444;" => [s:415]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x86"), '[s:416]', '"\xFB\x86" => "&#xE445;" => [s:416]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x87"), '[s:417]', '"\xFB\x87" => "&#xE446;" => [s:417]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x88"), '[s:418]', '"\xFB\x88" => "&#xE447;" => [s:418]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x89"), '[s:419]', '"\xFB\x89" => "&#xE448;" => [s:419]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x8A"), '[s:420]', '"\xFB\x8A" => "&#xE449;" => [s:420]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x8B"), '[s:421]', '"\xFB\x8B" => "&#xE44A;" => [s:421]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x8C"), '[s:422]', '"\xFB\x8C" => "&#xE44B;" => [s:422]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\x8D"), '[s:423]', '"\xFB\x8D" => "&#xE44C;" => [s:423]');

$t->diag('  FB8E-FBA0: invalid');
assertInvalidBetween($t, 0xFB8E, 0xFBA0, '[]');

$t->diag('  FBA1-FBD7: Group#6, &#xE501;-&#xE537;, [s:424]-[s:478]'); // http://creation.mb.softbank.jp/web/web_pic_06.html
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA1"), '[s:424]', '"\xFB\xA1" => "&#xE501;" => [s:424]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA2"), '[s:425]', '"\xFB\xA2" => "&#xE502;" => [s:425]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA3"), '[s:426]', '"\xFB\xA3" => "&#xE503;" => [s:426]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA4"), '[s:427]', '"\xFB\xA4" => "&#xE504;" => [s:427]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA5"), '[s:428]', '"\xFB\xA5" => "&#xE505;" => [s:428]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA6"), '[s:429]', '"\xFB\xA6" => "&#xE506;" => [s:429]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA7"), '[s:430]', '"\xFB\xA7" => "&#xE507;" => [s:430]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA8"), '[s:431]', '"\xFB\xA8" => "&#xE508;" => [s:431]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xA9"), '[s:432]', '"\xFB\xA9" => "&#xE509;" => [s:432]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xAA"), '[s:433]', '"\xFB\xAA" => "&#xE50A;" => [s:433]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xAB"), '[s:434]', '"\xFB\xAB" => "&#xE50B;" => [s:434]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xAC"), '[s:435]', '"\xFB\xAC" => "&#xE50C;" => [s:435]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xAD"), '[s:436]', '"\xFB\xAD" => "&#xE50D;" => [s:436]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xAE"), '[s:437]', '"\xFB\xAE" => "&#xE50E;" => [s:437]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xAF"), '[s:438]', '"\xFB\xAF" => "&#xE50F;" => [s:438]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB0"), '[s:439]', '"\xFB\xB0" => "&#xE510;" => [s:439]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB1"), '[s:440]', '"\xFB\xB1" => "&#xE511;" => [s:440]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB2"), '[s:441]', '"\xFB\xB2" => "&#xE512;" => [s:441]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB3"), '[s:442]', '"\xFB\xB3" => "&#xE513;" => [s:442]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB4"), '[s:443]', '"\xFB\xB4" => "&#xE514;" => [s:443]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB5"), '[s:444]', '"\xFB\xB5" => "&#xE515;" => [s:444]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB6"), '[s:445]', '"\xFB\xB6" => "&#xE516;" => [s:445]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB7"), '[s:446]', '"\xFB\xB7" => "&#xE517;" => [s:446]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB8"), '[s:447]', '"\xFB\xB8" => "&#xE518;" => [s:447]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xB9"), '[s:448]', '"\xFB\xB9" => "&#xE519;" => [s:448]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xBA"), '[s:449]', '"\xFB\xBA" => "&#xE51A;" => [s:449]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xBB"), '[s:450]', '"\xFB\xBB" => "&#xE51B;" => [s:450]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xBC"), '[s:451]', '"\xFB\xBC" => "&#xE51C;" => [s:451]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xBD"), '[s:452]', '"\xFB\xBD" => "&#xE51D;" => [s:452]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xBE"), '[s:453]', '"\xFB\xBE" => "&#xE51E;" => [s:453]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xBF"), '[s:454]', '"\xFB\xBF" => "&#xE51F;" => [s:454]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC0"), '[s:455]', '"\xFB\xC0" => "&#xE520;" => [s:455]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC1"), '[s:456]', '"\xFB\xC1" => "&#xE521;" => [s:456]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC2"), '[s:457]', '"\xFB\xC2" => "&#xE522;" => [s:457]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC3"), '[s:458]', '"\xFB\xC3" => "&#xE523;" => [s:458]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC4"), '[s:459]', '"\xFB\xC4" => "&#xE524;" => [s:459]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC5"), '[s:460]', '"\xFB\xC5" => "&#xE525;" => [s:460]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC6"), '[s:461]', '"\xFB\xC6" => "&#xE526;" => [s:461]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC7"), '[s:462]', '"\xFB\xC7" => "&#xE527;" => [s:462]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC8"), '[s:463]', '"\xFB\xC8" => "&#xE528;" => [s:463]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xC9"), '[s:464]', '"\xFB\xC9" => "&#xE529;" => [s:464]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xCA"), '[s:465]', '"\xFB\xCA" => "&#xE52A;" => [s:465]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xCB"), '[s:466]', '"\xFB\xCB" => "&#xE52B;" => [s:466]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xCC"), '[s:467]', '"\xFB\xCC" => "&#xE52C;" => [s:467]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xCD"), '[s:468]', '"\xFB\xCD" => "&#xE52D;" => [s:468]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xCE"), '[s:469]', '"\xFB\xCE" => "&#xE52E;" => [s:469]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xCF"), '[s:470]', '"\xFB\xCF" => "&#xE52F;" => [s:470]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD0"), '[s:471]', '"\xFB\xD0" => "&#xE530;" => [s:471]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD1"), '[s:472]', '"\xFB\xD1" => "&#xE531;" => [s:472]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD2"), '[s:473]', '"\xFB\xD2" => "&#xE532;" => [s:473]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD3"), '[s:474]', '"\xFB\xD3" => "&#xE533;" => [s:474]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD4"), '[s:475]', '"\xFB\xD4" => "&#xE534;" => [s:475]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD5"), '[s:476]', '"\xFB\xD5" => "&#xE535;" => [s:476]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD6"), '[s:477]', '"\xFB\xD6" => "&#xE536;" => [s:477]');
$t->is(OpenPNE_KtaiEmoji::convertSoftBankEmojiToOpenPNEFormat("\xFB\xD7"), '[s:478]', '"\xFB\xD7" => "&#xE537;" => [s:478]');

$t->diag('  FBD8-FBFF: invalid');
assertInvalidBetween($t, 0xFBD8, 0xFBFF, '[]');


//
// FCxx - not used on Yahoo! mobile
//
$t->diag('FC -- not used on Yahoo! mobile');
$t->diag('  FC00-FCFF: invalid');
assertInvalidBetween($t, 0xFC00, 0xFCFF, '');


//
// skipping FDxx - FFxx...
//
$t->diag('FD-FF (skipped)');

$t->diag('done.');

