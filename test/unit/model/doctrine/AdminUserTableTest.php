<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(3, new lime_output_color());

$table = Doctrine::getTable('AdminUser');

//------------------------------------------------------------
$t->diag('AdminUserTable');
$t->diag('AdminUserTable::retrieveByUsername()');
$result = $table->retrieveByUsername('admin');
$t->isa_ok($result, 'AdminUser');
$t->ok(!$table->retrieveByUsername('xxxxxxxxxx'));

//------------------------------------------------------------
$t->diag('AdminUserTable::retrievesAll()');
$result = $table->retrievesAll();
$t->isa_ok($result, 'Doctrine_Collection');
