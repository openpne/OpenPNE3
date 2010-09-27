<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(1, new lime_output_color());
$activityData1 = Doctrine::getTable('ActivityData')->find(1);

//------------------------------------------------------------
$t->diag('ActivityData');
$t->diag('ActivityData::getPublicFlagCaption()');
$t->is($activityData1->getPublicFlagCaption(), '全員に公開', '->getPublicFlagCaption() returns caption of public flag');
