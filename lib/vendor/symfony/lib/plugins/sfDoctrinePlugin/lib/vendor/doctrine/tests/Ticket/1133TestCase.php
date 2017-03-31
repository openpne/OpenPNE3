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
 * Doctrine_Ticket_1133_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1133_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1133_Foo';
        $this->tables[] = 'Ticket_1133_Bar';
        parent::prepareTables();
    }

    public function testTest()
    {
        $foo = new Ticket_1133_Foo();
        $foo->name = 'test';
        $foo->Bar->name = 'test2';
        $foo->save();

        $q = Doctrine_Query::create()
                ->from('Ticket_1133_Foo f')
                ->innerJoin('f.Bar b ON b.id = ?', $foo->Bar->id)
                ->addWhere('f.name = ?', 'test');

        $this->assertEqual($q->count(), 1);
    }

    public function testTest2()
    {
        $foo = new Ticket_1133_Foo();
        $foo->name = 'test';
        $foo->Bar->name = 'test2';
        $foo->save();

        $q = Doctrine_Query::create()
                ->from('Ticket_1133_Foo f')
                ->innerJoin('f.Bar b')
                ->addWhere('b.name = ?', 'test2')
                ->limit(1)
                ->offset(1);

        $this->assertEqual($q->count(), 2);
        $this->assertEqual($q->execute()->count(), 1);
    }

}

class Ticket_1133_Foo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('bar_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1133_Bar as Bar', array('local' => 'bar_id', 'foreign' => 'id'));
    }
}

class Ticket_1133_Bar extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }
}