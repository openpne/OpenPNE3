<?php

$_app = 'pc_frontend';
$_env = 'test';
$_taskConfig = array('--application='.$_app, '--env='.$_env);

$configuration = ProjectConfiguration::getApplicationConfiguration($_app, $_env, true);
new sfDatabaseManager($configuration);

$task = new sfDoctrineDropDbTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array_merge($_taskConfig, array('--no-confirmation')));

$task = new sfDoctrineBuildDbTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run($_taskConfig);

$task = new sfDoctrineInsertSqlTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run($_taskConfig);

$task = new sfDoctrineLoadDataTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array_merge($_taskConfig, array('--dir='.dirname(__FILE__).'/../fixtures')));
