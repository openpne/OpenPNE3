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
 * Doctrine_Ticket_DC236_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC236_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC236_File';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $node = new Ticket_DC236_File();
        $node->name = 'root';
        $treeMngr = $this->conn->getTable('Ticket_DC236_File')->getTree();
        $treeMngr->createRoot($node);

        $node2 = new Ticket_DC236_File();
        $node2->name = 'node2';
        $node2->getNode()->insertAsLastChildOf($node);

        $node3 = new Ticket_DC236_File();
        $node3->name = 'node3';
        $node3->getNode()->insertAsLastChildOf($node2);

        $node4 = new Ticket_DC236_File();
        $node4->name = 'node4';
        $node4->getNode()->insertAsLastChildOf($node2);
    }

    public function testTest()
    {
        $treeMngr = $this->conn->getTable('Ticket_DC236_File')->getTree();
        $root = $treeMngr->fetchRoot();
        $descendants = $root->getNode()->getDescendants()->toHierarchy();
        $this->assertEqual(2, count($descendants[0]['__children']));
    }
}

class Ticket_DC236_File extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
    }

    public function setUp()
    {
        $nestedset0 = new Doctrine_Template_NestedSet();
        $this->actAs($nestedset0);
    }
}