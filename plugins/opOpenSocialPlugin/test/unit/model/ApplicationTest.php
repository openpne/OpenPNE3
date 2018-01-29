<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(11, new lime_output_color());

$application1 = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy.xml");
$application2 = Doctrine::getTable('Application')->findOneByUrl("http://gist.github.com/raw/183505/a7f3d824cdcbbcf14c06f287537d0acb0b3e5468/gistfile1.xsl");
$member = Doctrine::getTable('Member')->find(1);

// ->addToMember()
$t->diag('->addToMember()');
$memberApplication1 = $application2->addToMember($member);
$t->isa_ok($memberApplication1, 'MemberApplication', '->addToMember() return MemberApplication object');

$memberApplication2 = $application2->addToMember($member, array('is_view_home' => true));
$applicationSettings = $memberApplication2->getApplicationSettings();
$t->ok(is_array($applicationSettings) && count($applicationSettings) === 1, '->addToMember() save member Application Settings');

// ->isHadByMember()
$t->diag('->isHadByMember()');
$t->ok($application1->isHadByMember(1), '->isHadByMember() return true when the member has the application');
$t->ok(!$application1->isHadByMember(999), '->isHadByMember() return false when the member has not the application');

// ->getMemberListPager()
$t->diag('->getMemberListPager()');
$t->isa_ok($application1->getMemberListPager(), 'sfDoctrinePager', '->getMemberListPager() return sfDoctrinePager object');

// ->getPersistentData()
$t->diag('->getPersistentData()');
$t->isa_ok($application1->getPersistentData(1, 'test_key'), 'ApplicationPersistentData', '->getPersistentData() return ApplicationPersisetentData object');

// ->getPersistentDatas()
$t->diag('->getPersistentDatas()');
$persistentDatas1 = $application1->getPersistentDatas(2, array());
$t->isa_ok($persistentDatas1, 'Doctrine_Collection', '->getPersistentDatas() return Doctrine_Collection object');
$persistentDatas2 = $application1->getPersistentDatas(array(), array());
$t->ok($persistentDatas2 === null, '->getPersistentData() return null when memberId is blank array');

// ->updateApplication()
$t->diag('->updateApplication()');
$t->isa_ok($application2->updateApplication('ja_JP'), 'Application', '->updateApplication() return Application object');

// ->isActive()
$t->diag('->isActive()');
$t->isa_ok($application1->isActive(), 'boolean', '->isActive() returns boolean');

// ->countMembers()
$t->diag('->countMembers()');
$t->is($application1->countMembers(), 1, '->countMembers() returns 1');
