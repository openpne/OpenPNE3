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
class Doctrine_NestedSet_Hydration_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'NestedSetTest_SingleRootNode';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $node = new NestedSetTest_SingleRootNode();
        $node->name = 'root';
        $treeMngr = $this->conn->getTable('NestedSetTest_SingleRootNode')->getTree();
        $treeMngr->createRoot($node);
        
        $node2 = new NestedSetTest_SingleRootNode();
        $node2->name = 'node2';
        $node2->getNode()->insertAsLastChildOf($node);
        
        $node3 = new NestedSetTest_SingleRootNode();
        $node3->name = 'node3';
        $node3->getNode()->insertAsLastChildOf($node2);
    }

    public function testRecordHierarchyHydration()
    {
        $results = Doctrine_Core::getTable('NestedSetTest_SingleRootNode')
            ->createQuery('n')
            ->execute(array(), Doctrine_Core::HYDRATE_RECORD_HIERARCHY);

        $this->assertEqual($results[0]['__children'][0]['__children'][0]['name'], 'node3');
        $this->assertTrue($results instanceof Doctrine_Collection);
    }

    public function testArrayHierarchyHydration()
    {
        $results = Doctrine_Core::getTable('NestedSetTest_SingleRootNode')
            ->createQuery('n')
            ->execute(array(), Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);

        $this->assertEqual($results[0]['__children'][0]['__children'][0]['name'], 'node3');
        $this->assertTrue(is_array($results));
    }

    public function testArrayHierarchyToArray()
    {
        $array = Doctrine_Core::getTable('NestedSetTest_SingleRootNode')
            ->createQuery('n')
            ->execute(array(), Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);

        $coll = Doctrine_Core::getTable('NestedSetTest_SingleRootNode')
            ->createQuery('n')
            ->execute(array(), Doctrine_Core::HYDRATE_RECORD_HIERARCHY);

        $this->assertEqual($array, $coll->toArray());
    }

    public function testHierarchyHydrationNotAllowedOnInvalidModel()
    {
        try {
            $results = Doctrine_Core::getTable('User')
                ->createQuery('u')
                ->execute(array(), Doctrine_Core::HYDRATE_RECORD_HIERARCHY);

            $this->fail();
        } catch (Exception $e) {
            $this->pass();
        }

        try {
            $results = Doctrine_Core::getTable('User')
                ->createQuery('u')
                ->execute(array(), Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);

            $this->fail();
        } catch (Exception $e) {
            $this->pass();
        }
    }
}