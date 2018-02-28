<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
$user = sfContext::getInstance()->getUser();
$user->setAuthenticated(true);
$user->setMemberId(1);

$t = new lime_test(12, new lime_output_color());
$table = Doctrine::getTable('MemberProfile');

//------------------------------------------------------------
$t->diag('MemberProfileTable');
$t->diag('MemberProfileTable::getProfileListByMemberId()');
$memberProfiles = $table->getProfileListByMemberId(1);
$t->is(count($memberProfiles), 9, 'getProfileListByMemberId() returns 6 profiles for member ID:1');

//------------------------------------------------------------
$t->diag('MemberProfileTable::getViewableProfileListByMemberId()');
$memberProfiles = $table->getViewableProfileListByMemberId(1);
$t->is(count($memberProfiles), 8);
$memberProfiles = $table->getViewableProfileListByMemberId(1, 1);
$t->is(count($memberProfiles), 8);
$memberProfiles = $table->getViewableProfileListByMemberId(1, 2);
$t->is(count($memberProfiles), 8);
$memberProfiles = $table->getViewableProfileListByMemberId(1, 3);
$t->is(count($memberProfiles), 7);

//------------------------------------------------------------
$t->diag('MemberProfileTable::getViewableProfileByMemberIdAndProfileName()');
$memberProfile = $table->getViewableProfileByMemberIdAndProfileName(1, 'op_preset_region');
$t->is($memberProfile->getValue(), 'Tokyo');
$memberProfile = $table->getViewableProfileByMemberIdAndProfileName(1, 'op_preset_region', 1);
$t->is($memberProfile->getValue(), 'Tokyo');
$memberProfile = $table->getViewableProfileByMemberIdAndProfileName(1, 'op_preset_region', 2);
$t->is($memberProfile->getValue(), 'Tokyo');
$memberProfile = $table->getViewableProfileByMemberIdAndProfileName(1, 'op_preset_region', 3);
$t->ok(!$memberProfile);
$memberProfile = $table->getViewableProfileByMemberIdAndProfileName(1, 'xxxxxxxxxxx', 1);
$t->ok(!$memberProfile);

//------------------------------------------------------------
$t->diag('MemberProfileTable::retrieveByMemberIdAndProfileId()');
$memberProfile = $table->retrieveByMemberIdAndProfileId(1, 1);
$t->is($memberProfile->getValue(), 'Man');

//------------------------------------------------------------
$t->diag('MemberProfileTable::searchMemberIds()');
$t->todo();

//------------------------------------------------------------
$t->diag('MemberProfileTable::createChild()');
$memberProfile = new MemberProfile();
$table->createChild($memberProfile, 2, 6, array(3, 4), array('A', 'B'));
