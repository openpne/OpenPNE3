<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('NavigationPeer::retrieveByType()');
$navs = NavigationPeer::retrieveByType('secure_global');
$t->isa_ok($navs, 'array', 'retrieveByType() returns an array.');
foreach ($navs as $nav)
{
  $t->isa_ok($nav, 'Navigation', 'each nav is a Navigation');
  $t->cmp_ok($nav->getType(), '===', 'secure_global', 'Navigation::getType() returns "secure_global".');
}

//------------------------------------------------------------

$t->diag('NavigationPeer::retrieveTypes()');
$types = NavigationPeer::retrieveTypes();
$t->isa_ok($navs, 'array', 'retrieveTypes() returns an array.');
$t->cmp_ok(in_array('community', $types), '===', true, '$types contains "community".');
