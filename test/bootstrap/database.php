<?php

$_app = 'pc_frontend';
$_env = 'test';
$_task_config = array('--application='.$_app, '--env='.$_env);

$configuration = ProjectConfiguration::getApplicationConfiguration($_app, $_env, true);
new sfDatabaseManager($configuration);

$task = new sfDoctrineDropDbTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array_merge($_task_config, array('--no-confirmation')));

$task = new sfDoctrineBuildDbTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run($_task_config);

$task = new sfDoctrineInsertSqlTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run($_task_config);

$task = new sfDoctrineLoadDataTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array_merge($_task_config, array('--dir='.dirname(__FILE__).'/../fixtures')));
