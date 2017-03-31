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
 * Doctrine_Ticket_DC23_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC23_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC23_User';
        $this->tables[] = 'Ticket_DC23_Contact';
        $this->tables[] = 'Ticket_DC23_Address';
        $this->tables[] = 'Ticket_DC23_Phonenumber';
        $this->tables[] = 'Ticket_DC23_BlogPost';

        parent::prepareTables();
    }

    public function testTest()
    {
        $yml = <<<END
---
Ticket_DC23_BlogPost:
  BlogPost_1:
    Translation:
      en:
        title: Test
        body: Testing

Ticket_DC23_User: 
  User_1: 
    name: jwage
    Contact:
      name: Test Contact
      Address: Address_1
      Phonenumbers:
        Phonenumber_1:
          name: test1
        Phonenumber_2:
          name: test2
  User_2:
    name: romanb
    Contact:
      name: Roman
      Address:
        name: Testing
      Phonenumbers: [Phonenumber_3]

Ticket_DC23_Phonenumber:
  Phonenumber_3:
    name: Testing

Ticket_DC23_Address:
  Address_1:
    name: Test Address
END;
        try {
            file_put_contents('test.yml', $yml);
            Doctrine_Core::loadData('test.yml', true);

            $q = Doctrine_Query::create()
                ->from('Ticket_DC23_User u')
                ->leftJoin('u.Contact c')
                ->leftJoin('c.Phonenumbers p')
                ->leftJoin('c.Address a');
            $results = $q->fetchArray();

            $this->assertTrue(isset($results[0]['Contact']['address_id']));
            $this->assertEqual(count($results[0]['Contact']['Phonenumbers']), 2);
            $this->assertEqual(count($results[1]['Contact']['Phonenumbers']), 1);

            $q = Doctrine_Query::create()
                ->from('Ticket_DC23_BlogPost p')
                ->leftJoin('p.Translation t');
            $results = $q->fetchArray();

            $this->assertEqual(count($results[0]['Translation']), 1);

            $this->pass();
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            $this->fail($e->getMessage());
        }

        unlink('test.yml');
    }
}

class Ticket_DC23_BlogPost extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 255);
        $this->hasColumn('body', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('I18n', array('fields' => array('title', 'body')));
    }
}

class Ticket_DC23_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('contact_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC23_Contact as Contact', array(
                'local' => 'contact_id',
                'foreign' => 'id',
                'onDelete' => 'CASCADE'
            )
        );
    }
}

class Ticket_DC23_Contact extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('address_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC23_Address as Address', array(
                'local' => 'address_id',
                'foreign' => 'id',
                'onDelete' => 'CASCADE'
            )
        );

        $this->hasMany('Ticket_DC23_Phonenumber as Phonenumbers', array(
                'local' => 'id',
                'foreign' => 'contact_id'
            )
        );
    }
}

class Ticket_DC23_Address extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }
}

class Ticket_DC23_Phonenumber extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('contact_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC23_Contact as Contact', array(
                'local' => 'contact_id',
                'foreign' => 'id',
                'onDelete' => 'CASCADE'
            )
        );
    }
}