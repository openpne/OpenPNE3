<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(16, new lime_output_color());

$table = Doctrine::getTable('MemberConfig');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');

//------------------------------------------------------------
$t->diag('MemberConfigTable');
$t->diag('MemberConfigTable::retrieveByNameAndMemberId()');
$result = $table->retrieveByNameAndMemberId('pc_address', 1);
$t->isa_ok($result, 'MemberConfig');

$result = $table->retrieveByNameAndMemberId('password', 1);
$t->isa_ok($result, 'MemberConfig');

//------------------------------------------------------------
$t->diag('MemberConfigTable::retrieveByNameAndValue()');
$result = $table->retrieveByNameAndValue('pc_address', 'sns@example.com');
$t->isa_ok($result, 'MemberConfig');

//------------------------------------------------------------
$t->diag('MemberConfigTable::retrievesByName()');
$result = $table->retrievesByName('pc_address');
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('MemberConfigTable::deleteDuplicatedPre()');
$memberConfig1 = $table->retrieveByNameAndMemberId('pc_address_pre', 2, true);
$memberConfig2 = $table->retrieveByNameAndMemberId('pc_address_pre', 3, true);
$t->isa_ok($memberConfig1, 'MemberConfig');
$t->isa_ok($memberConfig2, 'MemberConfig');
$table->deleteDuplicatedPre(2, 'pc_address', 'sns2@example.com');
$memberConfig1 = $table->retrieveByNameAndMemberId('pc_address_pre', 2, true);
$memberConfig2 = $table->retrieveByNameAndMemberId('pc_address_pre', 3, true);
$t->ok(!$memberConfig1);
$t->ok(!$memberConfig2);

//------------------------------------------------------------
$t->diag('MemberConfigTable::setValue()');
$memberConfig1 = $table->retrieveByNameAndMemberId('test1', 1);
$t->ok(!$memberConfig1);
$table->setValue(1, 'test1', 'bar');
$memberConfig1 = $table->retrieveByNameAndMemberId('test1', 1);
$t->isa_ok($memberConfig1, 'MemberConfig');

$memberConfig2 = $table->retrieveByNameAndMemberId('test2', 1);
$t->ok(!$memberConfig2);
$table->setValue(1, 'test2', '1989-01-08', true);
$memberConfig2 = $table->retrieveByNameAndMemberId('test2', 1);
$t->isa_ok($memberConfig2, 'MemberConfig');

//------------------------------------------------------------
$t->diag('ACL Test');
$memberConfig = $table->retrieveByNameAndMemberId('pc_address', 1);
$t->ok($memberConfig->isAllowed($member1, 'view'));
$t->ok(!$memberConfig->isAllowed($member2, 'view'));
$t->ok($memberConfig->isAllowed($member1, 'edit'));
$t->ok(!$memberConfig->isAllowed($member2, 'edit'));
