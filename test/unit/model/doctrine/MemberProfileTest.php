<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
$user = sfContext::getInstance()->getUser();
$user->setAuthenticated(true);
$user->setMemberId(1);

$t = new lime_test(24, new lime_output_color());

$table = Doctrine::getTable('MemberProfile');
$memberProfile1 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_sex');
$memberProfile2 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_birthday');
$memberProfile3 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_region');
$memberProfile4 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_self_introduction');
$memberProfile5 = $table->retrieveByMemberIdAndProfileName(1, 'select_item');
$memberProfile6 = $table->retrieveByMemberIdAndProfileName(1, 'checkbox_item');
$memberProfile7 = $table->retrieveByMemberIdAndProfileName(1, 'date_item');
$memberProfile8 = $table->retrieveByMemberIdAndProfileName(2, 'checkbox_item');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');
$member5 = Doctrine::getTable('Member')->findOneByName('E');

//------------------------------------------------------------
$t->diag('MemberProfile::__toString()');
$t->is((string)$memberProfile1, 'Man');
$t->is((string)$memberProfile6, 'え, う');
$t->is((string)$memberProfile7, '1989-01-08');

//------------------------------------------------------------
$t->diag('MemberProfile::getValue()');
$t->is($memberProfile1->getValue(), 'Man');
$t->is($memberProfile2->getValue(), '1988-04-23');
$t->is($memberProfile5->getValue(), '1');
$t->is($memberProfile6->getValue(), array(2, 3));
$t->is($memberProfile7->getValue(), '1989-01-08');

//------------------------------------------------------------
$t->diag('MemberProfile::isViewable()');
$t->ok($memberProfile1->isViewable());
$t->ok($memberProfile1->isViewable(1));
$t->ok($memberProfile1->isViewable(2));
$t->ok($memberProfile1->isViewable(3));

$t->ok($memberProfile3->isViewable());
$t->ok($memberProfile3->isViewable(1));
$t->ok($memberProfile3->isViewable(2));
$t->ok(!$memberProfile3->isViewable(3));

$t->ok(!$memberProfile4->isViewable());
$t->ok(!$memberProfile4->isViewable(1));
$t->ok(!$memberProfile4->isViewable(2));
$t->ok(!$memberProfile4->isViewable(3));

//------------------------------------------------------------
$t->diag('MemberProfile::clearChildren()');
$memberProfile8->clearChildren();

//------------------------------------------------------------
$t->diag('MemberProfile::generateRoleId()');
$t->is($memberProfile1->generateRoleId($member1), 'self');
$t->is($memberProfile1->generateRoleId($member2), 'friend');
$t->is($memberProfile1->generateRoleId($member3), 'everyone');
$t->is($memberProfile1->generateRoleId($member5), 'blocked');
