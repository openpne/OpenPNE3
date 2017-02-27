<?php

require_once __DIR__.'/../../bootstrap/unit.php';
require_once __DIR__.'/../../bootstrap/database.php';

$t = new lime_test();

$t->diag('opI18N::__()');

$i18n = new opI18N($configuration, new sfNoCache(), array('culture' => 'en'));

$t->is($i18n->__('@@ %my_friend% @@'), '@@ my friend @@');
$t->is($i18n->__('@@ %My_friend% @@'), '@@ My friend @@');

$t->info('#1759: passing null to parameters');

$t->is($i18n->__('@@ %my_friend% @@', null), '@@ my friend @@');

$t->info('#4168: passing SnsTerm instance to parameters');

// purge cache in opI18N::$parsed
$i18n = new opI18N($configuration, new sfNoCache(), array('culture' => 'en'));

$term = Doctrine_Core::getTable('SnsTerm')->get('my_friend');
$t->is($i18n->__('@@ %my_friend% @@', array('%my_friend%' => $term->titleize())), '@@ My Friend @@');
