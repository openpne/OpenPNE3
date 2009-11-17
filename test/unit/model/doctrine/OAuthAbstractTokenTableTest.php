<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(2, new lime_output_color());

//------------------------------------------------------------
$t->diag('OAuthAbstractTokenTable');
$t->diag('OAuthAbstractTokenTable::findByKeyString()');
$result = Doctrine::getTable('OAuthAdminToken')->findByKeyString('CCCCCCCCCCCCCCCC');
$t->isa_ok($result, 'OAuthAdminToken');

$result = Doctrine::getTable('OAuthAdminToken')->findByKeyString('DDDDDDDDDDDDDDDD', 'access');
$t->isa_ok($result, 'OAuthAdminToken');

