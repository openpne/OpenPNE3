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
 * Doctrine_Ticket_1341_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1341_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1341_User';
        $this->tables[] = 'Ticket_1341_Profile';
        parent::prepareTables();
    }

    public function testTest()
    {
        try {
            $user = new Ticket_1341_User();
            $user->username = 'jwage';
            $user->password = 'changeme';
            $user->Profile->name = 'Jonathan H. Wage';
            $user->save();
            $this->pass();
            $this->assertEqual($user->toArray(true), array(
              'id' => '1',
              'username' => 'jwage',
              'password' => 'changeme',
              'Profile' => 
              array(
                'id' => '1',
                'name' => 'Jonathan H. Wage',
                'user_id' => '1',
              ),
            ));
            $q = Doctrine_Query::create()
                ->from('Ticket_1341_User u')
                ->leftJoin('u.Profile p');
            $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t.password AS t__password, t2.id AS t2__id, t2.name AS t2__name, t2.userid AS t2__userid FROM ticket_1341__user t LEFT JOIN ticket_1341__profile t2 ON t.id = t2.userid');
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class Ticket_1341_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1341_Profile as Profile', array('local' => 'id', 'foreign' => 'user_id'));
    }
}

class Ticket_1341_Profile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('userId as user_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1341_User as User', array('local' => 'user_id', 'foreign' => 'id'));
    }
}