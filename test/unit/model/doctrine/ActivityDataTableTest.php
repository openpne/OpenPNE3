<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
opToolkit::clearCache();
include_once dirname(__FILE__) . '/fixtures/TestActivityTemplateConfigHandler.php';
$user = sfContext::getInstance()->getUser();
$user->setAuthenticated(true);
$user->setMemberId(1);

$t = new lime_test(40, new lime_output_color());
$table = Doctrine::getTable('ActivityData');

//------------------------------------------------------------
$t->diag('ActivityDataTable');
$t->diag('ActivityDataTable::updateActivity()');
$result = $table->updateActivity(1, 'test1');
$t->isa_ok($result, 'ActivityData', '->updateActivity() returns instance of ActivityData');

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
  )),
  'uri' => 'http://sns.example.com'
));
$t->isa_ok($result, 'ActivityData', '->updateActivity() returns instance of ActivityData');
$t->is($result->getPublicFlag(), 2, 'public_flag of ActivityData that created by ->updateActivity() is 2');
$t->is($result->getInReplyToActivityId(), 1, 'in_reply_to_activity_id of ActivityData that creatd by ->updateActivity() is 1');
$t->cmp_ok($result->getIsPc(), '===', false, 'is_pc of ActivityData that creatd by ->updateActivity() is false');
$t->cmp_ok($result->getIsMobile(), '===', false, 'is_mobile of ActivityData that creatd by ->updateActivity() is false');
$t->is($result->getSource(), 'API', 'source of ActivityData that creatd by ->updateActivity() is "API"');
$t->is($result->getSourceUri(), 'http://sns.example.com', 'source_uri of ActivityData that creatd by ->updateActivity() is "http://sns.example.com"');
$t->is(count($result->getImages()), 1, 'images of ActivityData that created by ->updateActivity() has 1 item');

$msg = '->updateActivity() throw LogicException when public_flag option is bad';
try
{
  $result = $table->updateActivity(1, 'test3', array('public_flag' => 999));
  $t->fail($msg);
}
catch (LogicException $e)
{
  $t->pass($msg);
}

$msg = '->updateActivity() throw LogicException when images option is bad';
try
{
  $result = $table->updateActivity(1, 'test4', array('images' => array(array('uri' => 'http://sns.example.com/image.png'))));
  $t->fail($msg);
}
catch (LogicException $e)
{
  $t->pass($msg);
}

$t->diag('ActivityDataTable::updateActivityByTemplate()');
$result = $table->updateActivityByTemplate(1, 'test_template', array('foo' => 'bar'));
$t->isa_ok($result, 'ActivityData', '->updateActivityByTemplate() returns instance of ActivityData');
$t->is($result->getTemplate(), 'test_template', 'template of ActivityData that created by ->updateActivityByTemplate() is "test_template"');
$t->is($result->getTemplateParam(), array('foo' => 'bar'), 'template_param of ActivityData that creatd by ->updateActivityByTemplate() is array that consists 1 item');

$t->diag('ActivityDataTable::getPublicFlags()');
$result = $table->getPublicFlags();
$t->todo('->getPublicFlags() returns array of public flags');

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_PRIVATE);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_PRIVATE,
  ActivityDataTable::PUBLIC_FLAG_FRIEND,
  ActivityDataTable::PUBLIC_FLAG_SNS,
  ActivityDataTable::PUBLIC_FLAG_OPEN
), '->getViewablePublicFlags() returns 4 public flags');

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_FRIEND);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_FRIEND,
  ActivityDataTable::PUBLIC_FLAG_SNS,
  ActivityDataTable::PUBLIC_FLAG_OPEN
), '->getViewablePublicFlags() returns 3 public flags');

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_SNS);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_SNS,
  ActivityDataTable::PUBLIC_FLAG_OPEN
), '->getViewablePublicFlags() returns 2 public flags');

$result = $table->getViewablePublicFlags(ActivityDataTable::PUBLIC_FLAG_OPEN);
$t->is($result, array(
  ActivityDataTable::PUBLIC_FLAG_OPEN
), '->getViewablePublicFlags() returns 1 public flag');

$result = $table->getViewablePublicFlags(999);
$t->is($result, array(), '->getViewablePublicFlags() returns empty array');

$t->diag('ActivityDataTable::getFriendActivityList()');
$result = $table->getFriendActivityList();
$t->isa_ok($result, 'Doctrine_Collection', '->getFriendActivityLis() returns instance of Doctrine_Collection');
$t->is(count($result), 5, '->getFriendActivityList() returns Doctrine_Collection that consists of 5');

$result = $table->getFriendActivityList(1);
$t->isa_ok($result, 'Doctrine_Collection', '->getFriendActivityList() returns instance of Doctrine_Collection');
$t->is(count($result), 5, '->getFriendActivityList() returns Doctrine_Collection that consists of 5');

$result = $table->getFriendActivityList(999);
$t->isa_ok($result, 'Doctrine_Collection', '->getFriendActivityList() returns instance of Doctrine_Collection');
$t->is(count($result), 0, '->getFriendActivityList() returns empty Doctrine_Collection');

$t->diag('ActivityDataTable::getFriendActivityListPager()');
$result = $table->getFriendActivityListPager(1);
$t->isa_ok($result, 'sfDoctrinePager', '->getFriendActivityListPager() returns instance of sfDoctrinePager');

$t->diag('ActivityDataTable::getActivityList()');
$result = $table->getActivityList();
$t->isa_ok($result, 'Doctrine_Collection', '->getActivityList() returns instance of Doctrine_Collection');
$t->is(count($result), 5, '->getActivityList() returns Doctrine_Collection that consists 5');

$result = $table->getActivityList(1, 1);
$t->isa_ok($result, 'Doctrine_Collection', '->getActivityList() returns instance of Doctrine_Collection');
$t->is(count($result), 5, '->getActivityList() returns Doctrine_Collection that consists 5');

$result = $table->getActivityList(999, 1);
$t->isa_ok($result, 'Doctrine_Collection', '->getActivityList() returns instance of Doctrine_Collection');
$t->is(count($result), 0, '->getActivityList() returns empty Doctrine_Collection');

$t->diag('ActivityDataTable::getActivityListPager()');
$result = $table->getActivityListPager(1, 1);
$t->isa_ok($result, 'sfDoctrinePager', '->getActivityListPager() returns instance of sfDoctrinePager');

$t->diag('ActivityDataTable::getAllMemberActivityList()');
$result = $table->getAllMemberActivityList();
$t->isa_ok($result, 'Doctrine_Collection', '->getAllMemberActivityList() returns instance of Doctrine_Collection');
$t->is(count($result), 5, '->getAllMemberActivityList() returns Doctrine_Collection that consists 5');

$t->diag('ActivityDataTable::getAllMemberActivityListPager()');
$result = $table->getAllMemberActivityListPager();
$t->isa_ok($result, 'sfDoctrinePager', '->getAllMemberActivityListPager() returns instance of sfDoctrinePager');

$t->diag('ActivityDataTable::getTemplateConfig()');
$result = $table->getTemplateConfig();
$t->cmp_ok($result, '===', array(
  'test_template' => 'Test test %member_1_nickname% test, %foo%!!!'
), 'getTemplateConfig() returns array of template config');

$t->diag('ActivityDataTable::filterBody()');
$dummyEvent = new sfEvent('', 'dummy');
$result = ActivityDataTable::filterBody($dummyEvent, '%member_1_nickname%');
$t->is($result, 'A', 'ActivityDataTable::filterBody() converts from "%member_1_nickname%" to "A"');

$result = ActivityDataTable::filterBody($dummyEvent, '%member_999_nickname%');
$t->is($result, '-', 'ActivityDataTable::filterBody() converts from "%member_999_nickname%" to "-"');
