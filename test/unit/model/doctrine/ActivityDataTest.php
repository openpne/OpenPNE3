<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$conn = Doctrine_Core::getTable('ActivityData')->getConnection();

$t = new lime_test(5);
$activityData1 = Doctrine::getTable('ActivityData')->find(1);
$activityImage = $activityData1->Images[0];

//------------------------------------------------------------
$t->diag('ActivityData');
$t->diag('ActivityData::getPublicFlagCaption()');
$t->is($activityData1->getPublicFlagCaption(), '全員に公開', '->getPublicFlagCaption() returns caption of public flag');

//------------------------------------------------------------
$t->diag('ActivityData: Cascading Delete');
$conn->beginTransaction();

$activityDataId = $activityData1->id;
$activityImageId = $activityImage->id;
$fileId = $activityImage->file_id;

$activityData1->delete($conn);

$t->ok(!Doctrine_Core::getTable('ActivityData')->find($activityDataId), 'activity_data is deleted.');
$t->ok(!Doctrine_Core::getTable('ActivityImage')->find($activityImageId), 'activity_image is deleted.');
$t->ok(!Doctrine_Core::getTable('File')->find($fileId), 'file is deleted.');
$t->ok(!Doctrine_Core::getTable('FileBin')->find($fileId), 'file_bin is deleted.');

$conn->rollback();
