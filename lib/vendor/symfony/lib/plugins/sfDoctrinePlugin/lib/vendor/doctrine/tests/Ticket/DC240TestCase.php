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
 * Doctrine_Ticket_DC240_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC240_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC240_User';
        $this->tables[] = 'Ticket_DC240_Role';
        $this->tables[] = 'Ticket_DC240_UserRole';
        $this->tables[] = 'Ticket_DC240_RoleReference';
        parent::prepareTables();
    }

    public function testTest()
    {
        $q = Doctrine_Query::create()
        	->from('Ticket_DC240_User u')
        	->leftJoin('u.Roles r')
        	->leftJoin('r.Parents p')
        	->orderBy('username ASC');

        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t.password AS t__password, t2.id AS t2__id, t2.name AS t2__name, t4.id AS t4__id, t4.name AS t4__name FROM ticket__d_c240__user t LEFT JOIN ticket__d_c240__user_role t3 ON (t.id = t3.id_user) LEFT JOIN ticket__d_c240__role t2 ON t2.id = t3.id_role LEFT JOIN ticket__d_c240__role_reference t5 ON (t2.id = t5.id_role_child) LEFT JOIN ticket__d_c240__role t4 ON t4.id = t5.id_role_parent ORDER BY t.username ASC, t3.position ASC, t5.position DESC');
    }
}

class Ticket_DC240_User extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('username', 'string', 64, array('notnull' => true));
		$this->hasColumn('password', 'string', 128, array('notnull' => true));
	}
	
	public function setUp()
	{
		$this->hasMany('Ticket_DC240_Role as Roles', array('local' => 'id_user', 'foreign' => 'id_role', 'refClass' => 'Ticket_DC240_UserRole', 'orderBy' => 'position ASC'));
	}
}

class Ticket_DC240_Role extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('name', 'string', 64);
	}
	
	public function setUp()
	{
		$this->hasMany('Ticket_DC240_User as Users', array('local' => 'id_role', 'foreign' => 'id_user', 'refClass' => 'Ticket_DC240_UserRole', 'orderBy' => 'position ASC'));
		$this->hasMany('Ticket_DC240_Role as Parents', array('local' => 'id_role_child', 'foreign' => 'id_role_parent', 'refClass' => 'Ticket_DC240_RoleReference', 'orderBy' => 'position DESC'));
		$this->hasMany('Ticket_DC240_Role as Children', array('local' => 'id_role_parent', 'foreign' => 'id_role_child', 'refClass' => 'Ticket_DC240_RoleReference', 'orderBy' => 'position ASC'));
	}
}

class Ticket_DC240_UserRole extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id_user', 'integer', null, array('primary' => true));
		$this->hasColumn('id_role', 'integer', null, array('primary' => true));
		$this->hasColumn('position', 'integer', null, array('notnull' => true));
	}
	
	public function setUp()
	{
		$this->hasOne('Ticket_DC240_User as User', array('local' => 'id_user', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
		$this->hasOne('Ticket_DC240_Role as Role', array('local' => 'id_role', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
	}
}

class Ticket_DC240_RoleReference extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id_role_parent', 'integer', null, array('primary' => true));
		$this->hasColumn('id_role_child', 'integer', null, array('primary' => true));
		$this->hasColumn('position', 'integer', null, array('notnull' => true));
	}
	
	public function setUp()
	{
		$this->hasOne('Ticket_DC240_Role as Parent', array('local' => 'id_role_parent', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
		$this->hasOne('Ticket_DC240_Role as Child', array('local' => 'id_role_child', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
	}
}