<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(4, new lime_output_color());
$table = Doctrine::getTable('File');

//------------------------------------------------------------
$t->diag('FileTable');
$t->diag('FileTable::retrieveByFilename()');
$t->isa_ok($table->retrieveByFilename('dummy_file'), 'File');
$t->ok(!$table->retrieveByFilename('xxxxxxxxxx'));

//------------------------------------------------------------
$t->diag('FileTable::getFilePager()');
$t->isa_ok($table->getFilePager(), 'sfDoctrinePager');

//------------------------------------------------------------
$t->diag('FileTable::getImageFilePager()');
$t->isa_ok($table->getImageFilePager(), 'sfDoctrinePager');
