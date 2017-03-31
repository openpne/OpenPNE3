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
 * Doctrine_Ticket_1253_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1253_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1253_User';
        $this->tables[] = 'Ticket_1253_UserType';
        parent::prepareTables();
    }

    public function testTest()
    {
        $test2 = new Ticket_1253_UserType();
        $test2->name = 'one';
        $test2->save();

        $test3 = new Ticket_1253_UserType();
        $test3->name = 'two';
        $test3->save();

        $test = new Ticket_1253_User();
        $test->name = 'test';
        $test->type_name = 'one';
        $test->save();

        $q = Doctrine_Query::create()
                ->from('Ticket_1253_User u')
                ->leftJoin('u.Type');

        // This will never work because t.type_name is the emulated enum value and t2.name is the actual name
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.name AS t__name, t.type_name AS t__type_name, t2.id AS t2__id, t2.name AS t2__name FROM ticket_1253__user t LEFT JOIN ticket_1253__user_type t2 ON t.type_name = t2.name');
        $results = $q->fetchArray();
        $this->assertEqual($results[0]['Type']['name'], 'one');
    }
}

class Ticket_1253_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
        $this->hasColumn('type_name', 'enum', 9, array('values' => array('one', 'two')));
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1253_UserType as Type', array('local' => 'type_name', 'foreign' => 'name'));
    }
}

class Ticket_1253_UserType extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1253_User as User', array('local' => 'name', 'foreign' => 'type_name', 'owningSide' => true));
    }
}