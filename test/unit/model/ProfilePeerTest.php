<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByIsDispRegist()');
$profiles = ProfilePeer::retrieveByIsDispRegist();
$t->isa_ok($profiles, 'array', 'retrieveByIsDispRegist() returns an array');
foreach ($profiles as $profile)
{
  $t->isa_ok($profile, 'Profile', 'each profile is a Profile');
  $t->cmp_ok($profile->getIsDispRegist(), '===', true, 'Profile::getIsDispRegist() returns true');
}

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByIsDispConfig()');
$profiles = ProfilePeer::retrieveByIsDispConfig();
$t->isa_ok($profiles, 'array', 'retrieveByIsDispConfig() returns an array');
foreach ($profiles as $profile)
{
  $t->isa_ok($profile, 'Profile', 'each profile is a Profile');
  $t->cmp_ok($profile->getIsDispConfig(), '===', true, 'Profile::getIsDispConfig() returns true');
}

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByIsDispSearch()');
$profiles = ProfilePeer::retrieveByIsDispSearch();
$t->isa_ok($profiles, 'array', 'retrieveByIsDispSearch() returns an array');
foreach ($profiles as $profile)
{
  $t->isa_ok($profile, 'Profile', 'each profile is a Profile');
  $t->cmp_ok($profile->getIsDispSearch(), '===', true, 'Profile::getIsDispSearch() returns true');
}

//------------------------------------------------------------

$t->diag('ProfilePeer::retrieveByName()');
$profile = ProfilePeer::retrieveByName('self_intro');
$t->isa_ok($profile, 'Profile', 'retrieveByName() returns a Profile');
$t->cmp_ok($profile->getName(), '===', 'self_intro', 'Profile::getName() returns a name');

$t->cmp_ok(ProfilePeer::retrieveByName('unknown'), '===', NULL, 'retrieveByName() returns NULL if name does not exist');
