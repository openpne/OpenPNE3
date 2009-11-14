<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(12, new lime_output_color());
$table = Doctrine::getTable('MemberRelationship');

//------------------------------------------------------------
$t->diag('MemberRelationshipTable');
$t->diag('MemberRelationshipTable::retrieveByFromAndTo()');
$t->isa_ok($table->retrieveByFromAndTo(1, 2), 'MemberRelationship');
$t->is($table->retrieveByFromAndTo(1, 999), null);

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::retrievesByMemberIdFrom()');
$result = $table->retrievesByMemberIdFrom(1);
$t->isa_ok($result, 'Doctrine_Collection');
$t->is($result->count(), 2);

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::getFriendListPager()');
$t->isa_ok($table->getFriendListPager(1), 'sfDoctrinePager');

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::getFriendMemberIds()');
$t->is($table->getFriendMemberIds(1), array(2));

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::friendConfirmList()');
$event = new sfEvent('subject', 'name', array('member' => Doctrine::getTable('Member')->find(1)));
MemberRelationshipTable::friendConfirmList($event);
$t->is(count($event->getReturnValue()), 1);

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::processFriendConfirm()');
$memberRelationship1 = new MemberRelationship();
$memberRelationship1->setMemberIdTo(1);
$memberRelationship1->setMemberIdFrom(3);
$memberRelationship1->setFriendPre();
$t->ok($memberRelationship1->isFriendPre());
$event = new sfEvent('subject', 'name', array('member' => Doctrine::getTable('Member')->find(1), 'id' => 3, 'is_accepted' => false));
MemberRelationshipTable::processFriendConfirm($event);
$t->ok(!$memberRelationship1->isFriendPre());

$memberRelationship2 = $table->retrieveByFromAndTo(4, 1);
$t->ok($memberRelationship2->isFriendPre());
$event = new sfEvent('subject', 'name', array('member' => Doctrine::getTable('Member')->find(1), 'id' => 4, 'is_accepted' => true));
MemberRelationshipTable::processFriendConfirm($event);
$t->ok($memberRelationship2->isFriend());

$event = new sfEvent('subject', 'name', array('member' => Doctrine::getTable('Member')->find(3), 'id' => 1, 'is_accepted' => true));
$t->ok(!MemberRelationshipTable::processFriendConfirm($event));
