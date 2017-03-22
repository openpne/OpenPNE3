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
 * Doctrine_Ticket_DC242_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC242_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC242_User';
        $this->tables[] = 'Ticket_DC242_Role';
        $this->tables[] = 'Ticket_DC242_UserRole';
        $this->tables[] = 'Ticket_DC242_RoleReference';
        parent::prepareTables();
    }

    public function testTest()
    {
        $role = new Ticket_DC242_Role();
        $role->name = 'publisher';
        $role->save();

        $role = new Ticket_DC242_Role();
        $role->name = 'reviewer';
        $role->save();

        $role = new Ticket_DC242_Role();
        $role->name = 'mod';
        $role->save();

        $user = new Ticket_DC242_User();
        $user->fromArray(array(
        	'username' => 'test',
        	'password' => 'test',
        	'Roles' => array(1, 2, 3),
        ));
        $user->save();

        $user->fromArray(array(
        	'Roles' => array(1, 3),
        ));
        $user->save();
        $user->refresh(true);

        $this->assertEqual($user->Roles[0]->id, 1);
        $this->assertEqual($user->Roles[1]->id, 3);
    }
}

class Ticket_DC242_User extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('username', 'string', 64, array('notnull' => true));
		$this->hasColumn('password', 'string', 128, array('notnull' => true));
	}

	public function setUp()
	{
		$this->hasMany('Ticket_DC242_Role as Roles', array('local' => 'id_user', 'foreign' => 'id_role', 'refClass' => 'Ticket_DC242_UserRole'));
	}
}

class Ticket_DC242_Role extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('name', 'string', 64);
	}

	public function setUp()
	{
		$this->hasMany('Ticket_DC242_User as Users', array('local' => 'id_role', 'foreign' => 'id_user', 'refClass' => 'Ticket_DC242_UserRole'));
		$this->hasMany('Ticket_DC242_Role as Parents', array('local' => 'id_role_child', 'foreign' => 'id_role_parent', 'refClass' => 'Ticket_DC242_RoleReference'));
		$this->hasMany('Ticket_DC242_Role as Children', array('local' => 'id_role_parent', 'foreign' => 'id_role_child', 'refClass' => 'Ticket_DC242_RoleReference'));
	}
}

class Ticket_DC242_UserRole extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id_user', 'integer', null, array('primary' => true));
		$this->hasColumn('id_role', 'integer', null, array('primary' => true));
	}

	public function setUp()
	{
		$this->hasOne('Ticket_DC242_User as User', array('local' => 'id_user', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
		$this->hasOne('Ticket_DC242_Role as Role', array('local' => 'id_role', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
	}
}

class Ticket_DC242_RoleReference extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id_role_parent', 'integer', null, array('primary' => true));
		$this->hasColumn('id_role_child', 'integer', null, array('primary' => true));
	}

	public function setUp()
	{
		$this->hasOne('Ticket_DC242_Role as Parent', array('local' => 'id_role_parent', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
		$this->hasOne('Ticket_DC242_Role as Child', array('local' => 'id_role_child', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
	}
}