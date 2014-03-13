<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$t = new lime_test(1, new lime_output_color());

$application = Doctrine::getTable('Application')->findOneByUrl("http://example.com/dummy.xml");
$member = Doctrine::getTable('Member')->find(1);
$memberApplication = Doctrine::getTable('MemberApplication')->findOneByApplicationAndMember($application, $member);

// ->set()
$t->diag('->set()');
$setting = Doctrine::getTable('MemberApplicationSetting')->set($memberApplication, 'application', 'tetete', 'tetete');
$t->isa_ok($setting, 'MemberApplicationSetting', '->set() return the MemberApplicationSetting object');
