<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(4, new lime_output_color());

$table = Doctrine::getTable('Navigation');

//------------------------------------------------------------
$t->diag('NavigationTable');
$t->diag('NavigationTable::retrieveByType()');
$result = $table->retrieveByType('default');
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('NavigationTable::getTypesByAppName()');
$t->is($table->getTypesByAppName('pc'), array(
  'insecure_global',
  'secure_global',
  'default',
  'friend',
  'community',
));
$t->is($table->getTypesByAppName('mobile'), array(
  'mobile_global',
  'mobile_home',
  'mobile_home_center',
  'mobile_home_side',
  'mobile_friend',
  'mobile_community'
));
$t->is($table->getTypesByAppName('backend'), array(
  'backend_side'
));
