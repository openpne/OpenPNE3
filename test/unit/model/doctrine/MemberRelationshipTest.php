<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());
$table = Doctrine::getTable('MemberRelationship');

// create model for test
$relation1 = $table->retrieveByFromAndTo(1, 2); //is_friend
$relation2 = $table->retrieveByFromAndTo(2, 1); //is_friend
$relation3 = $table->retrieveByFromAndTo(4, 1); //is_friend_pre
$relation4 = $table->retrieveByFromAndTo(1, 5); //is_access_block

$dummyRelation1 = new MemberRelationship();
$dummyRelation1->setMemberIdTo(4);
$dummyRelation1->setMemberIdFrom(1);

$dummyRelation2 = new MemberRelationship();
$dummyRelation2->setMemberIdTo(1);
$dummyRelation2->setMemberIdFrom(1);

$dummyRelation3 = new MemberRelationship();
$dummyRelation3->setMemberIdTo(1);
$dummyRelation3->setMemberIdFrom(5);

$newRelation1 = new MemberRelationship();
$newRelation1->setMemberIdTo(3);
$newRelation1->setMemberIdFrom(1);

//------------------------------------------------------------
$t->diag('MemberRelationship');
$t->diag('MemberRelationship::preSave()');
try
{
  $dummyRelation2->save();
  $t->fail();
}
catch (LogicException $e)
{
  $t->pass();
}
catch (Exception $e)
{
  $t->fail();
}

//------------------------------------------------------------
$t->diag('MemberRelationship::isFriend()');
$t->ok($relation1->isFriend());
$t->ok($relation2->isFriend());
$t->ok(!$relation3->isFriend());

//------------------------------------------------------------
$t->diag('MemberRelationship::isFriendPreFrom()');
$t->ok(!$relation1->isFriendPreFrom());
$t->ok(!$relation2->isFriendPreFrom());
$t->ok($relation3->isFriendPreFrom());

//------------------------------------------------------------
$t->diag('MemberRelationship::isFriendPreTo()');
$t->ok(!$relation1->isFriendPreTo());
$t->ok(!$relation2->isFriendPreTo());
$t->ok(!$relation3->isFriendPreTo());
$t->ok($dummyRelation1->isFriendPreTo());

//------------------------------------------------------------
$t->diag('MemberRelationship::isFriendPre()');
$t->ok(!$relation1->isFriendPre());
$t->ok(!$relation2->isFriendPre());
$t->ok($relation3->isFriendPre());
$t->ok($dummyRelation1->isFriendPre());

//------------------------------------------------------------
$t->diag('MemberRelationship::isSelf()');
$t->ok(!$dummyRelation1->isSelf());
$t->ok($dummyRelation2->isSelf());

//------------------------------------------------------------
$t->diag('MemberRelationship::isAccessBlocked()');
$t->ok(!$relation4->isAccessBlocked());
$t->ok($dummyRelation3->isAccessBlocked());

//------------------------------------------------------------
$t->diag('MemberRelationship::setFriendPre()');
$t->ok(!$newRelation1->isFriendPre());
$newRelation1->setFriendPre();
$t->ok($newRelation1->isFriendPre());

//------------------------------------------------------------
$t->diag('MemberRelationship::setFriend()');
$t->ok(!$newRelation1->isFriend());
$newRelation1->setFriend();
$t->ok($newRelation1->isFriend());

//------------------------------------------------------------
$t->diag('MemberRelationship::removeFriend()');
$t->ok($newRelation1->isFriend());
$newRelation1->removeFriend();
$t->ok(!$newRelation1->isFriend());

//------------------------------------------------------------
$t->diag('MemberRelationship::removeFriendPre()');
$newRelation1->setFriendPre();
$t->ok($newRelation1->isFriendPre());
$newRelation1->removeFriendPre();
$t->ok(!$newRelation1->isFriendPre());

//------------------------------------------------------------
$t->diag('MemberRelationship::getToInstance()');
$t->isa_ok($newRelation1->getToInstance(), 'MemberRelationship');
