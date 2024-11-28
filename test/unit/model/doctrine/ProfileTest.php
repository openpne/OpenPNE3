<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(22, new lime_output_color());

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
$t->is($result, array('Female' => 'Female', 'Man' => 'Man'), 'getOptionsArray() returns array of genders');

$result = $profileSelfIntro->getOptionsArray();
$t->is($result, array(), 'getOptionsArray() returns empty array');

$result = $profileSelectItem->getOptionsArray();
$t->todo('getOptionsArray() returns array of the set of user');

//------------------------------------------------------------
$t->diag('Profile::getPresetOptionsArray()');
$result = $profileSex->getPresetOptionsArray();
$t->is($result, array('Female' => 'Female', 'Man' => 'Man'), 'getPresetOptionsArray() returns array of genders');

$result = $profileBirthday->getPresetOptionsArray();
$t->is($result, array(), 'getPresetOptionsArray() returns empty array');

$result = $profileSelectItem->getPresetOptionsArray();
$t->is($result, array(), 'getPresetOptionsArray() returns empty array');

//------------------------------------------------------------
$t->diag('Profile::isMultipleSelect()');
$t->ok(!$profileSex->isMultipleSelect(), 'isMultipleSelect() returns false');
$t->ok(!$profileBirthday->isMultipleSelect(), 'isMultipleSelect() returns false');
$t->ok(!$profileSelectItem->isMultipleSelect(), 'isMultipleSelect() returns ture');
$t->ok($profileCheckboxItem->isMultipleSelect(), 'isMultipleSelect() returns true');

//------------------------------------------------------------
$t->diag('Profile::isSingleSelect()');
$t->ok($profileSex->isSingleSelect(), 'isSingleSelect() returns true');
$t->ok(!$profileBirthday->isMultipleSelect(), 'isSingleSelect() returns false');
$t->ok($profileSelectItem->isSingleSelect(), 'isSingleSelect() returns true');
$t->ok(!$profileCheckboxItem->isSingleSelect(), 'isSingleSelect() returns false');

//------------------------------------------------------------
$t->diag('Profile::isPreset()');
$t->ok($profileSex->isPreset(), 'isPreset() returns true');
$t->ok(!$profileSelectItem->isPreset(), 'isPreset() returns false');
$t->ok(!$profileCheckboxItem->isPreset(), 'isPreset() returns false');

//------------------------------------------------------------
$t->diag('Profile::getRawPresetName()');
$t->is($profileSex->getRawPresetName(), 'sex', 'getRawPresetName() returns "sex"');
$t->is($profileRegion->getRawPresetName(), 'region_JP', 'getRawPresetName() returns "region_JP"');
$t->is($profileSelectItem->getRawPresetName(), false, 'getRawPresetName() returns false');

//------------------------------------------------------------
$t->diag('Profile::getPresetConfig()');
$t->isa_ok($profileSex->getPresetConfig(), 'array', 'getPresetConfig() returns array');
$t->is($profileSelectItem->getPresetConfig(), array(), 'getPresetConfig() returns empty array');
