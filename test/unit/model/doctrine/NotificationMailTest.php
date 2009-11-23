<?php

include_once dirname(__FILE__).'/../../../bootstrap/unit.php';
include_once dirname(__FILE__).'/../../../bootstrap/database.php';

$t = new lime_test(1, new lime_output_color());

$object = Doctrine::getTable('NotificationMail')->find(1);

//------------------------------------------------------------
$t->diag('NotificationMail');
$t->diag('NotificationMail::__toString()');
sfDoctrineRecord::setDefaultCulture('ja_JP');
$t->is((string)$object, 'template1');
