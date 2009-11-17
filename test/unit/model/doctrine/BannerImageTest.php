<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(null, new lime_output_color());

$bannerImage = Doctrine::getTable('BannerImage')->find(1);

//------------------------------------------------------------
$t->diag('BannerImage');
$t->diag('BannerImage::delete()');
$t->is(Doctrine::getTable('BannerUseImage')->findAll()->count(), 1);
$bannerImage->delete();
$t->is(Doctrine::getTable('BannerUseImage')->findAll()->count(), 0);
