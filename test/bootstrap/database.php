<?php

$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
new sfDatabaseManager($configuration);

$task = new sfDoctrineBuildAllReloadTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array('--no-confirmation', '--dir='.dirname(__FILE__).'/../fixtures', '--skip-forms'));
