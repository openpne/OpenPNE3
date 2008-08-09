<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$app_configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
$databaseManager = new sfDatabaseManager($app_configuration);
$databaseManager->loadConfiguration();

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('AdminUserPeer::retrieveByUsername()');

$t->isa_ok(AdminUserPeer::retrieveByUsername('admin'), 'AdminUser',
  'retrieveByUsername() returns a AdminUser');
$t->cmp_ok(AdminUserPeer::retrieveByUsername('unknown'), '===', NULL,
  'retrieveByUsername() returns NULL if username is invalid');
