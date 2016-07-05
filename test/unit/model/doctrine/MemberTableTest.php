<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(12, new lime_output_color());
$table = Doctrine::getTable('Member');
$member1 = $table->findOneByName('A');
$member2 = $table->findOneByName('B');
$member3 = $table->findOneByName('C');
$member4 = $table->findOneByName('D');
$member5 = $table->findOneByName('E');

//------------------------------------------------------------
$t->diag('MemberTable');
$t->diag('MemberTable::createPre()');
$t->isa_ok($table->createPre(), 'Member');

//------------------------------------------------------------
$t->diag('MemberTable::searchMemberIds()');
$t->todo('$table->searchMemberIds(\'A\')');
$t->is($table->searchMemberIds('A', array(1)), array(1));

//------------------------------------------------------------
$t->diag('MemberTable::retrivesByInviteMemberId()');
$t->isa_ok($table->retrivesByInviteMemberId(4), 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('ACL Test');
$t->cmp_ok($member1->isAllowed($member1, 'view'), '===', true);
$t->cmp_ok($member1->isAllowed($member2, 'view'), '===', true);
$t->cmp_ok($member1->isAllowed($member3, 'view'), '===', true);
$t->cmp_ok($member1->isAllowed($member5, 'view'), '===', false);

$t->cmp_ok($member1->isAllowed($member1, 'edit'), '===', true);
$t->cmp_ok($member1->isAllowed($member2, 'edit'), '===', false);
$t->cmp_ok($member1->isAllowed($member3, 'edit'), '===', false);
$t->cmp_ok($member5->isAllowed($member1, 'edit'), '===', false);
