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
 * Doctrine_Ticket_1483_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1483_TestCase extends Doctrine_UnitTestCase 
{
    public function testTest()
    {
        $q = Doctrine_Query::create()
            ->from('Ticket_1483_User u')
            ->leftJoin('u.Groups g WITH g.id = (SELECT g2.id FROM Ticket_1483_Group g2 WHERE (g2.name = \'Test\' OR g2.name = \'Test2\'))');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t2.id AS t2__id, t2.name AS t2__name FROM ticket_1483__user t LEFT JOIN ticket_1483__user_group t3 ON (t.id = t3.user_id) LEFT JOIN ticket_1483__group t2 ON t2.id = t3.group_id AND (t2.id = (SELECT t4.id AS t4__id FROM ticket_1483__group t4 WHERE (t4.name = \'Test\' OR t4.name = \'Test2\')))');

    }
    
    public function testTest2()
    {
        $q = Doctrine_Query::create()
            ->from('Ticket_1483_User u')
            ->leftJoin('u.Groups g WITH g.id = (SELECT g2.id FROM Ticket_1483_Group g2 WHERE (g2.name = \'Test\' OR (g2.name = \'Test2\')))');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t2.id AS t2__id, t2.name AS t2__name FROM ticket_1483__user t LEFT JOIN ticket_1483__user_group t3 ON (t.id = t3.user_id) LEFT JOIN ticket_1483__group t2 ON t2.id = t3.group_id AND (t2.id = (SELECT t4.id AS t4__id FROM ticket_1483__group t4 WHERE (t4.name = \'Test\' OR t4.name = \'Test2\')))');

    }
    
    public function testTest3()
    {
        $q = Doctrine_Query::create()
            ->from('Ticket_1483_User u')
            ->leftJoin('u.Groups g WITH g.id = (SELECT g2.id FROM Ticket_1483_Group g2 WHERE ((g2.name = \'Test\' AND g2.name = \'Test2\') OR (g2.name = \'Test2\')))');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t2.id AS t2__id, t2.name AS t2__name FROM ticket_1483__user t LEFT JOIN ticket_1483__user_group t3 ON (t.id = t3.user_id) LEFT JOIN ticket_1483__group t2 ON t2.id = t3.group_id AND (t2.id = (SELECT t4.id AS t4__id FROM ticket_1483__group t4 WHERE ((t4.name = \'Test\' AND t4.name = \'Test2\') OR t4.name = \'Test2\')))');

    }

    public function testTest4()
    {
        $q = Doctrine_Query::create()
            ->from('Ticket_1483_User u')
            ->leftJoin('u.Groups g WITH g.id = (SELECT COUNT(*) FROM Ticket_1483_Group)');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t2.id AS t2__id, t2.name AS t2__name FROM ticket_1483__user t LEFT JOIN ticket_1483__user_group t3 ON (t.id = t3.user_id) LEFT JOIN ticket_1483__group t2 ON t2.id = t3.group_id AND (t2.id = (SELECT COUNT(*) AS t4__0 FROM ticket_1483__group t4))');

    }
}

class Ticket_1483_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1483_Group as Groups', array('local'    => 'user_id',
                                                            'foreign'  => 'group_id',
                                                            'refClass' => 'Ticket_1483_UserGroup'));
    }
}

class Ticket_1483_Group extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1483_User as Users', array('local'    => 'group_id',
                                                          'foreign'  => 'user_id',
                                                          'refClass' => 'Ticket_1483_UserGroup'));
    }
}


class Ticket_1483_UserGroup extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer');
        $this->hasColumn('group_id', 'integer');
    }
}