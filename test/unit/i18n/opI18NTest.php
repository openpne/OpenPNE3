<?php

include_once dirname(__FILE__).'/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$app = 'api';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', true);
sfContext::createInstance($configuration);

$t = new lime_test(null, new lime_output_color());

$i18N = new opI18N($configuration);
sfContext::getInstance()->getUser()->setCulture('en');

//------------------------------------------------------------
$t->diag('opI18N test (en)');
$t->is($i18N->__('%friend%'), 'friend', 'friend normal.');
$t->is($i18N->__('%Friend%'), 'Friend', 'friend fronting.');
$t->is($i18N->__('%my_friend%'), 'my friend', 'my friend normal.');
$t->is($i18N->__('%My_friend%'), 'My friend', 'my friend fronting.');
$t->is($i18N->__('%community%'), 'community', 'community normal.');
$t->is($i18N->__('%Community%'), 'Community', 'community fronting.');
$t->is($i18N->__('%nickname%'), 'nickname', 'nickname normal.');
$t->is($i18N->__('%Nickname%'), 'Nickname', 'nickname fronting.');
$t->is($i18N->__('%my_friend%', null), 'my friend', '(#1759) passing null to parameters');
$term = Doctrine_Core::getTable('SnsTerm')->get('my_friend');
$t->is($i18N->__('%my_friend%', array('%my_friend%' => $term->titleize())), 'My Friend', '(#4168) passing SnsTerm instance to parameters');

$i18N = new opI18N($configuration);
sfContext::getInstance()->getUser()->setCulture('ja_JP');

//------------------------------------------------------------
$t->diag('opI18N test (ja_JP)');
$t->is($i18N->__('%friend%'), 'フレンド', 'friend normal.');
$t->is($i18N->__('%Friend%'), 'フレンド', 'friend fronting. (The results of normal and fronting are the same)');
$t->is($i18N->__('%my_friend%'), 'マイフレンド', 'my friend normal.');
$t->is($i18N->__('%My_friend%'), 'マイフレンド', 'my friend fronting. (The results of normal and fronting are the same)');
$t->is($i18N->__('%community%'), 'コミュニティ', 'community normal.');
$t->is($i18N->__('%Community%'), 'コミュニティ', 'community fronting. (The results of normal and fronting are the same)');
$t->is($i18N->__('%nickname%'), 'ニックネーム', 'nickname normal.');
$t->is($i18N->__('%Nickname%'), 'ニックネーム', 'nickname fronting. (The results of normal and fronting are the same)');
$t->is($i18N->__('%my_friend%', null), 'マイフレンド', '(#1759) passing null to parameters');
$term = Doctrine_Core::getTable('SnsTerm')->get('my_friend');
$t->is($i18N->__('%my_friend%', array('%my_friend%' => $term->titleize())), 'マイフレンド', '(#4168) passing SnsTerm instance to parameters');
