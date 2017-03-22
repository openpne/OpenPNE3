<?php
class Doctrine_Ticket_1225_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array('Ticket_1225_Tree');
        parent::prepareTables();
    }
    
    public function prepareData()
    {
    }

    public function testMoveAsSameNodeThrowsException() 
    {
        $root1 = new Ticket_1225_Tree();
        $tree = $root1->getTable()->getTree();

        $root1->name = 'Name 1';
        $tree->createRoot($root1);

        try {
            $root1->getNode()->moveAsPrevSiblingOf($root1);
            $this->fail();
        } catch (Doctrine_Tree_Exception $e) {
            $this->pass();
        }

        try {
            $root1->getNode()->moveAsNextSiblingOf($root1);
            $this->fail();
        } catch (Doctrine_Tree_Exception $e) {
            $this->pass();
        }

        try {
            $root1->getNode()->moveAsFirstChildOf($root1);
            $this->fail();
        } catch (Doctrine_Tree_Exception $e) {
            $this->pass();
        }

        try {
	      $root1->getNode()->moveAsLastChildOf($root1);
            $this->fail();
        } catch (Doctrine_Tree_Exception $e) {
            $this->pass();
        }
    }
}

class Ticket_1225_Tree extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');

        $this->actAs('NestedSet');
    }
}