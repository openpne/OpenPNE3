<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true));

include_once sfConfig::get('sf_lib_dir').'/vendor/symfony/lib/helper/HelperHelper.php';
use_helper('opUtil', 'Url', 'Tag', 'Escaping');

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------
$t->diag('cycle_vars()');
$t->is(cycle_vars('test', 'item1,item2'), 'item1');
$t->is(cycle_vars('test', 'item1,item2'), 'item2');
$t->is(cycle_vars('test', 'item1,item2'), 'item1');

//------------------------------------------------------------
$t->diag('op_format_last_login_time() setCulture en');
$now = time();
sfContext::getInstance()->getUser()->setCulture('en');
$t->is(op_format_last_login_time($now - 2, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 8, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 13, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 25, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 49, $now), 'less than a minute ago');
$t->is(op_format_last_login_time($now - 60, $now), '1 minute ago');

$t->is(op_format_last_login_time($now - 10 * 60, $now), '10 minutes ago');
$t->is(op_format_last_login_time($now - 50 * 60, $now), 'about 1 hour ago');

$t->is(op_format_last_login_time($now - 3 * 3600, $now), 'about 3 hours ago');
$t->is(op_format_last_login_time($now - 25 * 3600, $now), '1 day ago');

$t->is(op_format_last_login_time($now - 4 * 86400, $now), '4 days ago');
$t->is(op_format_last_login_time($now - 35 * 86400, $now), 'about 1 month ago');
$t->is(op_format_last_login_time($now - 75 * 86400, $now), '3 months ago');

$t->is(op_format_last_login_time($now - 370 * 86400, $now), 'about 1 year ago');
$t->is(op_format_last_login_time($now - 4 * 370 * 86400, $now), 'over 4 years ago');
$t->is(op_format_last_login_time($now - 1000 * 86400, $now), 'over 2 years ago');

$t->diag('op_format_last_login_time() setCulture ja_JP');
sfContext::getInstance()->getUser()->setCulture('ja_JP');
$t->is(op_format_last_login_time($now - 2, $now), '1分以内');
$t->is(op_format_last_login_time($now - 8, $now), '1分以内');
$t->is(op_format_last_login_time($now - 13, $now), '1分以内');
$t->is(op_format_last_login_time($now - 25, $now), '1分以内');
$t->is(op_format_last_login_time($now - 49, $now), '1分以内');
$t->is(op_format_last_login_time($now - 60, $now), '1分前');

$t->is(op_format_last_login_time($now - 10 * 60, $now), '10分前');
$t->is(op_format_last_login_time($now - 50 * 60, $now), '1時間前');

$t->is(op_format_last_login_time($now - 3 * 3600, $now), '3時間前');
$t->is(op_format_last_login_time($now - 25 * 3600, $now), '1日前');

$t->is(op_format_last_login_time($now - 4 * 86400, $now), '4日前');
$t->is(op_format_last_login_time($now - 35 * 86400, $now), '1ヶ月前');
$t->is(op_format_last_login_time($now - 75 * 86400, $now), '3ヶ月前');

$t->is(op_format_last_login_time($now - 370 * 86400, $now), '1年前');
$t->is(op_format_last_login_time($now - 4 * 370 * 86400, $now), '4年以上前');
$t->is(op_format_last_login_time($now - 1000 * 86400, $now), '2年以上前');
//------------------------------------------------------------
$t->diag('op_link_to_member()');
$t->is(op_link_to_member(1), link_to('A', '@obj_member_profile?id=1'), 'link to member 1');
$t->is(op_link_to_member(1, array(), '@obj_friend_unlink'), link_to('A', '@obj_friend_unlink?id=1'), 'link to unlink member 1');
$t->is(op_link_to_member(1, array('link_target' => 'tetetete'), '@obj_member_profile'), link_to('tetetete', '@obj_member_profile?id=1'), 'link to member 1 (free text)');
$member2 = Doctrine::getTable('Member')->find(2);
$t->is(op_link_to_member($member2), link_to($member2->getName(), 'obj_member_profile', $member2), 'set Member object');
$t->is(op_link_to_member(9999), '-', 'set undefine member');
$t->is(op_link_to_member(null), '-', 'set null member');

Doctrine::getTable('SnsConfig')->set('nickname_of_member_who_does_not_have_credentials', 'I am a pen.');
$t->is(op_link_to_member(null), 'I am a pen.', 'set nickname_of_member_who_does_not_have_credentials original setting');
