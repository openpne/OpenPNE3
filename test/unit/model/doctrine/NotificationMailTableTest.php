<?php

include_once dirname(__FILE__).'/../../../bootstrap/unit.php';
include_once dirname(__FILE__).'/../../../bootstrap/database.php';

$t = new lime_test(1, new lime_output_color());

//------------------------------------------------------------
$t->diag('NotificationMailTable');
$t->diag('NotificationMailTable::getDisabledNotificationNames()');
$result = Doctrine::getTable('NotificationMail')->getDisabledNotificationNames();
$t->is($result, array('name2'));

