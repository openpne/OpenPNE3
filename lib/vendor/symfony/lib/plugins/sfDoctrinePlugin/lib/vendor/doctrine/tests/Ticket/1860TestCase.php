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
 * Doctrine_Ticket_1860_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1860_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        // Create 10 users and mark them as deleted.
        for ($i=0; $i<10; $i++) {
            $user = new Ticket_1860_User;
            $user->username = 'user' . $i;
            $user->password = md5('user' . $i);
            $user->save();
            $user->delete();
        }
    }

    public function prepareTables()
    {
        $this->tables = array('Ticket_1860_User');
        parent::prepareTables();
    }

    public function testTicket()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $query1 = Doctrine_Query::create()
            ->select('u.*')
            ->from('Ticket_1860_User u');

        $this->assertEqual(count($query1->fetchArray()), 0);

        $query2 = Doctrine_Query::create()
            ->select('u.*')
            ->from('Ticket_1860_User u');

        // Defining initial variables
        $currentPage = 1;
        $resultsPerPage = 5;

        // Creating pager object
        $pager = new Doctrine_Pager($query2, $currentPage, $resultsPerPage);

        $this->assertEqual(count($pager->execute()->toArray()), 0);
        $this->assertEqual($pager->getQuery()->getSqlQuery(), 'SELECT t.id AS t__id, t.username AS t__username, t.password AS t__password, t.deleted_at AS t__deleted_at FROM ticket_1860_users t WHERE (t.deleted_at IS NULL) LIMIT 5');
        
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class Ticket_1860_User extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('ticket_1860_users');

    $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'unsigned' => '1', 'primary' => true, 'autoincrement' => true, 'length' => '4'));
    $this->hasColumn('username', 'string', 45, array('type' => 'string', 'notnull' => true, 'unique' => true, 'length' => '45'));
    $this->hasColumn('password', 'string', 45, array('type' => 'string', 'notnull' => true, 'length' => '45'));
  }

  public function setUp()
  {
    $softdelete0 = new Doctrine_Template_SoftDelete();
    $this->actAs($softdelete0);
  }
}
