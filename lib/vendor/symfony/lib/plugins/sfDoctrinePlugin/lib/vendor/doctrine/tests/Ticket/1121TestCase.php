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
 * Doctrine_Ticket_1121_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1121_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1121_User';
        $this->tables[] = 'Ticket_1121_Profile';
        parent::prepareTables();
    }

    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);
        $q = Doctrine_Query::create()
                ->from('Ticket_1121_User u')
                // UserProfile has SoftDelete behavior but because it is aliased as Profile, it tries to 
                // call the dql callbacks for the query on a class named Profile instead of UserProfile
                // Code responsible for this is in Doctrine_Query_Abstract::_preQuery()
                ->leftJoin('u.Profile p');

        // The condition and params for UserProfile SoftDelete and are not added properly
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t.password AS t__password, t.profile_id AS t__profile_id, t.deleted_at AS t__deleted_at, t2.id AS t2__id, t2.name AS t2__name, t2.about AS t2__about, t2.deleted_at AS t2__deleted_at FROM ticket_1121__user t LEFT JOIN ticket_1121__profile t2 ON t.profile_id = t2.id AND (t2.deleted_at IS NULL) WHERE (t.deleted_at IS NULL)');
        $this->assertEqual(count($q->getFlattenedParams()), 0);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class Ticket_1121_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
        $this->hasColumn('profile_id', 'integer');
    }

    public function setUp()
    {
        $this->actAs('SoftDelete');
        $this->hasOne('Ticket_1121_Profile as Profile', array('local'   => 'profile_id',
                                                              'foreign' => 'id'));
    }
}

class Ticket_1121_Profile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('about', 'string', 2000);
    }

    public function setUp()
    {
        $this->actAs('SoftDelete');
        $this->hasOne('Ticket_1121_User as User', array('local'   => 'id',
                                                        'foreign' => 'profile_id'));
    }
}