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
class Doctrine_NestedSet_TimestampableMultiRoot_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'NestedSet_Timestampable_MultiRootNode';
        parent::prepareTables();
    }

    public function prepareData()
    {}
    
    
    public function testSavingNewRecordWithRootIdWorks() {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true );
        $node = new NestedSet_Timestampable_MultiRootNode();
        $node->name = 'root';
        $node->root_id = 42;
        $child1 = new NestedSet_Timestampable_MultiRootNode();
        $child1->name = 'node1';
        $child1->root_id = 42;
        $node2 = new NestedSet_Timestampable_MultiRootNode();
        $node2->name = 'node2';
        $node2->root_id = 42;
        //try {
            $treeMngr = $this->conn->getTable('NestedSet_Timestampable_MultiRootNode')->getTree();
            $treeMngr->createRoot($node);
            $this->assertEqual(1, $node['lft']);
            $this->assertEqual(2, $node['rgt']);
            $this->assertNotEqual(null, $node['created_at']);
            $this->assertNotEqual(null, $node['updated_at']);
            $child1->name = "child1";
            $child1->getNode()->insertAsLastChildOf($node);
        
            $node->refresh(); // ! updates lft/rgt
            // test insertion
            $this->assertEqual(2, $child1->lft);
            $this->assertEqual(3, $child1->rgt);
            $this->assertEqual(1, $child1->level);
            // test node has been shifted
            $this->assertEqual(1, $node->lft);
            $this->assertEqual(4, $node->rgt);
            $this->assertEqual(0, $node->level);
            
            $node->getNode()->delete();
        //} catch (Exception $e) {
        //    $this->fail();
        //}
    }

}
