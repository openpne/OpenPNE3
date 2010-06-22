<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
$user = sfContext::getInstance()->getUser();
$user->setAuthenticated(true);
$user->setMemberId(1);

$t = new lime_test(34, new lime_output_color());
$table = Doctrine::getTable('ActivityData');

//------------------------------------------------------------
$t->diag('ActivityDataTable');
$t->diag('ActivityDataTable::updateActivity()');
$result = $table->updateActivity(1, 'test1');
$t->isa_ok($result, 'ActivityData');

$result = $table->updateActivity(1, 'test2', array(
  'public_flag' => 2,
  'in_reply_to_activity_id' => 1,
  'is_pc' => false,
  'is_mobile' => false,
  'source' => 'API',
  'source_uri' => 'http://sns.example.com',
  'images' => array(array(
    'uri' => 'http://sns.example.com/test.png',
    'mime_type' => 'image/png'
  ))
));
$t->isa_ok($result, 'ActivityData');
$t->is($result->getPublicFlag(), 2);
$t->is($result->getInReplyToActivityId(), 1);
$t->is($result->getIsPc(), false);
$t->is($result->getIsMobile(), false);
$t->is($result->getSource(), 'API');
$t->is($result->getSourceUri(), 'http://sns.example.com');
$t->is(count($result->getImages()), 1);

try
{
  $result = $table->updateActivity(1, 'test3', array('public_flag' => 999));
  $t->fail();
}
catch (LogicException $e)
{
  $t->pass();
}

try
{
  $result = $table->updateActivity(1, 'test4', array('images' => array(array('uri' => 'http://sns.example.com/image.png'))));
  $t->fail();
}
catch (LogicException $e)
{
  $t->pass();
}

$t->diag('ActivityDataTable::getPublicFlags()');
$result = $table->getPublicFlags();
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_SNS => '全員に公開',
  ActivityDataTable::PUBLIC_FLAG_FRIEND => 'マイフレンドまで公開',
  ActivityDataTable::PUBLIC_FLAG_PRIVATE => '公開しない',
));

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_PRIVATE);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_PRIVATE,
  ActivityDataTable::PUBLIC_FLAG_FRIEND,
  ActivityDataTable::PUBLIC_FLAG_SNS,
  ActivityDataTable::PUBLIC_FLAG_OPEN
));

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_FRIEND);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_FRIEND,
  ActivityDataTable::PUBLIC_FLAG_SNS,
  ActivityDataTable::PUBLIC_FLAG_OPEN
));

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_SNS);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_SNS,
  ActivityDataTable::PUBLIC_FLAG_OPEN
));

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_OPEN);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_OPEN
));

$result = $table->getViewablePublicFlags(999);
$t->is($result, array());

$t->diag('ActivityDataTable::getFriendActivityList()');
$result = $table->getFriendActivityList();
$t->isa_ok($result, 'Doctrine_Collection');
$t->is(count($result), 5);

$result = $table->getFriendActivityList(1);
$t->isa_ok($result, 'Doctrine_Collection');
$t->is(count($result), 5);

$result = $table->getFriendActivityList(999);
$t->isa_ok($result, 'Doctrine_Collection');
$t->is(count($result), 0);

$t->diag('ActivityDataTable::getFriendActivityListPager()');
$result = $table->getFriendActivityListPager(1);
$t->isa_ok($result, 'sfDoctrinePager');

$t->diag('ActivityDataTable::getActivityList()');
$result = $table->getActivityList();
$t->isa_ok($result, 'Doctrine_Collection');
$t->is(count($result), 5);

$result = $table->getActivityList(1, 1);
$t->isa_ok($result, 'Doctrine_Collection');
$t->is(count($result), 5);

$result = $table->getActivityList(999, 1);
$t->isa_ok($result, 'Doctrine_Collection');
$t->is(count($result), 0);

$t->diag('ActivityDataTable::getActivityListPager()');
$result = $table->getActivityListPager(1, 1);
$t->isa_ok($result, 'sfDoctrinePager');

$t->diag('ActivityDataTable::getAllMemberActivityList()');
$result = $table->getAllMemberActivityList();
$t->isa_ok($result, 'Doctrine_Collection');
$t->is(count($result), 5);

$t->diag('ActivityDataTable::getAllMemberActivityListPager()');
$result = $table->getAllMemberActivityListPager();
$t->isa_ok($result, 'sfDoctrinePager');
