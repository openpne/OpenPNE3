<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(5, new lime_output_color());

$table = Doctrine::getTable('ProfileOption');

//------------------------------------------------------------
$t->diag('ProfileOptionTable');
$t->diag('ProfileOptionTable::retrieveByProfileId()');
$result = $table->retrieveByProfileId(1);
$t->isa_ok($result, 'array');
$result = $table->retrieveByProfileId(5);
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('ProfileOptionTable::getMaxSortOrder()');
$t->todo();

//------------------------------------------------------------
$t->diag('ProfileOptionTable::generatePresetProfileOption()');
$result = $table->generatePresetProfileOption(1);
$t->isa_ok($result, 'array');
$result = $table->generatePresetProfileOption(5);
$t->is($result, array());
