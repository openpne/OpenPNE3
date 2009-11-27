<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

$_test_dir = realpath(dirname(__FILE__).'/..');

// chdir to the symfony(OpenPNE) project directory
chdir(dirname(__FILE__).'/../../../..');

require_once 'config/ProjectConfiguration.class.php';
$configuration = new ProjectConfiguration(realpath($_test_dir.'/../../../'));
include($configuration->getSymfonyLibDir().'/vendor/lime/lime.php');
