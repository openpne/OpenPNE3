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
 * Doctrine_Ticket_1015_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1015_TestCase extends Doctrine_UnitTestCase {

    public function prepareTables() {
        $this->tables = array();
        $this->tables[] = 'T1015_Person';
        $this->tables[] = 'T1015_Points';

        parent::prepareTables();
    }

    public function prepareData()
    {
        $person = new T1015_Person();
        $person['name'] = "James";
        $person['T1015_Points']['total'] = 15;
        $person->save();
    }


    public function testDoctrineQueryJoinSelect()
    {

        $q = new Doctrine_Query();
        $q->select('person.id, points.total')
        ->from('T1015_Person person')
        ->innerJoin('person.T1015_Points points WITH person.id = 1');

        $results = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        //var_dump($results);
        $person = $results[0];

        // number of points for person id of 1 should be 15
        $this->assertEqual(15, $person['T1015_Points']['total']); //THIS WILL FAIL
         
    }

    public function testDoctrineRawSQLJoinSelect()
    {
        $q = new Doctrine_RawSql();
        $q->select('{person.id}, {points.total}')
        ->from('person person INNER JOIN points points ON person.id = points.person_id AND person.id=1')
        ->addComponent('person', 'T1015_Person person')
        ->addComponent('points', 'person.T1015_Points points');

        $results = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
        //var_dump($results);
        $person = $results[0];

        // number of points for person id of 1 should be 15
        $this->assertEqual(15, $person['T1015_Points']['total']);
    }
}

class T1015_Person extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('person');
        $this->hasColumn('id', 'integer', 15, array('autoincrement' => true, 'unsigned' => true, 'primary' => true, 'notnull' => true));
        $this->hasColumn('name', 'string', 50);
    }

    public function setUp()
    {
        parent :: setUp();
        $this->hasOne('T1015_Points', array('local' => 'id', 'foreign' => 'person_id'));
    }
}

class T1015_Points extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('points');
        $this->hasColumn('person_id', 'integer', 15, array('primary' => true, 'notnull' => true));
        $this->hasColumn('total', 'integer', 3);
    }

    public function setUp()
    {
        parent :: setUp();
        $this->hasOne('T1015_Person', array('local' => 'person_id', 'foreign' => 'id'));
    }
}
