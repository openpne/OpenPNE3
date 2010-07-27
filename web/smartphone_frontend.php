<?php


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('smartphone_frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
