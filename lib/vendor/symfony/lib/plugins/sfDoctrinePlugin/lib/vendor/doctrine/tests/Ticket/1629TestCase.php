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
 * Doctrine_Ticket_1629_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1629_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1629_User';
        $this->tables[] = 'Ticket_1629_Phonenumber';
        parent::prepareTables();
    }

    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $user = new Ticket_1629_User();
        $user->username = 'jwage';
        $user->password = 'changeme';
        $user->Phonenumbers[0]->phonenumber = '6155139185';
        $user->save();
        $user->Phonenumbers[0]->delete();

        $q = Doctrine_Query::create()
            ->from('Ticket_1629_User u')
            ->leftJoin('u.Phonenumbers p');
            
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t.password AS t__password, t.deleted_at AS t__deleted_at, t2.id AS t2__id, t2.user_id AS t2__user_id, t2.phonenumber AS t2__phonenumber, t2.deleted_at AS t2__deleted_at FROM ticket_1629__user t LEFT JOIN ticket_1629__phonenumber t2 ON t.id = t2.user_id AND (t2.deleted_at IS NULL) WHERE (t.deleted_at IS NULL)');
            
        $users = $q->fetchArray();
        $this->assertEqual(count($users), 1);
        $this->assertEqual(count($users[0]['Phonenumbers']), 0);

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class Ticket_1629_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('SoftDelete');
        $this->hasMany('Ticket_1629_Phonenumber as Phonenumbers', array('local' => 'id', 'foreign' => 'user_id'));
    }
}

class Ticket_1629_Phonenumber extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer');
        $this->hasColumn('phonenumber', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('SoftDelete');
        $this->hasOne('Ticket_1629_User as User', array('local' => 'user_id', 'foreign' => 'id'));
    }
}