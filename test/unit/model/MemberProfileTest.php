<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('MemberProfile::__toString()');
$memberProfile = Doctrine::getTable('MemberProfile')->find(1);
$profileOption = Doctrine::getTable('ProfileOption')->find($memberProfile->getProfileOptionId());
$t->cmp_ok((string)$memberProfile, '===', (string)$profileOption->getValue(), '__toString() returns an option');
$memberProfile = Doctrine::getTable('MemberProfile')->find(2);
$t->cmp_ok((string)$memberProfile, '===', (string)$memberProfile->getValue(), '__toString() returns a value');

//------------------------------------------------------------

$t->diag('MemberProfile::getValue()');
$memberProfile = Doctrine::getTable('MemberProfile')->find(1);
$t->cmp_ok($memberProfile->getValue(), '===', '1', 'getValue() returns an option');
$memberProfile = Doctrine::getTable('MemberProfile')->find(2);
$t->cmp_ok($memberProfile->getValue(), '===', '1988-04-23', 'getValue() returns a value');

//------------------------------------------------------------

$t->diag('MemberProfileTable::getProfileListByMemberId()');
$memberProfile = Doctrine::getTable('MemberProfile')->getProfileListByMemberId(1);
$t->is(count($memberProfile), 3, 'getProfileListByMemberId() returns 3 profiles for member ID:1');

//------------------------------------------------------------

$t->diag('MemberProfileTable::retrieveByMemberIdAndProfileName()');
$memberProfile = Doctrine::getTable('MemberProfile')->retrieveByMemberIdAndProfileName(1, 'birthday');
$t->is($memberProfile->getValue(), '1988-04-23', 'retrieveByMemberIdAndProfileName() returns profile of member(ID:1)\'s birthday');

//------------------------------------------------------------

$t->diag('Member::hydrateProfiles()');

//------------------------------------------------------------

$t->diag('Member::getName()');

//------------------------------------------------------------

$t->diag('Member::getCaption()');
