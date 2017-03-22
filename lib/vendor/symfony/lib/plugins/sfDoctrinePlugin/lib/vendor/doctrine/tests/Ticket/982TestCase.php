<?php
/*
 * Test to ensure LocalKey Relations allow 0 for id value
 */
class Doctrine_Ticket_982_TestCase extends Doctrine_UnitTestCase
{    
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = "T982_MyModel";
        parent::prepareTables();
    }

    public function prepareData()
    {
	  $myModelZero = new T982_MyModel();
	  $myModelZero->id = 0;
	  $myModelZero->parentid = 0;
      $myModelZero->save();
      $this->assertIdentical(0, $myModelZero->id);
	  
      $this->myModelOne = new T982_MyModel();
	  $this->myModelOne->id = 1;
	  $this->myModelOne->parentid = 0;
      $this->myModelOne->save();
	  
      $this->myModelTwo = new T982_MyModel();
	  $this->myModelTwo->id = 2;
	  $this->myModelTwo->parentid = 1;
      $this->myModelTwo->save();
    }

    public function testTicket()
    {
        $this->conn->getTable('T982_MyModel')->clear();
        
        $myModelZero = $this->conn->getTable('T982_MyModel')->find(0);
        
		$this->assertIdentical($myModelZero->id, '0');
		$this->assertIdentical($myModelZero->parentid, '0');
		$this->assertTrue($myModelZero->parent->exists());
		$this->assertTrue(ctype_digit($myModelZero->parent->id));
		$this->assertIdentical($myModelZero, $myModelZero->parent);
		$this->assertIdentical($myModelZero->parent->id, '0');
		$this->assertIdentical($myModelZero->parent->parentid, '0');		
        
        $myModelOne = $this->conn->getTable('T982_MyModel')->find(1);
        
        $this->assertIdentical($myModelOne->id, '1');
		$this->assertIdentical($myModelOne->parentid, '0');
		$this->assertTrue($myModelOne->parent->exists());
		$this->assertTrue(ctype_digit($myModelOne->parent->id));
		$this->assertIdentical($myModelOne->parent->id, '0');
		$this->assertIdentical($myModelOne->parent->parentid, '0');
		
		$myModelTwo = $this->conn->getTable('T982_MyModel')->find(2);

        $this->assertIdentical($myModelTwo->id, '2');
		$this->assertIdentical($myModelTwo->parentid, '1');
		$this->assertIdentical($myModelTwo->parent->id, '1');
		$this->assertIdentical($myModelTwo->parent->parentid, '0');
		
   }
}

class T982_MyModel extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'notnull' => true));
        $this->hasColumn('parentid', 'integer', 4, array('notnull' => true));
    }

    public function setUp()
    {
        $this->hasOne('T982_MyModel as parent', array('local' => 'parentid', 'foreign' => 'id'));
    }

}