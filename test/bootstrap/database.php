<?php

$app_configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
$databaseManager = new sfDatabaseManager($app_configuration);
$databaseManager->loadConfiguration();

// execute propel:insert-sql task
$task = new sfPropelInsertSqlTask(new sfEventDispatcher(), new sfAnsiColorFormatter());
$task->runFromCLI(new sfCommandManager(), '');

$data = new sfPropelData();
$data->loadData(dirname(__FILE__) . '/../fixtures');
