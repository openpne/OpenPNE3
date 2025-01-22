<?php

include_once dirname(__FILE__) . '/../../../bootstrap/unit.php';
include_once dirname(__FILE__) . '/../../../bootstrap/database.php';
sfContext::createInstance($configuration);

$t = new lime_test(10, new lime_output_color());

$table = Doctrine::getTable('Profile');

//------------------------------------------------------------
$t->diag('ProfileTable');
$t->diag('ProfileTable::getPublicFlags()');
$data = array(
  4 => 'Web全体に公開',
  1 => '全員に公開',
  2 => 'マイフレンドまで公開',
  3 => '公開しない'
);
$t->is($table->getPublicFlags(), $data);

//------------------------------------------------------------
$t->diag('ProfileTable::getPublicFlag()');
$t->is($table->getPublicFlag(ProfileTable::PUBLIC_FLAG_SNS), '全員に公開');
$t->is($table->getPublicFlag(ProfileTable::PUBLIC_FLAG_FRIEND), 'マイフレンドまで公開');
$t->is($table->getPublicFlag(ProfileTable::PUBLIC_FLAG_PRIVATE), '公開しない');

//------------------------------------------------------------
$t->diag('ProfileTable::retrievesAll()');
$result = $table->retrievesAll();
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('ProfileTable::retrieveByIsDispRegist()');
$result = $table->retrieveByIsDispRegist();
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('ProfileTable::retrieveByIsDispConfig()');
$result = $table->retrieveByIsDispConfig();
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('ProfileTable::retrieveByIsDispSearch()');
$result = $table->retrieveByIsDispSearch();
$t->isa_ok($result, 'Doctrine_Collection');

//------------------------------------------------------------
$t->diag('ProfileTable::retrieveByName()');
$result = $table->retrieveByName('op_preset_sex');
$t->isa_ok($result, 'Profile');

//------------------------------------------------------------
$t->diag('ProfileTable::getMaxSortOrder()');
$t->todo();


