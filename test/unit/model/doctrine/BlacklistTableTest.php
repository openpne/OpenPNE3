<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(1, new lime_output_color());

$table = Doctrine::getTable('Blacklist');

//------------------------------------------------------------
$t->diag('BlacklistTable');
$t->diag('BlacklistTable::retrieveByUid()');
$result = $table->retrieveByUid('TEST');
$t->isa_ok($result, 'Blacklist');
