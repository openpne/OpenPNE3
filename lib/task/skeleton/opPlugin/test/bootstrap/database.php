<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

// guess current application
if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', true);
new sfDatabaseManager($configuration);

$task = new sfDoctrineBuildAllReloadTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array('--no-confirmation', '--dir='.sfConfig::get('sf_root_dir').'/data/fixtures', '--skip-forms'));

$loadData = new sfDoctrineLoadDataTask($configuration->getEventDispatcher(), new sfFormatter());
$loadData->run(array('--dir='.dirname(__FILE__).'/../fixtures'));
