<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(2, new lime_output_color());

$table = Doctrine::getTable('GadgetConfig');

//------------------------------------------------------------
$t->diag('GadgetConfigTable');
$t->diag('GadgetConfigTable::retrieveByGadgetIdAndName()');
$t->is($table->retrieveByGadgetIdAndName(3, 'row')->getValue(), 1);
$t->ok(!$table->retrieveByGadgetIdAndName(3, 'col'));

