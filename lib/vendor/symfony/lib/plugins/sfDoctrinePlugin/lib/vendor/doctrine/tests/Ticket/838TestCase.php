<?php
/**
 * Doctrine_Ticket_838_TestCase
 *
 * @package     Doctrine
 * @author      Jani Hartikainen <firstname at codeutopia net>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @version     $Revision$
 */

class Doctrine_Ticket_838_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'NestedSetTest_SingleRootNode';
        parent::prepareTables();
    }

    public function prepareData()
    {

    }
    
    /**
     * Test that the old root is placed correctly under the new root
     */
    public function testMoveRoot()
    {
        $node = new NestedSetTest_SingleRootNode();
        $node->name = 'oldroot';
        $tree = $this->conn->getTable('NestedSetTest_SingleRootNode')->getTree();
        $tree->createRoot($node);
        
        $oldRoot = $node->copy();
        // detach the node, so that it can be inserted as a new node
        $oldRoot->getNode()->detach();
        
        $node->getNode()->delete();
        
        $this->assertTrue($oldRoot !== $node);
        $this->assertEqual(0, $oldRoot['lft']);
        $this->assertEqual(0, $oldRoot['rgt']);
        $this->assertEqual(0, $oldRoot['level']);
        
        $newRoot = new NestedSetTest_SingleRootNode();
        $newRoot->name = 'newroot';
        $tree->createRoot($newRoot);
        $this->assertEqual(1, $newRoot['lft']);
        $this->assertEqual(2, $newRoot['rgt']);
        $this->assertEqual(0, $newRoot['level']);
        
        $oldRoot->getNode()->insertAsFirstChildOf($newRoot);
        
        $this->assertEqual(2, $oldRoot['lft']);
        $this->assertEqual(3, $oldRoot['rgt']);
        $this->assertEqual(1, $oldRoot['level']);
        
        $newRoot->refresh();
        
        $this->assertEqual(1, $newRoot['lft']);
        $this->assertEqual(4, $newRoot['rgt']);
        $this->assertEqual(0, $newRoot['level']);
		
        $children = $newRoot->getNode()->getChildren();
        $oldRoot = $children[0];
	
        $this->assertEqual(2, $oldRoot['lft']);
        $this->assertEqual(3, $oldRoot['rgt']);
        $this->assertEqual(1, $oldRoot['level']);
    }
}