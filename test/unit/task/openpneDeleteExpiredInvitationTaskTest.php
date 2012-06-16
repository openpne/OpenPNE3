<?php
include_once dirname(__FILE__).'/../../bootstrap/unit.php';
$t = new lime_test(null, new lime_output_color());

require_once dirname(__FILE__).'/../../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
$context = sfContext::createInstance($configuration);
new sfDatabaseManager($configuration);

$dbtask = new sfDoctrineBuildTask($configuration->getEventDispatcher(), new sfFormatter());
$dbtask->setConfiguration($configuration);
$dbtask->run(array(), array(
  'no-confirmation' => true,
  'db'              => true,
  'and-load'        => dirname(__FILE__).'/../../fixtures/expire_invitation',
  'application'     => 'pc_frontend',
  'env'             => 'test',
));

opActivateBehavior::disable();
$table = Doctrine::getTable('Member');

$q = $table->createQuery();
$t->is($q->execute()->count(), 2, 'A member and a pre-member exists.');

$task = new openpneDeleteExpiredInvitationTask($context->getEventDispatcher(), new sfFormatter());
$task->run();

$q = $table->createQuery();
$t->is($q->execute()->count(), 1, 'After executing task, no pre-member left.');