<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('MemberProfile::__toString()');
$memberProfile = MemberProfilePeer::retrieveByPK(1);
$option = ProfileOptionPeer::retrieveByPk($memberProfile->getProfileOptionId());
$t->cmp_ok((string)$memberProfile, '===', $option->getValue(), '__toString() returns an option');
$memberProfile = MemberProfilePeer::retrieveByPK(2);
$t->cmp_ok((string)$memberProfile, '===', $memberProfile->getValue(), '__toString() returns a value');

//------------------------------------------------------------

$t->diag('MemberProfile::getValue()');
$memberProfile = MemberProfilePeer::retrieveByPK(1);
$t->cmp_ok($memberProfile->getValue(), '===', 1, 'getValue() returns an option');
$memberProfile = MemberProfilePeer::retrieveByPK(2);
$t->cmp_ok($memberProfile->getValue(), '===', 'よろしくお願いします。', 'getValue() returns a value');

//------------------------------------------------------------

$t->diag('Member::hydrateProfiles()');

//------------------------------------------------------------

$t->diag('Member::getName()');

//------------------------------------------------------------

$t->diag('Member::getCaption()');
