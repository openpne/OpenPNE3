<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

//------------------------------------------------------------

$t->diag('AdminUserPeer::retrieveByUsername()');

$t->isa_ok(AdminUserPeer::retrieveByUsername('admin'), 'AdminUser',
  'retrieveByUsername() returns a AdminUser');
$t->cmp_ok(AdminUserPeer::retrieveByUsername('unknown'), '===', NULL,
  'retrieveByUsername() returns NULL if username is invalid');
