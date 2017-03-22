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
 * Doctrine_Ticket_DC300_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC300_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        $g1 = new Ticket_DC300_Group();
        $g1['name'] = 'group1';
        $g1->save();

        $g2 = new Ticket_DC300_Group();
        $g2['name'] = 'group2';
        $g2->save();

        $g3 = new Ticket_DC300_Group();
        $g3['name'] = 'group3';
        $g3->save();

        $u1 = new Ticket_DC300_User();
        $u1['name'] = 'user1';
        $u1['Groups']->add($g1);
        $u1['Groups']->add($g2);
        $u1->save();
    }

    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC300_Group';
        $this->tables[] = 'Ticket_DC300_User';
        $this->tables[] = 'Ticket_DC300_UserGroup';
        parent::prepareTables();
    }

    public function testRefTableEntriesOnManyToManyRelationsWithSynchronizeWithArray()
    {
		$u1 = Doctrine_Core::getTable('Ticket_DC300_User')->find(1);

		// update the groups user (id 1) is linked to
		$u1->synchronizeWithArray(array(
			'Groups' => array(
				array('name' => 'group1 update'),
				array('name' => 'group2 update')
			)
		));
		$u1->save();

		// update the user-objects with real data from database
		$u1->loadReference('Groups');

		// check wether the two database-entries in RefTable exists
		$this->assertEqual(count($u1->Groups), 2);
    }
   
}

class Ticket_DC300_Group extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('name', 'string', 255);
	}
	
	public function setUp()
	{
		$this->hasMany('Ticket_DC300_User as Users', array(
			'local' => 'group_id',
			'foreign' => 'user_id',
			'refClass' => 'Ticket_DC300_UserGroup'
		));
	}
}

class Ticket_DC300_User extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('name', 'string', 255);
	}

	public function setUp()
	{
		$this->hasMany('Ticket_DC300_Group as Groups', array(
			'local' => 'user_id',
			'foreign' => 'group_id',
			'refClass' => 'Ticket_DC300_UserGroup'
		));
	}
}

class Ticket_DC300_UserGroup extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('user_id', 'integer');
		$this->hasColumn('group_id', 'integer');
	}
}