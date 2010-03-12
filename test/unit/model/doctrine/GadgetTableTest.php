<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(11, new lime_output_color());

$table = Doctrine::getTable('Gadget');

//------------------------------------------------------------
$t->diag('GadgetTable');
$t->diag('GadgetTable::retrieveGadgetsByTypesName()');
$results = $table->retrieveGadgetsByTypesName('gadget');
$t->isa_ok($results, 'array');
$t->is(count($results), 4);

$results = $table->retrieveGadgetsByTypesName('profile');
$t->isa_ok($results, 'array');
$t->is(count($results), 4);

try
{
  $results = $table->retrieveGadgetsByTypesName('xxxxxxxxxx');
  $t->fail();
}
catch (Doctrine_Exception $e)
{
  $t->pass();
}

//------------------------------------------------------------
$t->diag('GadgetTable::retrieveByType()');
$results = $table->retrieveByType('top');
$t->isa_ok($results, 'array');
$t->is(count($results), 1);

//------------------------------------------------------------
$t->diag('GadgetTable::getGadgetsIds()');
$results = $table->getGadgetsIds('top');
$t->is($results, array(1));

//------------------------------------------------------------
$t->diag('GadgetTable::getGadgetConfigListByType()');
$results = $table->getGadgetConfigListByType('top');
$t->is(count($results), 21);
$results = $table->getGadgetConfigListByType('profileTop');
$t->is(count($results), 10);
$results = $table->getGadgetConfigListByType('xxxxxxxxxx');
$t->is($results, array());
