<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';

$t = new lime_test(4);

//------------------------------------------------------------
$t->diag('BannerImage: Cascading Delete');
$conn->beginTransaction();

$bannerImage = Doctrine_Core::getTable('BannerImage')->find(1);
$bannerUseImage = $bannerImage->BannerUseImage[0];
$file = $bannerImage->File;

$bannerImage->delete($conn);

$t->ok(!Doctrine_Core::getTable('BannerImage')->find($bannerImage->id), 'banner_image is deleted.');
$t->ok(!Doctrine_Core::getTable('BannerUseImage')->find($bannerUseImage->id), 'banner_use_image is deleted.');
$t->ok(!Doctrine_Core::getTable('File')->find($file->id), 'file is deleted.');
$t->ok(!Doctrine_Core::getTable('FileBin')->find($file->id), 'file_bin is deleted.');

$conn->rollback();
