<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(3, new lime_output_color());

$table = Doctrine::getTable('Banner');

//------------------------------------------------------------
$t->diag('BannerTable');
$t->diag('BannerTable::findByName()');
$result = $table->findByName('banner1');
$t->isa_ok($result, 'Banner');

$result = $table->findByName('xxxxxxxxxx');
$t->cmp_ok($result, '===', false);

//------------------------------------------------------------
$t->diag('BannerTable::retrieveTop()');
$result = $table->retrieveTop();
$t->isa_ok($result, 'Banner');
