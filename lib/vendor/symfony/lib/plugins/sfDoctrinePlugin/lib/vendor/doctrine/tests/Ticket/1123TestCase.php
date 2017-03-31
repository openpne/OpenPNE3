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
 * Doctrine_Ticket_1123_TestCase
 *
 * @package     Doctrine
 * @author      Jfung
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1123_TestCase extends Doctrine_UnitTestCase 
{
    public function testInit()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mysql');
        $this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh);
    }

    public function testExportSql()
    {
        $sql = $this->conn->export->exportClassesSql(array('Ticket_1123_User', 'Ticket_1123_UserReference'));
        $this->assertEqual(count($sql), 4);
        $this->assertEqual($sql[0], 'CREATE TABLE ticket_1123__user_reference (user1 BIGINT, user2 BIGINT, PRIMARY KEY(user1, user2)) ENGINE = INNODB');
        $this->assertEqual($sql[1], 'CREATE TABLE ticket_1123__user (id BIGINT AUTO_INCREMENT, name VARCHAR(30), PRIMARY KEY(id)) ENGINE = INNODB');
        $this->assertEqual($sql[2], 'ALTER TABLE ticket_1123__user_reference ADD CONSTRAINT ticket_1123__user_reference_user2_ticket_1123__user_id FOREIGN KEY (user2) REFERENCES ticket_1123__user(id) ON DELETE CASCADE');
        $this->assertEqual($sql[3], 'ALTER TABLE ticket_1123__user_reference ADD CONSTRAINT ticket_1123__user_reference_user1_ticket_1123__user_id FOREIGN KEY (user1) REFERENCES ticket_1123__user(id) ON DELETE CASCADE');
    }
}

class Ticket_1123_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 30);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1123_User as Friend', array('local'    => 'user1',
                                                           'foreign'  => 'user2',
                                                           'refClass' => 'Ticket_1123_UserReference',
                                                           'equal'    => true));
    }
}

class Ticket_1123_UserReference extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user1', 'integer', null, array('primary' => true));
        $this->hasColumn('user2', 'integer', null, array('primary' => true));
    }

    public function setUp()
    {
        $this->hasOne('Ticket_1123_User as User1', array('local' => 'user1', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
        $this->hasOne('Ticket_1123_User as User2', array('local' => 'user2', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
    }
}