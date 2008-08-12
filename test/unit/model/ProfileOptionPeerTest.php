<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('ProfileOptionPeer::retrieveByIsProfileId()');
$options = ProfileOptionPeer::retrieveByIsProfileId(1);
$t->isa_ok($options, 'array', 'retrieveByIsProfileId() returns an array');
foreach ($options as $option)
{
  $t->isa_ok($option, 'ProfileOption', 'each element is a ProfileOption');
}

$t->cmp_ok(ProfileOptionPeer::retrieveByIsProfileId(2), '===', array(), 'retrieveByIsProfileId() returns an empty array if no options exist');
$t->cmp_ok(ProfileOptionPeer::retrieveByIsProfileId(999), '===', array(), 'retrieveByIsProfileId() returns an empty array if profile_id is invalid');
