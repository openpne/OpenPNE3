<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(1, new lime_output_color());

//------------------------------------------------------------
$t->diag('AdminUser');
$t->diag('AdminUser::preSave()');
$adminUser = new AdminUser();
$adminUser->setUserName('foo');
$password = 'bar';
$adminUser->setPassword($password);
$adminUser->save();

$t->is($adminUser->getPassword(), md5($password));
