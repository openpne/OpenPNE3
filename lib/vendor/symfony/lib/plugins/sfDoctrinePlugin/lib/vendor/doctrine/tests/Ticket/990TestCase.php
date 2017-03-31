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
 * Doctrine_Ticket_990_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_990_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_990_Person';
        parent::prepareTables();
    }

    public function testOverwriteIdentityMap()
    {        
        $person = new Ticket_990_Person();
        $person->firstname = 'John';
        $person->save();
        
        $person->firstname = 'Alice';
        
        $person = Doctrine_Core::getTable('Ticket_990_Person')->find($person->id);
        
        $this->assertEqual('John', $person->firstname);
    }

    public function testDontOverwriteIdentityMap()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_HYDRATE_OVERWRITE, false);

        $user = Doctrine_Core::getTable('User')->createQuery()->fetchOne();
        $user->name = 'test';
        $user = Doctrine_Core::getTable('User')->find($user->id);
        $this->assertEqual($user->name, 'test');

        
        $person = new Ticket_990_Person();
        $person->firstname = 'John';
        $person->save(); 
        
        $person->firstname = 'Alice';
        
        $this->assertEqual(Doctrine_Record::STATE_DIRTY, $person->state());
        $this->assertTrue($person->isModified());
        $this->assertEqual(array('firstname' => 'Alice'), $person->getModified());
        
        $person = Doctrine_Core::getTable('Ticket_990_Person')->find($person->id);
        
        $this->assertEqual('Alice', $person->firstname);
        $this->assertEqual(Doctrine_Record::STATE_DIRTY, $person->state());
        $this->assertTrue($person->isModified());
        $this->assertEqual(array('firstname' => 'Alice'), $person->getModified());
        
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_HYDRATE_OVERWRITE, true);
    }

    public function testRefreshAlwaysOverwrites()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_HYDRATE_OVERWRITE, false);
        
        $person = new Ticket_990_Person();
        $person->firstname = 'John';
        $person->save();
        
        $person->firstname = 'Alice';
        
        $person->refresh();
        
        $this->assertEqual('John', $person->firstname);
        
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_HYDRATE_OVERWRITE, true);
    }
}

class Ticket_990_Person extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('person');
        $this->hasColumn('id', 'integer', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true) );
        $this->hasColumn('firstname', 'string');
        $this->hasColumn('lastname', 'string');
    }
}