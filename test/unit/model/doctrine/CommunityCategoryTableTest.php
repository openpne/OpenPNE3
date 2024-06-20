<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(14, new lime_output_color());

$table = Doctrine::getTable('CommunityCategory');

//------------------------------------------------------------
$t->diag('CommunityCategoryTable');
$t->diag('CommunityCategoryTable::retrieveAll()');
$t->isa_ok($table->retrieveAll(), 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('CommunityCategoryTable::retrieveAllRoots()');
$t->isa_ok($table->retrieveAllRoots(), 'Doctrine_Collection');
$categories = $table->retrieveAllRoots(false);
$t->is($categories[0]->getSortOrder(), 2);
$categories = $table->retrieveAllRoots(true);
$t->is($categories[0]->getSortOrder(), 1);

//------------------------------------------------------------
$t->diag('CommunityCategoryTable::getAllRootsQuery()');
$t->isa_ok($table->getAllRootsQuery(), 'opDoctrineQuery');
$t->is($table->getAllRootsQuery(false)->getDql(), ' FROM CommunityCategory WHERE lft = 1');
$t->is($table->getAllRootsQuery(true)->getDql(), ' FROM CommunityCategory WHERE lft = 1 ORDER BY sort_order');

//------------------------------------------------------------
$t->diag('CommunityCategoryTable::retrieveAllChildren()');
$t->isa_ok($table->retrieveAllChildren(), 'Doctrine_Collection');
$categories = $table->retrieveAllChildren(false);
$t->is($categories[0]->getSortOrder(), 2);
$categories = $table->retrieveAllChildren(true);
$t->is($categories[0]->getSortOrder(), 1);

//------------------------------------------------------------
$t->diag('CommunityCategoryTable::getAllChildrenQuery()');
$t->isa_ok($table->getAllChildrenQuery(), 'opDoctrineQuery');
$t->is($table->getAllChildrenQuery(false)->getDql(), ' FROM CommunityCategory WHERE lft > 1');
$t->is($table->getAllChildrenQuery(true)->getDql(), ' FROM CommunityCategory WHERE lft > 1 ORDER BY sort_order');

//------------------------------------------------------------
$t->diag('CommunityCategoryTable::getAllChildren()');
$t->todo();
