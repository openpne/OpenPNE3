<?php

new sfDatabaseManager(ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true));

// execute propel:insert-sql task
$task = new sfPropelInsertSqlTask(new sfEventDispatcher(), new sfAnsiColorFormatter());
$task->run(array(), array('no-confirmation'));

$loader = new sfPropelData();
$loader->loadData(dirname(__FILE__).'/../fixtures');
