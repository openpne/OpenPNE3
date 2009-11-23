<?php

include_once dirname(__FILE__).'/../../../bootstrap/unit.php';
include_once dirname(__FILE__).'/../../../bootstrap/database.php';

$t = new lime_test(11, new lime_output_color());

$table = Doctrine::getTable('SnsTerm');

//------------------------------------------------------------
$t->diag('SnsTermTable');
$t->diag('SnsTermTable::set()');
$table->configure(null, null);
$t->cmp_ok($table->set('foo', 'bar'), '===', false);
$table->configure('en', 'pc_frontend');
$table->set('foo', 'foo', 'en', 'pc_frontend');
$t->ok($table->findOneByName('foo'));
$table->set('bar', 'bar', 'en', 'pc_frontend');
$t->ok($table->findOneByName('bar'));

//------------------------------------------------------------
$t->diag('SnsTermTable::offsetGet()');
$t->is((string)$table['friend'], 'friend');
$t->is((string)$table['Friend'], 'Friend');
$t->is((string)$table['xxxxxxxxxx'], '');

//------------------------------------------------------------
$t->diag('SnsTermTable::offsetExists()');
$t->ok(isset($table['friend']));
$t->ok(isset($table['Friend']));
$t->ok(!isset($table['xxxxxxxxxx']));

//------------------------------------------------------------
$t->diag('SnsTermTable::offsetSet()');
try
{
  $table['foo'] = 'bar';
  $t->fail();
}
catch (LogicException $e)
{
  $t->pass();
}

//------------------------------------------------------------
$t->diag('SnsTermTable::offsetUnset()');
try
{
  unset($table['foo']);
  $t->fail();
}
catch (LogicException $e)
{
  $t->pass();
}
