<?php

include_once dirname(__FILE__).'/../../../bootstrap/unit.php';
include_once dirname(__FILE__).'/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
sfDoctrineRecord::setDefaultCulture('ja_JP');

$t = new lime_test(5, new lime_output_color());

//------------------------------------------------------------
$t->diag('NotificationMailTable');
$t->diag('NotificationMailTable::getDisabledNotificationNames()');
$result = Doctrine::getTable('NotificationMail')->getDisabledNotificationNames();
$t->is($result, array('name2'));

$t->diag('NotificationMailTable::fetchTemplate()');
$result = Doctrine::getTable('NotificationMail')->fetchTemplate('name1');
$t->isa_ok($result, 'NotificationMail');

$result = Doctrine::getTable('NotificationMail')->fetchTemplate('pc_changeMailAddress');
$t->isa_ok($result, 'NotificationMail');

$result = Doctrine::getTable('NotificationMail')->fetchTemplate('aaaaa');
$t->cmp_ok(false, '===', $result);

$t->diag('NotificationMailTable::getConfigs()');
$result = Doctrine::getTable('NotificationMail')->getConfigs();
$t->isa_ok($result, 'array');
