<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);

new sfDatabaseManager($configuration);

$task = new sfDoctrineBuildTask($configuration->getEventDispatcher(), new sfFormatter());
$task->setConfiguration($configuration);
$task->run(array(), array(
  'no-confirmation' => true,
  'db'              => true,
  'and-load'        => dirname(__FILE__).'/../fixtures',
));
