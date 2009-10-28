<?php

$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
new sfDatabaseManager($configuration);

$task = new sfDoctrineDropDbTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array('--no-confirmation'));

$task = new sfDoctrineBuildDbTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run();

$task = new sfDoctrineInsertSqlTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run();

$task = new sfDoctrineLoadDataTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array('--dir='.dirname(__FILE__).'/../fixtures'));
