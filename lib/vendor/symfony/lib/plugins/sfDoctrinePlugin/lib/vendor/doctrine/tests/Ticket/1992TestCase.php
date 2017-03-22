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
 * Doctrine_Ticket_1992_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1992_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1992_Person';
        $this->tables[] = 'Ticket_1992_Profile';
        $this->tables[] = 'Ticket_1992_PersonProfile';
        parent::prepareTables();
    }

    public function prepareData()
    {
      $this->person = new Ticket_1992_Person();
      $this->person->nummer = '1';
      $this->profile1 = new Ticket_1992_Profile();
      $this->profile1->name = 'test 2';
      $this->person->Profile[] = $this->profile1;
      $this->profile2 = new Ticket_1992_Profile();
      $this->profile2->name = 'test 2';
      $this->person->Profile[] = $this->profile2;
      $this->person->save();
    }

    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $person = Doctrine_Query::create()
            ->from('Ticket_1992_Person p')
            ->innerJoin('p.Profile pr')
            ->fetchOne();
        $this->assertEqual($person['nummer'], 1);
        $this->assertEqual(count($person['Profile']), 2);

        $this->profile1->delete();

        $person = Doctrine_Query::create()
            ->from('Ticket_1992_Person p')
            ->innerJoin('p.Profile pr')
            ->fetchOne();
        $this->assertEqual($person['nummer'], 1);
        $this->assertEqual(count($person['Profile']), 1);

        $this->profile2->delete();

        $person = Doctrine_Query::create()
            ->from('Ticket_1992_Person p')
            ->innerJoin('p.Profile pr')
            ->fetchOne();
        $this->assertEqual($person, false);

        $person = Doctrine_Query::create()
            ->from('Ticket_1992_Person p')
            ->leftJoin('p.Profile pr')
            ->fetchOne();
        $this->assertEqual($person['nummer'], 1);
        $this->assertEqual(count($person['Profile']), 0);

        $this->person->delete();

        $person = Doctrine_Query::create()
            ->from('Ticket_1992_Person p')
            ->fetchOne();
        $this->assertEqual($person, false);

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class Ticket_1992_Person extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('nummer', 'string', 16, array('type' => 'string', 'length' => 16, 'primary' => true));        
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1992_Profile as Profile', array('local' => 'person_nummer', 'foreign' => 'profile_id', 'refClass' => 'Ticket_1992_PersonProfile'));
        $this->actAs('SoftDelete');
    }
}

class Ticket_1992_Profile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'unsigned' => 1, 'primary' => true, 'autoincrement' => true));        
        $this->hasColumn('name', 'string');
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1992_Person as Person', array('local' => 'profile_id', 'foreign' => 'person_nummer', 'refClass' => 'Ticket_1992_PersonProfile'));
        $this->actAs('SoftDelete');
    }
}

class Ticket_1992_PersonProfile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('person_nummer', 'string', 16, array('type' => 'string', 'length' => 16, 'notnull' => true));
        $this->hasColumn('profile_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'unsigned' => 1, 'notnull' => true));        
    }

    public function setUp()
    {
        $this->actAs('SoftDelete');
    }
}