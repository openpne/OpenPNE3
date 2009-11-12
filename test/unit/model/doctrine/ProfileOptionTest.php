<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(2, new lime_output_color());

//------------------------------------------------------------
$t->diag('opProfileOptionEmulator');
$option = new opProfileOptionEmulator();
$option->id = 1;
$option->value = 'test';
$t->is($option->id, 1);
$t->is($option->value, 'test');
