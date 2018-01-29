<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(23, new lime_output_color());

$application1 = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy.xml");
$application2 = Doctrine::getTable('Application')->findOneByUrl("http://gist.github.com/raw/183505/a7f3d824cdcbbcf14c06f287537d0acb0b3e5468/gistfile1.xsl");
$application3 = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy3.xml");
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');
$member4 = Doctrine::getTable('Member')->findOneByName('D');
$member5 = Doctrine::getTable('Member')->findOneByName('E');
$memberApplication1 = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($application1, $member1);
$memberApplication2 = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($application2, $member1);
$memberApplication3 = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($application3, $member1);

// ->getApplicationSettings()
$t->diag('->getApplicationSettings()');
$applicationSettings = $memberApplication1->getApplicationSettings();
$t->ok(is_array($applicationSettings) && count($applicationSettings) === 2);

// ->getApplicationSetting()
$t->diag('->getApplicationSetting()');
$value1 = $memberApplication1->getApplicationSetting('is_view_home', 'default');
$t->is($value1, '1');
$value2 = $memberApplication1->getApplicationSetting('tetete', 'default');
$t->is($value2, 'default');

// ->setApplicationSetting()
$t->diag('->setApplicationSetting()');
$memberApplication1->setApplicationSetting('tetete', 'tetete');
$value3 = $memberApplication1->getApplicationSetting('tetete', 'default');
$t->is($value3, 'tetete');

// ->getUserSettings()
$t->diag('->getUserSettings()');
$userSettings = $memberApplication1->getUserSettings();
$t->ok(is_array($userSettings) && count($userSettings) === 1);

// ->getUserSetting()
$t->diag('->getUserSetting()');
$value4 = $memberApplication1->getUserSetting('user_setting', 'default');
$t->is($value4, '1');
$value5 = $memberApplication1->getUserSetting('tetete', 'default');
$t->is($value5, 'default');

// ->setUserSetting()
$t->diag('->setUserSetting()');
$memberApplication1->setUserSetting('tetete', 'tetete');
$value6 = $memberApplication1->getUserSetting('tetete', 'default');
$t->is($value6, 'tetete');

// ->getViewable()
$t->diag('->setVieable()');
$t->ok($memberApplication1->isViewable($member1->getId()));
$t->ok($memberApplication1->isViewable($member2->getId()));
$t->ok($memberApplication1->isViewable($member3->getId()));
$t->ok($memberApplication1->isViewable($member4->getId()));
$t->ok(!$memberApplication1->isViewable($member5->getId()));

$t->ok($memberApplication2->isViewable($member1->getId()));
$t->ok($memberApplication2->isViewable($member2->getId()));
$t->ok(!$memberApplication2->isViewable($member3->getId()));
$t->ok(!$memberApplication2->isViewable($member4->getId()));
$t->ok(!$memberApplication2->isViewable($member5->getId()));

$t->ok($memberApplication3->isViewable($member1->getId()));
$t->ok(!$memberApplication3->isViewable($member2->getId()));
$t->ok(!$memberApplication3->isViewable($member3->getId()));
$t->ok(!$memberApplication3->isViewable($member4->getId()));
$t->ok(!$memberApplication3->isViewable($member5->getId()));
