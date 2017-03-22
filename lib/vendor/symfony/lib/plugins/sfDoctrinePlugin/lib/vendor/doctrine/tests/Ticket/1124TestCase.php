<?php

class Doctrine_Ticket_1124_TestCase extends Doctrine_UnitTestCase
{
	const NO_ALIAS			= 5;
	const SOMETHING_ELSE	= 8;
	const TABLEIZED_ALIAS	= 27;
	const CLASSIFIED_ALIAS  = 29;
	const ANOTHER_ALIAS     = 30;

    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1124_Record';

        parent::prepareTables();
    }

    public function prepareData()
    {
        $record = new Ticket_1124_Record();
        $record->no_alias		 = self::NO_ALIAS;
        $record->somethingElse	 = self::SOMETHING_ELSE;
        $record->tableizedAlias	 = self::TABLEIZED_ALIAS;
        $record->ClassifiedAlias = self::CLASSIFIED_ALIAS;
        $record->another_Alias   = self::ANOTHER_ALIAS;
        $record->save();
    }

	private function assertIsSampleRecord($record)
	{
    	$this->assertNotNull($record);
    	$this->assertEqual($record->no_alias, self::NO_ALIAS);
    	$this->assertEqual($record->somethingElse, self::SOMETHING_ELSE);
    	$this->assertEqual($record->tableizedAlias, self::TABLEIZED_ALIAS);
    	$this->assertEqual($record->ClassifiedAlias, self::CLASSIFIED_ALIAS);
	}

    public function testFindByUnaliasedColumnWorks()
    {
        try {
    	    $r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneByNoAlias(self::NO_ALIAS);
    	    $this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByDisjointlyAliasedColumnWorks()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneBysomethingElse(self::SOMETHING_ELSE);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByDisjointlyAliasedColumnWorks2()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneBydisjoint_alias(self::SOMETHING_ELSE);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByDisjointlyAliasedColumnWorks3()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneByDisjointAlias(self::SOMETHING_ELSE);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByTableizedAliasedColumnWorks()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneBytableizedAlias(self::TABLEIZED_ALIAS);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByTableizedAliasedColumnWorks2()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneBytableized_alias(self::TABLEIZED_ALIAS);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByClassifiedAliasedColumnWorks()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneByClassifiedAlias(self::CLASSIFIED_ALIAS);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByAnotherAliasedColumnWorks()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneByTest(self::ANOTHER_ALIAS);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByAnotherAliasedColumnWorks2()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneBytest(self::ANOTHER_ALIAS);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testFindByAnotherAliasedColumnWorks3()
    {
        try {
        	$r = Doctrine_Core::getTable('Ticket_1124_Record')->findOneByanother_Alias(self::ANOTHER_ALIAS);	// test currently fails
    	
        	$this->assertIsSampleRecord($r);
    	    $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class Ticket_1124_Record extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('emb1_record');
        $this->hasColumn('id', 'integer', 15, array('autoincrement' => true, 'unsigned' => true, 'primary' => true, 'notnull' => true));
        $this->hasColumn('no_alias', 'integer', 4);	// column with no aliasing
        $this->hasColumn('disjoint_alias as somethingElse', 'integer', 4);	// column whose alias has no relation to the column itself
        $this->hasColumn('tableized_alias as tableizedAlias', 'integer', 4);	// column whose alias' tableized form is equivalent to the column name itself
        $this->hasColumn('w00t as ClassifiedAlias', 'integer', 4);
        $this->hasColumn('test as another_Alias', 'integer', 4);
    }
}