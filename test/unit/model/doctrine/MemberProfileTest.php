<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
$user = sfContext::getInstance()->getUser();
$user->setAuthenticated(true);
$user->setMemberId(1);

$t = new lime_test(28, new lime_output_color());

$table = Doctrine::getTable('MemberProfile');
$memberProfile1 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_sex');
$memberProfile2 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_birthday');
$memberProfile3 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_region');
$memberProfile4 = $table->retrieveByMemberIdAndProfileName(1, 'op_preset_self_introduction');
$memberProfile5 = $table->retrieveByMemberIdAndProfileName(1, 'select_item');
$memberProfile6 = $table->retrieveByMemberIdAndProfileName(1, 'checkbox_item');
$memberProfile7 = $table->retrieveByMemberIdAndProfileName(1, 'date_item');
$memberProfile8 = $table->retrieveByMemberIdAndProfileName(2, 'checkbox_item');
$memberProfile9 = $table->retrieveByMemberIdAndProfileName(2, 'op_preset_birthday');
$member1 = Doctrine::getTable('Member')->findOneByName('A');
$member2 = Doctrine::getTable('Member')->findOneByName('B');
$member3 = Doctrine::getTable('Member')->findOneByName('C');
$member5 = Doctrine::getTable('Member')->findOneByName('E');

//------------------------------------------------------------
$t->diag('MemberProfile::__toString()');
$t->is((string)$memberProfile1, 'Man', '->__toString() returns "Man"');
$t->is((string)$memberProfile5, 'あ', '->__toString() returns "あ"');
$t->is((string)$memberProfile6, 'え, う', '->__toString() returns "え, う"');
$t->is((string)$memberProfile7, '1989-01-08', '->__toString() returns "1989-01-08"');

//------------------------------------------------------------
$t->diag('MemberProfile::getValue()');
$t->is($memberProfile1->getValue(), 'Man', '->getValue() returns "Man"');
$t->is($memberProfile2->getValue(), '1988-04-23', '->getValue() returns "1988-04-23"');
$t->is($memberProfile5->getValue(), '1', '->getValue() returns "1"');
$t->is($memberProfile6->getValue(), array(2, 3), '->getValue() returns array of selected index');
$t->is($memberProfile7->getValue(), '1989-01-08', '->getValue() returns "1989-01-08"');
$t->cmp_ok($memberProfile9->getValue(), '===', null, '->getValue() returns null');

//------------------------------------------------------------
$t->diag('MemberProfile::isViewable()');
$t->ok($memberProfile1->isViewable(), '->isViewable() returns true');
$t->ok($memberProfile1->isViewable(1), '->isViewable() returns true');
$t->ok($memberProfile1->isViewable(2), '->isViewable() returns true');
$t->ok($memberProfile1->isViewable(3), '->isViewable() returns true');

$t->ok($memberProfile3->isViewable(), '->isViewable() returns true');
$t->ok($memberProfile3->isViewable(1), '->isViewable() returns true');
$t->ok($memberProfile3->isViewable(2), '->isViewable() returns true');
$t->ok(!$memberProfile3->isViewable(3), '->isViewable() returns false');

$t->ok(!$memberProfile4->isViewable(), '->isViewable() returns false');
$t->ok(!$memberProfile4->isViewable(1), '->isViewable() returns false');
$t->ok(!$memberProfile4->isViewable(2), '->isViewable() returns false');
$t->ok(!$memberProfile4->isViewable(3), '->isViewable() returns false');

//------------------------------------------------------------
$t->diag('MemberProfile::clearChildren()');
$t->is($memberProfile8->getNode()->getChildren()->count(), 1, 'member profile has a child before ->clearChildren()');
$memberProfile8->clearChildren();
$t->cmp_ok($memberProfile8->getNode()->getChildren(), '===', false, "member profile hasn't child after ->clearChildren()");

//------------------------------------------------------------
$t->diag('MemberProfile::generateRoleId()');
$t->is($memberProfile1->generateRoleId($member1), 'self', '->generateRoleId() returns "self"');
$t->is($memberProfile1->generateRoleId($member2), 'friend', '->generateRoleId() returns "friend"');
$t->is($memberProfile1->generateRoleId($member3), 'everyone', '->generateRoleId() returns "everyone"');
$t->is($memberProfile1->generateRoleId($member5), 'blocked', '->generateRoleId() returns "blocked"');
