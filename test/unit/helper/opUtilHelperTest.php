<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once sfConfig::get('sf_lib_dir').'/helper/opUtilHelper.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------
$t->diag('cycle_vars()');
$t->is(cycle_vars('test', 'item1,item2'), 'item1');
$t->is(cycle_vars('test', 'item1,item2'), 'item2');
$t->is(cycle_vars('test', 'item1,item2'), 'item1');

//------------------------------------------------------------
$t->diag('op_format_last_login_time()');
$now = time();
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
