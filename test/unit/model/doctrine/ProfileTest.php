<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(20, new lime_output_color());

$table = Doctrine::getTable('Profile');

$profileSex          = $table->findOneByName('op_preset_sex');
$profileBirthday     = $table->findOneByName('op_preset_birthday');
$profileRegion       = $table->findOneByName('op_preset_region');
$profileSelfIntro    = $table->findOneByName('op_preset_self_introduction');
$profileSelectItem   = $table->findOneByName('select_item');
$profileCheckboxItem = $table->findOneByName('checkbox_item');

//------------------------------------------------------------
$t->diag('Profile');
$t->diag('Profile::getOptionsArray()');
$result = $profileSex->getOptionsArray();
$t->is($result, array('Female' => 'Female', 'Man' => 'Man'));

$result = $profileBirthday->getOptionsArray();
$t->is($result, array());

$result = $profileSelectItem->getOptionsArray();
$t->is($result, array(1 => 'あ', 2 => 'い'));

//------------------------------------------------------------
$t->diag('Profile::getPresetOptionsArray()');
$result = $profileSex->getPresetOptionsArray();
$t->is($result, array('Female' => 'Female', 'Man' => 'Man'));

$result = $profileBirthday->getPresetOptionsArray();
$t->is($result, array());

$result = $profileSelectItem->getPresetOptionsArray();
$t->is($result, array());

//------------------------------------------------------------
$t->diag('Profile::isMultipleSelect()');
$t->ok(!$profileSex->isMultipleSelect());
$t->ok(!$profileSelectItem->isMultipleSelect());
$t->ok($profileCheckboxItem->isMultipleSelect());

//------------------------------------------------------------
$t->diag('Profile::isSingleSelect()');
$t->ok($profileSex->isSingleSelect());
$t->ok($profileSelectItem->isSingleSelect());
$t->ok(!$profileCheckboxItem->isSingleSelect());

//------------------------------------------------------------
$t->diag('Profile::isPreset()');
$t->ok($profileSex->isPreset());
$t->ok(!$profileSelectItem->isPreset());
$t->ok(!$profileCheckboxItem->isPreset());

//------------------------------------------------------------
$t->diag('Profile::getRawPresetName()');
$t->is($profileSex->getRawPresetName(), 'sex');
$t->is($profileRegion->getRawPresetName(), 'region_JP');
$t->is($profileSelectItem->getRawPresetName(), false);

//------------------------------------------------------------
$t->diag('Profile::getPresetConfig()');
$t->isa_ok($profileSex->getPresetConfig(), 'array');
$t->is($profileSelectItem->getPresetConfig(), array());
