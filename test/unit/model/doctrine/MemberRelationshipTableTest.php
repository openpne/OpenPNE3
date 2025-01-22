<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

opMailSend::initialize();
Zend_Mail::setDefaultTransport(new opZendMailTransportMock());

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
$t->todo('is($result->count(), 6)');

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::getFriendListPager()');
$t->todo();

//------------------------------------------------------------
$t->diag('MemberRelationshipTable::getFriendMemberIds()');
$t->todo();

//------------------------------------------------------------
$t->diag('MemberRelationship::getFriends()');
$t->isa_ok($table->getFriends(1, 1, false), 'Doctrine_Collection', 'getFriends() returns Doctrine_Collection');

$randomFriend = $table->getFriends(1, 3, true)->toArray();
$t->cmp_ok(count($randomFriend), '<=', 3, 'getFriends() returns 3 records at most');

if (1 < count($randomFriend))
{
  $isRandom = false;
  for ($i = 0; $i < 10; $i++)
  {
    $current = $table->getFriends(1, 3, true)->toArray();

    if ($current !== $randomFriend)
    {
      $isRandom = true;
      break;
    }
  }
  $t->is($isRandom, true, 'getFriends() returns random order');
}
else
{
  $t->skip('getFriends() returns random order (too few friends)');
}

if (3 < count($randomFriend))
{
  $randomFriend = $table->getFriends(1, 3, true)->toArray();
  $randomFriendIds = array();
  foreach ($randomFriend as $f)
  {
    $randomFriendIds[] = $f['id'];
  }
  sort($randomFriendIds);

  $isRandom = false;
  for ($i = 0; $i < 10; $i++)
  {
    $current = $table->getFriends(1, 3, true)->toArray();

    $currentIds = array();
    foreach ($current as $f)
    {
      $currentIds[] = $f['id'];
    }
    sort($currentIds);

    if (count(array_diff($randomFriendIds, $currentIds)) > 0)
    {
      $isRandom = true;
      break;
    }
  }
  $t->is($isRandom, true, 'getFriends() returns random in limitation');
}
else
{
  $t->skip('getFriends() returns random in limitation (too few friends)');
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
