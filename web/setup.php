<?php

require_once(dirname(__FILE__).'/../lib/vendor/symfony/lib/task/sfFilesystem.class.php');
$fileSystem = new sfFileSystem();
$root = dirname(__FILE_).'/../';
$fileSystem->copy($root.'/config/ProjectConfiguration.class.php.sample', $root.'/config/ProjectConfiguration.class.php');

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('setup', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
