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
 * Doctrine_Ticket_1500_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1500_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'T1500_User';
        $this->tables[] = 'T1500_Group';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $group = new T1500_Group();
        $group->name = 'admin';
        $group->save();

        $user = new T1500_User();
        $user->groupId = $group->id;
        $user->name = 'jwage';
        $user->save();

        $user = new T1500_User();
        $user->groupId = $group->id;
        $user->name = 'guilhermeblanco';
        $user->save();        
    }

    public function testTicket()
    {
        $q = Doctrine_Query::create()
            ->from('T1500_User u')->innerJoin('u.Group g')->where('u.id = 1');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.user_id AS t__user_id, t.group_id AS t__group_id, t.name AS t__name, t2.group_id AS t2__group_id, t2.name AS t2__name FROM t1500__user t INNER JOIN t1500__group t2 ON t.group_id = t2.group_id WHERE (t.user_id = 1)');

        $q = Doctrine_Query::create()
            ->from('T1500_Group g')->innerJoin('g.Users u')->where('g.id = 1');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.group_id AS t__group_id, t.name AS t__name, t2.user_id AS t2__user_id, t2.group_id AS t2__group_id, t2.name AS t2__name FROM t1500__group t INNER JOIN t1500__user t2 ON t.group_id = t2.group_id WHERE (t.group_id = 1)');
    }
}

class T1500_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id as id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('group_id as groupId', 'integer', null);
        $this->hasColumn('name', 'string', 100);
    }

    public function setUp()
    {
        $this->hasOne('T1500_Group as Group', array('local' => 'groupId', 'foreign' => 'id'));
    }
}

class T1500_Group extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('group_id as id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 100);
    }

    public function setUp()
    {
        $this->hasMany('T1500_User as Users', array('local' => 'id', 'foreign' => 'groupId'));
    }
}