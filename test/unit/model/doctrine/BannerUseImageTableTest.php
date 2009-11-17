<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

$table = Doctrine::getTable('BannerUseImage');

//------------------------------------------------------------
$t->diag('BannerUseImageTable');
$t->diag('BannerUseImageTable::retrieveByBannerAndImageId()');
$result = $table->retrieveByBannerAndImageId(1, 1);
$t->isa_ok($result, 'BannerUseImage');
