<?php

include_once dirname(__FILE__) . '/../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../bootstrap/database.php';

$t = new lime_test(6);

$t->diag('opConfig::get()');

// 'Default' is not defined
sfConfig::set('openpne_sns_config', array(
  'foo' => array(),
));

$t->is(opConfig::get('foo', null), null);
$t->is(opConfig::get('foo', 'default'), 'default');

Doctrine_Core::getTable('SnsConfig')->set('foo', 'tetete');
$t->is(opConfig::get('foo', null), 'tetete');

// 'Default' is defined
sfConfig::set('openpne_sns_config', array(
  'bar' => array('Default' => 'hogehoge'),
));

$t->is(opConfig::get('bar', null), 'hogehoge');
$t->is(opConfig::get('bar', 'default'), 'hogehoge');

Doctrine_Core::getTable('SnsConfig')->set('bar', 'tetete');
$t->is(opConfig::get('bar', null), 'tetete');
