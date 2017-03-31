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
 * Doctrine_Ticket_1280_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1131_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        //$this->tables = array();
        $this->tables[] = 'Ticket_1131_User';
        $this->tables[] = 'Ticket_1131_Group';
        $this->tables[] = 'Ticket_1131_Role';
        parent::prepareTables();
    }
    
    
    public function prepareData()
    {
        parent::prepareData();
        
        $role = new Ticket_1131_Role();
        $role->name = 'Role One';
        $role->save();
        $this->role_one = $role->id;
        $role->free();
        
        $role = new Ticket_1131_Role();
        $role->name = 'Role Two';
        $role->save();
        $this->role_two = $role->id;
        $role->free();
        
        $group = new Ticket_1131_Group();
        $group->role_id = $this->role_one;
        $group->name = 'Core Dev';
        $group->save();

        $user = new Ticket_1131_User();
        $user->Group = $group;
        $user->role_id = $this->role_two;
        $user->name = 'jwage';
        $user->save();

        $group->free();
        $user->free();
    }

    public function testTicket()
    {
        $user = Doctrine_Query::create()
            ->from('Ticket_1131_User u')
            ->where('u.id = ?')->fetchOne(array(1));

        $this->assertEqual($user->Group->id, 1);
        $this->assertFalse($user->get('group_id') instanceof Doctrine_Record);
    }
    
    public function testTicketWithOverloadingAndTwoQueries()
    {
        $orig = Doctrine_Manager::getInstance()->getAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        
        $user = Doctrine_Query::create()
            ->from('Ticket_1131_User u')
            ->where('u.id = ?')->fetchOne(array(1));
        
        $user = Doctrine_Query::create()
            ->from('Ticket_1131_UserWithOverloading u')
            ->leftJoin('u.Group g')
            ->leftJoin('u.Role r')
            ->addWhere('u.id = ?')->fetchOne(array(1));
        
        $this->assertEqual($user->Role->id, 1);
        $this->assertFalse($user->role_id instanceof Doctrine_Record);
        
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, $orig);
    }
}

class Ticket_1131_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('group_id', 'integer', 20, array(
            'notnull' => false, 'default' => null
        ));
        $this->hasColumn('role_id', 'integer', 20, array(
            'notnull' => false, 'default' => null
        ));
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1131_Group as Group', array(
            'local' => 'group_id',
            'foreign' => 'id'
        ));
        
        $this->hasOne('Ticket_1131_Role as Role', array(
            'local' => 'role_id',
            'foreign' => 'id'));
    }
}

class Ticket_1131_UserWithOverloading extends Ticket_1131_User
{
    public function getRole()
    {
        return $this->Group->Role;
    }
    
    public function getRoleId()
    {
        return $this->Group->role_id;
    }
}

class Ticket_1131_Group extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('role_id', 'integer', 20, array(
            'notnull' => false, 'default' => null
        ));
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1131_Role as Role', array(
            'local' => 'role_id',
            'foreign' => 'id'));
        
        $this->hasMany('Ticket_1131_User as Users', array(
            'local' => 'id',
            'foreign' => 'group_id'
        ));
    }
}

class Ticket_1131_Role extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1131_User as Users', array(
            'local' => 'id',
            'foreign' => 'role_id'
        ));
        $this->hasMany('Ticket_1131_Group as Groups', array(
            'local' => 'id',
            'foreign' => 'role_id'
        ));
    }
}