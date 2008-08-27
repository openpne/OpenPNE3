<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('MemberProfilePeer::getProfileListByMemberId()');
$MemberProfileList = MemberProfilePeer::getProfileListByMemberId(1);
$t->isa_ok($MemberProfileList, 'array', 'getProfileListByMemberId() returns an array');
foreach ($MemberProfileList as $profile) {
  $t->isa_ok($profile, 'MemberProfile', 'each profile is a MemberProfile');
  $t->cmp_ok($profile->getName(), '==', true, 'Profile::getName() returns value.');
}
$t->cmp_ok(MemberProfilePeer::getProfileListByMemberId(2), '===', array(), 'getProfileListByMemberId() returns an empty array if member does not have any profile');
$t->cmp_ok(MemberProfilePeer::getProfileListByMemberId(999), '===', array(), 'getProfileListByMemberId() returns an empty array if member is not exist');

//------------------------------------------------------------

$t->diag('MemberProfilePeer::retrieveByMemberIdAndProfileId()');
$memberProfile = MemberProfilePeer::retrieveByMemberIdAndProfileId(1, 2);
$t->isa_ok($memberProfile, 'MemberProfile', 'retrieveByMemberIdAndProfileId() returns a MemberProfile');
$t->is($memberProfile->getValue(), 'よろしくお願いします。', 'MemberProfile::getValue() returns a value');
$t->cmp_ok(MemberProfilePeer::retrieveByMemberIdAndProfileId(1, 999), '===', NULL, 'retrieveByMemberIdAndProfileId() returns NULL if profile does not exist');
$t->cmp_ok(MemberProfilePeer::retrieveByMemberIdAndProfileId(999, 1), '===', NULL, 'retrieveByNameAndMemberId() returns NULL if member does not exist');

//------------------------------------------------------------

$t->diag('MemberProfilePeer::retrieveByMemberIdAndProfileName()');
$memberProfile = MemberProfilePeer::retrieveByMemberIdAndProfileName(1, 'self_intro');
$t->isa_ok($memberProfile, 'MemberProfile', 'retrieveByMemberIdAndProfileName() returns a MemberProfile');
$t->is($memberProfile->getValue(), 'よろしくお願いします。', 'MemberProfile::getValue() returns a value');
$t->cmp_ok(MemberProfilePeer::retrieveByMemberIdAndProfileName(1, 'unknown'), '===', NULL, 'retrieveByMemberIdAndProfileName() returns NULL if profile does not exist');
$t->cmp_ok(MemberProfilePeer::retrieveByMemberIdAndProfileName(999, 'example'), '===', NULL, 'retrieveByNameAndMemberId() returns NULL if member does not exist');
