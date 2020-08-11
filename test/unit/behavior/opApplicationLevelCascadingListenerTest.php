<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

require_once dirname(__FILE__).'/../../bootstrap/unit.php';

$t = new lime_test(null, new lime_output_color());

// ---------------
// Defining Mocks
// ---------------
class TestParent extends opDoctrineRecord
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
  }

  public function setUp()
  {
    parent::setUp();

    $this->hasMany('TestChildA as TestChildrenA', array('local' => 'id', 'foreign' => 'test_parent_id'));
    $this->hasMany('TestChildB as TestChildrenB', array('local' => 'id', 'foreign' => 'test_parent_id'));
    $this->hasOne('TestChildC as TestChildC', array('local' => 'id', 'foreign' => 'test_parent_id'));
    $this->hasOne('TestChildD as TestChildD', array('local' => 'id', 'foreign' => 'test_parent_id'));
  }
}

class TestChildA extends opDoctrineRecord
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('test_parent_id', 'integer', 4);
  }

  public function setUp()
  {
    parent::setUp();

    $this->hasOne('TestParent', array('local' => 'test_parent_id', 'foreign' => 'id', 'onDelete' => 'cascade'));
  }
}

class TestChildB extends opDoctrineRecord
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('test_parent_id', 'integer', 4);
  }

  public function setUp()
  {
    parent::setUp();

    $this->hasOne('TestParent', array('local' => 'test_parent_id', 'foreign' => 'id', 'onDelete' => 'set null'));
  }
}

class TestChildC extends opDoctrineRecord
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('test_parent_id', 'integer', 4);
  }

  public function setUp()
  {
    parent::setUp();

    $this->hasOne('TestParent', array('local' => 'test_parent_id', 'foreign' => 'id', 'onDelete' => 'cascade'));
  }
}
class TestChildD extends opDoctrineRecord
{
  public function setTableDefinition()
  {
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('test_parent_id', 'integer', 4);
  }

  public function setUp()
  {
    parent::setUp();

    $this->hasOne('TestParent', array('local' => 'test_parent_id', 'foreign' => 'id', 'onDelete' => 'set null'));
  }
}

function getRecord()
{
  $parent = new TestParent();
  $parent->TestChildrenA[] = new TestChildA();
  $parent->TestChildrenB[] = new TestChildB();
  $parent->TestChildC = new TestChildC();
  $parent->TestChildD = new TestChildD();
  $parent->save();

  return $parent;
}

function initAdapter($adapter)
{
  while ($adapter->pop());
}

function getConnection($isAppLevelCascading = false)
{
  $adapter = new Doctrine_Adapter_Mock('mysql');
  $conn = Doctrine_Manager::connection($adapter, 'doctrine');

  if ($isAppLevelCascading)
  {
    Doctrine_Manager::connection()->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL ^ Doctrine::EXPORT_CONSTRAINTS);
  }
  else
  {
    Doctrine_Manager::connection()->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL);
  }
  Doctrine_Manager::connection()->setAttribute(Doctrine::ATTR_USE_DQL_CALLBACKS, true);

  return array($conn, $adapter);

}

// ---------------
// Doing Tests
// ---------------
list ($conn, $adapter) = getConnection();

$t->comment('Doctrine::ATTR_EXPORT has Doctrine::EXPORT_CONSTRAINTS, so opApplicationLevelCascadingListener is disabled.');
$record = getRecord();
initAdapter($adapter);
$t->todo('The record does not have any listener.');
$record->delete();
$t->ok(in_array('DELETE FROM test_parent WHERE id = ?', $adapter->getAll()), 'The record is removed by DELETE query.');
$t->ok(!in_array('DELETE FROM test_child_a WHERE id = ?', $adapter->getAll()), 'The child A is not removed.');
$t->ok(!in_array('UPDATE test_child_b SET test_parent_id = ? WHERE id = ?', $adapter->getAll()), 'The child B doesn\'t unlink to parent.');
$t->ok(!in_array('DELETE FROM test_child_c WHERE id = ?', $adapter->getAll()), 'The child C is not removed.');
$t->ok(!in_array('UPDATE test_child_b SET test_parent_id = ? WHERE id = ?', $adapter->getAll()), 'The child D doesn\'t unlink to parent.');

initAdapter($adapter);
$record->getTable()->createQuery()->delete()->execute();
$t->ok(in_array('DELETE FROM test_parent', $adapter->getAll()), 'TestParents are removed by DELETE query.');
$t->ok(!in_array('DELETE FROM test_child_a WHERE test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2)', $adapter->getAll()), 'TestChlildrenA are not removed.');
$t->ok(!in_array('UPDATE test_child_b SET test_parent_id = ? WHERE test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2)', $adapter->getAll()), 'TestChlildrenB don\'t unlink to parent.');
$t->ok(!in_array('DELETE FROM test_child_c WHERE test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2)', $adapter->getAll()), 'TestChlildrenC are not removed.');
$t->ok(!in_array('UPDATE test_child_d SET test_parent_id = ? WHERE test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2)', $adapter->getAll()), 'TestChlildrenD don\'t unlink to parent.');

Doctrine_Manager::getInstance()->closeConnection($conn);
// ---------------
list ($conn, $adapter) = getConnection(true);

$t->comment('Doctrine::ATTR_EXPORT doesn\'t have Doctrine::EXPORT_CONSTRAINTS, so opApplicationLevelCascadingListener is enabled.');
$record = getRecord();
initAdapter($adapter);
$t->ok($record->getListener() instanceof Doctrine_Record_Listener_Chain, 'The record has listener.');
$record->delete();
$t->ok(in_array('DELETE FROM test_parent WHERE id = ?', $adapter->getAll()), 'The record is removed by DELETE query.');
$t->ok(in_array('DELETE FROM test_child_a WHERE id = ?', $adapter->getAll()), 'The child A is removed.');
$t->ok(in_array('UPDATE test_child_b SET test_parent_id = ? WHERE id = ?', $adapter->getAll()), 'The child B unlinks to parent.');
$t->ok(in_array('DELETE FROM test_child_c WHERE id = ?', $adapter->getAll()), 'The child C is removed.');
$t->ok(in_array('UPDATE test_child_b SET test_parent_id = ? WHERE id = ?', $adapter->getAll()), 'The child D unlinks to parent.');

initAdapter($adapter);
$record->getTable()->createQuery()->delete()->execute();
$t->ok(in_array('DELETE FROM test_parent', $adapter->getAll()), 'TestParents are removed by DELETE query.');
$t->ok(in_array('DELETE FROM test_child_a WHERE (test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2))', $adapter->getAll()), 'TestChlildrenA are removed.');
$t->ok(in_array('UPDATE test_child_b SET test_parent_id = ? WHERE (test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2))', $adapter->getAll()), 'TestChlildrenB unlink to parent.');
$t->ok(in_array('DELETE FROM test_child_c WHERE (test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2))', $adapter->getAll()), 'TestChlildrenC are removed.');
$t->ok(in_array('UPDATE test_child_d SET test_parent_id = ? WHERE (test_parent_id IN (SELECT t2.id AS t2__id FROM test_parent t2))', $adapter->getAll()), 'TestChlildrenD unlink to parent.');

Doctrine_Manager::getInstance()->closeConnection($conn);
// ---------------

