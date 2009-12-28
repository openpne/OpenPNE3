<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(4, new lime_output_color());

$activityData = Doctrine::getTable('ActivityData')->find(5);
$t->ok($activityData);

$memberRelationShip = Doctrine::getTable('MemberRelationship')->find(1);
$memberRelationShip->delete();
$activityData = Doctrine::getTable('ActivityData')->find(5);
$t->ok(!$activityData);

$activityData = Doctrine::getTable('ActivityData')->find(6);
$t->ok($activityData);

Doctrine::getTable('MemberRelationship')->createQuery()
  ->delete()
  ->where('id = ?', 2)
  ->execute();

$activityData = Doctrine::getTable('ActivityData')->find(6);
$t->ok(!$activityData);
