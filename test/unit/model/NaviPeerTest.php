<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('NaviPeer::retrieveByType()');
$navis = NaviPeer::retrieveByType('test');
$t->isa_ok($navis, 'array', 'retrieveByType() returns an array.');
foreach ($navis as $navi)
{
  $t->isa_ok($navi, 'Navi', 'each navi is a Navi');
  $t->cmp_ok($navi->getType(), '===', 'test', 'Navi::getType() returns "test".');
}

//------------------------------------------------------------

$t->diag('NaviPeer::retrieveTypes()');
$types = NaviPeer::retrieveTypes();
$t->isa_ok($navis, 'array', 'retrieveTypes() returns an array.');
$t->cmp_ok(in_array('test', $types), '===', true, '$types contains "test".');
