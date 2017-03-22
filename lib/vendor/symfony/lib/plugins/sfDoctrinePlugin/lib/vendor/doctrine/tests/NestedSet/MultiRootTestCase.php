<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Record_State_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_NestedSet_MultiRoot_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'NestedSet_MultiRootNode';
        parent::prepareTables();
    }

    public function prepareData()
    {}
    
    public function testSavingNewRecordAsRootWithoutRootIdThrowsException() {
        $node = new NestedSet_MultiRootNode();
        $node->name = 'root';
        try {
            $treeMngr = $this->conn->getTable('NestedSet_MultiRootNode')->getTree();
            $treeMngr->createRoot($node);
            $this->fail();
        } catch (Doctrine_Tree_Exception $e) {
            $this->pass();
        }
    }
    
    public function testSavingNewRecordWithRootIdWorks() {
        $node = new NestedSet_MultiRootNode();
        $node->name = 'root';
        $node->root_id = 42;
        try {
            $treeMngr = $this->conn->getTable('NestedSet_MultiRootNode')->getTree();
            $treeMngr->createRoot($node);
            $this->assertEqual(1, $node['lft']);
            $this->assertEqual(2, $node['rgt']);
            $node->getNode()->delete();
        } catch (Doctrine_Tree_Exception $e) {
            $this->fail();
        }
    }
    
    public function testSavingPersistentRecordAsRootAssignsIdToRootId() {
        $node = new NestedSet_MultiRootNode();
        $node->name = 'root';
        $node->save();
        try {
            $treeMngr = $this->conn->getTable('NestedSet_MultiRootNode')->getTree();
            $treeMngr->createRoot($node);
            $this->assertEqual(1, $node['lft']);
            $this->assertEqual(2, $node['rgt']);
            $this->assertEqual($node->id, $node->root_id);
            $node->getNode()->delete();
        } catch (Doctrine_Tree_Exception $e) {
            $this->fail();
        }
    }
    
    public function testSaveMultipleRootsWithChildren() {
        $root1 = new NestedSet_MultiRootNode();
        $root1->name = 'root1';
        $root1->save();
        try {
            $treeMngr = $this->conn->getTable('NestedSet_MultiRootNode')->getTree();
            $treeMngr->createRoot($root1);
            $this->assertEqual(1, $root1['lft']);
            $this->assertEqual(2, $root1['rgt']);
            $this->assertEqual($root1->id, $root1->root_id);
        } catch (Doctrine_Tree_Exception $e) {
            $this->fail();
        }
        
        $root2 = new NestedSet_MultiRootNode();
        $root2->name = 'root';
        $root2->save();
        try {
            $treeMngr = $this->conn->getTable('NestedSet_MultiRootNode')->getTree();
            $treeMngr->createRoot($root2);
            $this->assertEqual(1, $root2['lft']);
            $this->assertEqual(2, $root2['rgt']);
            $this->assertEqual($root2->id, $root2->root_id);
        } catch (Doctrine_Tree_Exception $e) {
            $this->fail();
        }
        
        // now a child for root1
        $child1 = new NestedSet_MultiRootNode();
        $child1->name = "child1";
        $child1->getNode()->insertAsLastChildOf($root1);
        
        $root1->refresh(); // ! updates lft/rgt
        // test insertion
        $this->assertEqual(2, $child1->lft);
        $this->assertEqual(3, $child1->rgt);
        $this->assertEqual(1, $child1->level);
        // test root1 has been shifted
        $this->assertEqual(1, $root1->lft);
        $this->assertEqual(4, $root1->rgt);
        $this->assertEqual(0, $root1->level);
        
        // now a child for root2
        $child2 = new NestedSet_MultiRootNode();
        $child2->name = "child2";
        $child2->getNode()->insertAsLastChildOf($root2);
        
        $root2->refresh(); // ! updates lft/rgt
        // test insertion
        $this->assertEqual(2, $child2->lft);
        $this->assertEqual(3, $child2->rgt);
        $this->assertEqual(1, $child2->level);
        // test root2 has been shifted
        $this->assertEqual(1, $root2->lft);
        $this->assertEqual(4, $root2->rgt);
        $this->assertEqual(0, $root2->level);
        
        // query some
        $root1Id = $root1->id;
        $root2Id = $root2->id;
        $this->conn->getTable('NestedSet_MultiRootNode')->clear();
        // check the root1 child
        $treeMngr = $this->conn->getTable('NestedSet_MultiRootNode')->getTree();
        $root1 = $treeMngr->fetchRoot($root1Id);
        $desc = $root1->getNode()->getDescendants();
        $this->assertTrue($desc !== false);
        $this->assertEqual(1, count($desc));
        $this->assertEqual('child1', $desc[0]['name']);
        $this->assertEqual(1, $desc[0]['level']);
        // check the root2 child
        $root2 = $treeMngr->fetchRoot($root2Id);
        $desc = $root2->getNode()->getDescendants();
        $this->assertTrue($desc !== false);
        $this->assertEqual(1, count($desc));
        $this->assertEqual('child2', $desc[0]['name']);
        $this->assertEqual(1, $desc[0]['level']);
    }

}
