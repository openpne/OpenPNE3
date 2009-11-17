<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(3, new lime_output_color());

$table = Doctrine::getTable('OAuthMemberToken');

//------------------------------------------------------------
$t->diag('OAuthMemberTokenTable');
$t->diag('OAuthMemberTokenTable::findByKeyString()');
$result = $table->findByKeyString('EEEEEEEEEEEEEEEE');
$t->isa_ok($result, 'OAuthMemberToken');

$result = $table->findByKeyString('FFFFFFFFFFFFFFFF', 'access');
$t->isa_ok($result, 'OAuthMemberToken');

$query = $table->createQuery();
$result = $table->findByKeyString('EEEEEEEEEEEEEEEE', 'request', $query);
$t->isa_ok($result, 'OAuthMemberToken');
