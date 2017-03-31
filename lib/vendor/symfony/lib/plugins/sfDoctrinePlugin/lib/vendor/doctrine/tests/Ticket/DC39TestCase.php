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
 * Doctrine_Ticket_DC39_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC39_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        $g1 = new Ticket_DC39_Group();
        $g1['name'] = 'group1';
        $g1->save();

        $g2 = new Ticket_DC39_Group();
        $g2['name'] = 'group2';
        $g2->save();

        $u1 = new Ticket_DC39_User();
        $u1['group_id'] = 1;
        $u1['name'] = 'user1';
        $u1->save();

        $u2 = new Ticket_DC39_User();
        $u2['group_id'] = 2;
        $u2['name'] = 'user2';
        $u2->save();
    }

    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC39_Group';
        $this->tables[] = 'Ticket_DC39_User';
        parent::prepareTables();
    }

    public function testOneToManyRelationsWithSynchronizeWithArray()
    {
    		// link group (id 2) with users (id 1,2)
    		$group = Doctrine_Core::getTable('Ticket_DC39_Group')->find(2);
    		$group->synchronizeWithArray(array(
    			'Users' => array(1, 2)
    		));
    		$group->save();

    		// update the user-objects with real data from database
    		$user1 = Doctrine_Core::getTable('Ticket_DC39_User')->find(1);
    		$user2 = Doctrine_Core::getTable('Ticket_DC39_User')->find(2);

    		// compare the group_id (should be 2) with the group_id set through $group->synchronizeWithArray
    		$this->assertEqual($group->Users[0]->group_id, 2);
    		$this->assertEqual($group->Users[1]->group_id, 2);
    }
   
}

class Ticket_DC39_Group extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('name', 'string', 255);
	}
	
	public function setUp()
	{
		$this->hasMany('Ticket_DC39_User as Users', array(
			'local' => 'id',
			'foreign' => 'group_id'
		));
	}
}

class Ticket_DC39_User extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('group_id', 'integer');
		$this->hasColumn('name', 'string', 255);
	}

	public function setUp()
	{
		$this->hasOne('Ticket_DC39_Group as Group', array(
			'local' => 'group_id',
			'foreign' => 'id'
		));
	}
}