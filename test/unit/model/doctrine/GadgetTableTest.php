<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(21, new lime_output_color());

$table = Doctrine::getTable('Gadget');
$table->clearGadgetsCache();
sfConfig::set('op_is_enable_gadget_cache', false);
//------------------------------------------------------------
$t->diag('GadgetTable');
$t->diag('GadgetTable::retrieveGadgetsByTypesName()');
$results = $table->retrieveGadgetsByTypesName('gadget');
$t->isa_ok($results, 'array', '->retrieveGadgetsByTypesName() returns array of gadgets');
$t->is(count($results), 4, '->retrieveGadgetsByTypesName() returns 4 gadgets');

$results = $table->retrieveGadgetsByTypesName('gadget');
$t->isa_ok($results, 'array', '->retrieveGadgetsByTypesName() returns array of gadgets');
$t->is(count($results), 4, '->retrieveGadgetsByTypesName() returns 4 gadgets');

$results = $table->retrieveGadgetsByTypesName('profile');
$t->isa_ok($results, 'array', '->retrieveGadgetsByTypesName() returns array of gadgets');
$t->is(count($results), 4, '->retrieveGadgetsByTypesName() return 4 gadgets');

$message = '->retrieveGadgetsByTypesName() throw Exception';
try
{
  $results = $table->retrieveGadgetsByTypesName('xxxxxxxxxx');
  $t->fail($message);
}
catch (Doctrine_Exception $e)
{
  $t->pass($message);
}

//------------------------------------------------------------
$t->diag('GadgetTable::retrieveByType()');
$results = $table->retrieveByType('top');
$t->isa_ok($results, 'array', '->retrieveByType() returns of types');
$t->is(count($results), 1, '->retrieveByType() returns 1 type');

//------------------------------------------------------------
$t->diag('GadgetTable::getGadgetsIds()');
$results = $table->getGadgetsIds('top');
$t->is($results, array(1), '->getGadgetsIds() returns array(1)');

//------------------------------------------------------------
$t->diag('GadgetTable::getGadgetConfigListByType()');
$results = $table->getGadgetConfigListByType('top');
$t->isa_ok($results, 'array', '->getGadgetConfigListByType() returns array');
$results = $table->getGadgetConfigListByType('top');
$t->isa_ok($results, 'array', '->getGadgetConfigListByType() returns array');
$results = $table->getGadgetConfigListByType('profileTop');
$t->isa_ok($results, 'array', '->getGadgetConfigListByType() returns array');
$results = $table->getGadgetConfigListByType('xxxxxxxxxx');
$t->is($results, array(), '->getGadgetConfigListByType() returns array of empty');

//------------------------------------------------------------
$t->diag('Cache Test');
$table->clearGadgetsCache();
sfConfig::set('op_is_enable_gadget_cache', true);
$file = sfConfig::get('sf_app_cache_dir').'/config/gadget_gadgets.php';
$t->ok(!is_readable($file), 'The cache is not exists');
$results = $table->retrieveGadgetsByTypesName('gadget');
$t->ok(is_readable($file), 'The cache file was created by ->retrieveGadgetsByTypesName()');

$table->clearGadgetsCache();
$t->ok(!is_readable($file), 'The cache file was deleted by ->clearGadgetsCache()');

//------------------------------------------------------------
$t->diag('ACL Test');
$gadget1 = $table->findOneByName('searchBox');
$gadget2 = $table->findOneByName('languageSelecterBox');
$member1 = Doctrine::getTable('Member')->find(1);
$anonymousMember = new opAnonymousMember();
$t->cmp_ok($gadget1->isAllowed($member1, 'view'), '===', true, 'search box is allowed member 1');
$t->cmp_ok($gadget1->isAllowed($anonymousMember, 'view'), '===', false,'search box is not allowed anonymous member');
$t->cmp_ok($gadget2->isAllowed($member1, 'view'), '===', true, 'languageSelecterBox is allowed member 1');
$t->cmp_ok($gadget2->isAllowed($anonymousMember, 'view'), '===', true, 'languageSelecterBox is allowed anonymous member');
