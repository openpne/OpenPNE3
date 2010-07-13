<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);
opToolkit::clearCache();
include_once dirname(__FILE__) . '/fixtures/TestActivityTemplateConfigHandler.php';

$t = new lime_test(4, new lime_output_color());
$activityData1 = Doctrine::getTable('ActivityData')->find(1);
$activityData7 = Doctrine::getTable('ActivityData')->find(7);
$activityData8 = Doctrine::getTable('ActivityData')->find(8);

//------------------------------------------------------------
$t->diag('ActivityData');
$t->diag('ActivityData::getPublicFlagCaption()');
$t->is($activityData1->getPublicFlagCaption(), '全員に公開', '->getPublicFlagCaption() returns caption of public flag');

$t->diag('ActivityData::getBody()');
$t->is($activityData1->getBody(), 'dummy1', '->getBody() returns "dummy1"');
$t->is($activityData7->getBody(), 'Test test A test, bar!!!', '->getBody() returns "Test test A test, bar!!!"');
$t->cmp_ok($activityData8->getBody(), '===', '', '->getBody() returns ""');
