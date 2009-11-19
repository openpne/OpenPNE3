<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php'; 
$t = new lime_test(null, new lime_output_color());

$table = Doctrine::getTable('OpenIDTrustLog');

//------------------------------------------------------------
$t->diag('OpenIDTrustLog');
$t->diag('OpenIDTrustLogTable::findByOpenID()');
$result = $table->findByOpenID('example.com', 1);
$t->isa_ok($result, 'OpenIDTrustLog');

//------------------------------------------------------------
$t->diag('OpenIDTrustLogTable::log()');
$log = $table->log('example.com', 1);
$t->isa_ok($log, 'OpenIDTrustLog');
$log = $table->log('example.com', 2);
$t->isa_ok($log, 'OpenIDTrustLog');

//------------------------------------------------------------
$t->diag('OpenIDTrustLogTable::getListPager()');
$result = $table->getListPager(1);
$t->isa_ok($result, 'sfDoctrinePager');
$t->is($result->getNbResults(), 1);
