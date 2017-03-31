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
 * Doctrine_Ticket_952_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_952_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_952_Parent';
        $this->tables[] = 'Ticket_952_Child';
        parent::prepareTables();
    }

    public function testTest()
    {
        $parent = new Ticket_952_Parent();
        $parent->name = 'Parent';
        $parent->Children[0]->name = 'Child 1';
        $parent->Children[1]->name = 'Child 2';
        $parent->save();
        $parent->free(true);

        $profiler = new Doctrine_Connection_Profiler();
        Doctrine_Manager::connection()->setListener($profiler);

        $q = Doctrine_Query::create()
                ->from('Ticket_952_Parent p')
                ->leftJoin('p.Children c');
        $parents = $q->execute();
        $this->assertEqual($parents[0]['Children'][0]['Parent']->name, 'Parent'); // Invoked additional queries
        $this->assertEqual($profiler->count(), 1);
    }
}

class Ticket_952_Parent extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_952_Child as Children', array('local' => 'id', 'foreign' => 'parent_id'));
    }
}

class Ticket_952_Child extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('parent_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('Ticket_952_Parent as Parent', array('local' => 'parent_id', 'foreign' => 'id'));
    }
}