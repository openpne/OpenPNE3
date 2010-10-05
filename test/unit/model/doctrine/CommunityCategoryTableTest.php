<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(3, new lime_output_color());

$table = Doctrine::getTable('CommunityCategory');

//------------------------------------------------------------
$t->diag('CommunityCategoryTable');
$t->diag('CommunityCategoryTable::retrieveAll()');
$t->isa_ok($table->retrieveAll(), 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('CommunityCategoryTable::retrieveAllRoots()');
$t->isa_ok($table->retrieveAllRoots(), 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('CommunityCategoryTable::retrieveAllChildren()');
$t->isa_ok($table->retrieveAllChildren(), 'Doctrine_Collection');
