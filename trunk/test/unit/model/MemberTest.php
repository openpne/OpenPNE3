<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('Member::getProfiles()');
$member = MemberPeer::retrieveByPK(1);
$t->isa_ok($member->getProfiles(), 'array', 'getProfiles() returns array');

//------------------------------------------------------------

$t->diag('Member::getProfile()');
$t->cmp_ok($member->getProfile('sex'), '===', 1, 'getProfile() returns a value');
$t->cmp_ok($member->getProfile('self_intro'), '===', 'よろしくお願いします。', 'getProfile() returns a value');
$t->cmp_ok($member->getProfile('example'), '===', NULL, 'getProfile() returns NULL if profileName is not registerd by member');
$t->cmp_ok($member->getProfile('unknown'), '===', NULL, 'getProfile() returns NULL if profileName does not exist');
