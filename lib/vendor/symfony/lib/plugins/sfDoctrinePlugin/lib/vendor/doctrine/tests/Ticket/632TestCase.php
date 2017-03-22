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
 * Doctrine_Ticket_632_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_632_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_632_User';
        $this->tables[] = 'Ticket_632_Group';
        $this->tables[] = 'Ticket_632_UserGroup';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $user = new Ticket_632_User();
        $user->username = 'jwage';
        $user->password = 'changeme';
        $user->Groups[]->name = 'Group One';
        $user->Groups[]->name = 'Group Two';
        $user->Groups[]->name = 'Group Three';
        $user->save();
    }

    public function testTest()
    {
        $user = Doctrine_Query::create()
            ->from('Ticket_632_User u, u.Groups g')
            ->where('u.username = ?', 'jwage')
            ->limit(1)
            ->fetchOne();
        $this->assertEqual($user->Groups->count(), 3);
        unset($user->Groups[2]);
        $this->assertEqual($user->Groups->count(), 2);
        $user->save(); // This deletes the UserGroup association and the Group record

        // We should still have 3 groups
        $groups = Doctrine_Core::getTable('Ticket_632_Group')->findAll();
        $this->assertEqual($groups->count(), 3);
    }
}

class Ticket_632_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_632_Group as Groups', array('local'   => 'user_id',
                                                           'foreign' => 'group_id',
                                                           'refClass' => 'Ticket_632_UserGroup'));
    }
}

class Ticket_632_Group extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_632_User as Users', array('local'   => 'group_id',
                                                         'foreign' => 'user_id',
                                                         'refClass' => 'Ticket_632_UserGroup'));
    }
}

class Ticket_632_UserGroup extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer', 4, array('primary' => true));
        $this->hasColumn('group_id', 'integer', 4, array('primary' => true));
    }

    public function setUp()
    {
        $this->hasOne('Ticket_632_User as User', array('local'   => 'user_id',
                                                       'foreign' => 'id'));

        $this->hasOne('Ticket_632_Group as Group', array('local' => 'group_id',
                                                         'foreign' => 'id'));
    }
}