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
 * Doctrine_Ticket_1641_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1641_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'T1641_User';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $user = new T1641_User();
        $user->name = 'jwage';
        $user->save();

        $user = new T1641_User();
        $user->name = 'guilhermeblanco';
        $user->save();   

        $user->delete();     
    }

    public function testTicket()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $table = Doctrine_Core::getTable('T1641_User');

        $this->assertEqual($table->createQuery()->getCountSqlQuery(), 'SELECT COUNT(*) AS num_results FROM t1641__user t WHERE (t.deleted_at IS NULL)');
	
        $this->assertEqual($table->count(), 1);
        $this->assertEqual($table->createQuery()->execute()->count(), 1);
        $this->assertEqual($table->createQuery()->count(), 1);

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class T1641_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id as id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 100);
    }

    public function setUp()
    {
        $this->actAs('SoftDelete');
    }
}