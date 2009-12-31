<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(16, new lime_output_color());
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
$t->is($result->count(), 6);

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::getFriendListPager()');
$t->isa_ok($table->getFriendListPager(1), 'sfDoctrinePager');

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::getFriendMemberIds()');
$t->is($table->getFriendMemberIds(1), array(2, 4, 7, 8));

//------------------------------------------------------------
$t->diag('MemberRelationship::getFriends()');
$t->isa_ok($table->getFriends(1, 1, false), 'Doctrine_Collection', 'getFriends() returns Doctrine_Collection');
$randomFriend1 = $table->getFriends(1, 3, true)->toArray();
$randomFriend2 = $table->getFriends(1, 3, true)->toArray();
$randomFriendNames1 = array();
$randomFriendNames2 = array();

$t->cmp_ok(count($randomFriend1), '<=', 3, 'getFriends() returns 3 records at most');
if (1 < count($randomFriend1))
{
  $isRandom = false;
  while ($randomFriend1)
  {
    $rf1 = array_shift($randomFriend1);
    $rf2 = array_shift($randomFriend2);
    if ($rf1['name'] !== $rf2['name'])
    {
      $isRandom = true;
    }

    $randomFriendNames1[] = $rf1['name'];
    $randomFriendNames2[] = $rf2['name'];
  }
  sort($randomFriendNames1);
  sort($randomFriendNames2);
  $t->isnt(count(array_diff($randomFriendNames1, $randomFriendNames2)), 0, 'getFriends() returns random in limitation');
  $t->is($isRandom, true, 'getFriends() returns random order');
}
else
{
  $t->skip('getFriends() returns random order (too few friends)');
}

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
