<?php

$_test_dir = realpath(dirname(__FILE__).'/..');

require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');
$configuration = new ProjectConfiguration(realpath($_test_dir.'/..'));
include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');

$app_configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
sfContext::createInstance($app_configuration);
