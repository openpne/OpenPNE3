<?php

if (!isset($configuration) || !($configuration instanceof sfApplicationConfiguration))
{
  if (!isset($app))
  {
    $app = 'pc_frontend';
  }

  $configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', true);
}

if (!isset($fixture))
{
  $fixture = 'common';
}

new sfDatabaseManager($configuration);

$conn = opDoctrineQuery::getMasterConnectionDirect();
$conn->exec('SET FOREIGN_KEY_CHECKS = 0');

$task = new sfDoctrineBuildTask($configuration->getEventDispatcher(), new sfFormatter());
$task->setConfiguration($configuration);
$task->run(array(), array(
  'no-confirmation' => true,
  'db'              => true,
  'and-load'        => dirname(__FILE__).'/../fixtures/'.$fixture,
  'application'     => $configuration->getApplication(),
  'env'             => $configuration->getEnvironment(),
));

$conn = Doctrine_Manager::getInstance()->getCurrentConnection();
$conn->clear();
