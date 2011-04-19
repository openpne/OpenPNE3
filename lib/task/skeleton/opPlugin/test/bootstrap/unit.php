<?php

$_test_dir = realpath(dirname(__FILE__).'/..');

// chdir to the symfony(OpenPNE) project directory
chdir(dirname(__FILE__).'/../../../..');

require_once 'config/ProjectConfiguration.class.php';
$configuration = new ProjectConfiguration(realpath($_test_dir.'/../../../'));
include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');
