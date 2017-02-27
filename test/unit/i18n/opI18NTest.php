<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$app = 'api';
sfContext::createInstance(ProjectConfiguration::getApplicationConfiguration($app, 'test', true));

$i18N = sfContext::getInstance()->getI18N();

$t = new lime_test(null, new lime_output_color());

sfContext::getInstance()->getUser()->setCulture('en');

//------------------------------------------------------------
$t->diag('opI18N test');
$t->is($i18N->__('%friend%'), 'friend', 'friend normal.');
$t->is($i18N->__('%Friend%'), 'Friend', 'friend titleize.');
$t->is($i18N->__('%my_friend%'), 'my friend', 'my friend normal.');
$t->is($i18N->__('%My_friend%'), 'My friend', 'my friend titleize.');
$t->is($i18N->__('%community%'), 'community', 'community normal.');
$t->is($i18N->__('%Community%'), 'Community', 'community titleize.');
$t->is($i18N->__('%nickname%'), 'nickname', 'nickname normal.');
$t->is($i18N->__('%Nickname%'), 'Nickname', 'nickname titleize.');
