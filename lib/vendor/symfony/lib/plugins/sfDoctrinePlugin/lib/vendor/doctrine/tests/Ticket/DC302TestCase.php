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
 * Doctrine_Ticket_DC302_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC302_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC302_Role';
        $this->tables[] = 'Ticket_DC302_RoleReference';
        $this->tables[] = 'Ticket_DC302_User';
        $this->tables[] = 'Ticket_DC302_UserRole';
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        $role1 = new Ticket_DC302_Role();
        $role1->name = 'admin'; // id: 1
        $role1->save();
        
        $role2 = new Ticket_DC302_Role();
        $role2->name = 'publisher'; // id: 2
        $role2->save();
        
        $role3 = new Ticket_DC302_Role();
        $role3->name = 'reviewer'; // id: 3
        $role3->save();
        
        $role4 = new Ticket_DC302_Role();
        $role4->name = 'mod'; // id: 4
        $role4->save();
        
        // reviewer inherits from admin, mod, publisher - in that order
        $role3->Parents[] = $role1;
        $role3->Parents[] = $role4;
        $role3->Parents[] = $role2;
        $role3->save();
        
        // update positions
        $query = Doctrine_Query::create()
            ->update('Ticket_DC302_RoleReference')
            ->set('position', '?', 0)
            ->where('id_role_child = ?', 3)
            ->andWhere('id_role_parent = ?', 1)
            ->execute();
        $query = Doctrine_Query::create()
            ->update('Ticket_DC302_RoleReference')
            ->set('position', '?', 1)
            ->where('id_role_child = ?', 3)
            ->andWhere('id_role_parent = ?', 4)
            ->execute();
        $query = Doctrine_Query::create()
            ->update('Ticket_DC302_RoleReference')
            ->set('position', '?', 2)
            ->where('id_role_child = ?', 3)
            ->andWhere('id_role_parent = ?', 2)
            ->execute();
            
            
        $user = new Ticket_DC302_User();
        $user->username = 'test';
        $user->password = 'test';
        $user->fromArray(array('Roles' => array(4, 2)));
        $user->save();
        // update positions
        $query = Doctrine_Query::create()
            ->update('Ticket_DC302_UserRole')
            ->set('position', '?', 0)
            ->where('id_user = ?', 1)
            ->andWhere('id_role = ?', 4)
            ->execute();
        $query = Doctrine_Query::create()
            ->update('Ticket_DC302_UserRole')
            ->set('position', '?', 1)
            ->where('id_user = ?', 1)
            ->andWhere('id_role = ?', 2)
            ->execute();
    }
    
    public function testTest()
    {
        $profiler = new Doctrine_Connection_Profiler();
        $this->conn->addListener($profiler);

		$role = Doctrine_Core::getTable('Ticket_DC302_Role')->find(3);
		$parents = $role->Parents->toArray();
        
        $this->assertEqual($parents[1]['Ticket_DC302_RoleReference'][0]['position'], 1);

        $events = $profiler->getAll();
        $event = array_pop($events);
        $this->assertEqual($event->getQuery(), 'SELECT ticket__d_c302__role.id AS ticket__d_c302__role__id, ticket__d_c302__role.name AS ticket__d_c302__role__name, ticket__d_c302__role_reference.id_role_parent AS ticket__d_c302__role_reference__id_role_parent, ticket__d_c302__role_reference.id_role_child AS ticket__d_c302__role_reference__id_role_child, ticket__d_c302__role_reference.position AS ticket__d_c302__role_reference__position FROM ticket__d_c302__role INNER JOIN ticket__d_c302__role_reference ON ticket__d_c302__role.id = ticket__d_c302__role_reference.id_role_parent WHERE ticket__d_c302__role.id IN (SELECT id_role_parent FROM ticket__d_c302__role_reference WHERE id_role_child = ?) ORDER BY position');
    }
}

class Ticket_DC302_Role extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 64);
    }
    
    public function setUp()
    {
        $this->hasMany('Ticket_DC302_User as Users', array('local' => 'id_role', 'foreign' => 'id_user', 'refClass' => 'Ticket_DC302_UserRole'));
        $this->hasMany('Ticket_DC302_Role as Parents', array('local' => 'id_role_child', 'foreign' => 'id_role_parent', 'refClass' => 'Ticket_DC302_RoleReference', 'orderBy' => 'position'));
        $this->hasMany('Ticket_DC302_Role as Children', array('local' => 'id_role_parent', 'foreign' => 'id_role_child', 'refClass' => 'Ticket_DC302_RoleReference'));
    }
}

class Ticket_DC302_RoleReference extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id_role_parent', 'integer', null, array('primary' => true));
        $this->hasColumn('id_role_child', 'integer', null, array('primary' => true));
        $this->hasColumn('position', 'integer', null, array('notnull' => true, 'default' => 0));
    }
    
    public function setUp()
    {
        $this->hasOne('Ticket_DC302_Role as Parent', array('local' => 'id_role_parent', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
        $this->hasOne('Ticket_DC302_Role as Child', array('local' => 'id_role_child', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    }
}

class Ticket_DC302_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 64, array('notnull' => true));
        $this->hasColumn('password', 'string', 128, array('notnull' => true));
    }
    
    public function setUp()
    {
        $this->hasMany('Ticket_DC302_Role as Roles', array('local' => 'id_user', 'foreign' => 'id_role', 'refClass' => 'Ticket_DC302_UserRole', 'orderBy' => 'position'));
    }
}

class Ticket_DC302_UserRole extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id_user', 'integer', null, array('primary' => true));
        $this->hasColumn('id_role', 'integer', null, array('primary' => true));
        $this->hasColumn('position', 'integer', null, array('notnull' => true, 'default' => 0));
    }
    
    public function setUp()
    {
        $this->hasOne('Ticket_DC302_User as User', array('local' => 'id_user', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
        $this->hasOne('Ticket_DC302_Role as Role', array('local' => 'id_role', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    }
}