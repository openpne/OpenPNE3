<?php

class Doctrine_Ticket_932_TestCase extends Doctrine_UnitTestCase
{
	public function prepareTables()
	{
		$this->tables[] = "UserNoAutoIncrement";
		parent::prepareTables();
	}

	public function prepareData()
	{
	}

	public function testInit()
	{
		$this->dbh = new Doctrine_Adapter_Mock('pgsql');
		$this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh);
		$this->assertEqual(Doctrine_Core::IDENTIFIER_NATURAL, $this->conn->getTable('UserNoAutoIncrement')->getIdentifierType());
	}

	public function testCreateNewUserNoAutoIncrement()
	{
		$newUser = new UserNoAutoIncrement();
		$newUser->id = 1;
		$newUser->display_name = "Mah Name";
		$newUser->save();
		$this->assertEqual(Doctrine_Record::STATE_CLEAN, $newUser->state());
		$this->assertEqual(1, $newUser->id);
	}
}

class UserNoAutoIncrement extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => false, 'notnull' => true));
		$this->hasColumn('display_name', 'string', 255, array('notnull' => true));
	}
}
