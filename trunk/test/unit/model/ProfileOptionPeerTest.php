<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('ProfileOptionPeer::retrieveByProfileId()');
$options = ProfileOptionPeer::retrieveByProfileId(1);
$t->isa_ok($options, 'array', 'retrieveByProfileId() returns an array');
foreach ($options as $option)
{
  $t->isa_ok($option, 'ProfileOption', 'each element is a ProfileOption');
}

$t->cmp_ok(ProfileOptionPeer::retrieveByProfileId(2), '===', array(), 'retrieveByProfileId() returns an empty array if no options exist');
$t->cmp_ok(ProfileOptionPeer::retrieveByProfileId(999), '===', array(), 'retrieveByProfileId() returns an empty array if profile_id is invalid');
