<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(2, new lime_output_color());

$banner1 = Doctrine::getTable('Banner')->findOneByName('banner1');
$banner2 = Doctrine::getTable('Banner')->findOneByName('banner2');

//------------------------------------------------------------
$t->diag('Banner');
$t->diag('Banner::getRandomImage()');
$result = $banner1->getRandomImage();
$t->isa_ok($result, 'BannerImage');

$result = $banner2->getRandomImage();
$t->cmp_ok($result, '===', false);
