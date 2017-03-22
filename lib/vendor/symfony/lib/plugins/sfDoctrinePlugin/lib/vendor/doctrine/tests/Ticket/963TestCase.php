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
 * Doctrine_Ticket_963_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_963_TestCase extends Doctrine_UnitTestCase 
{
    public function testInit()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mysql');
        $this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh);
    }

    public function testExportSql()
    {
        $sql = $this->conn->export->exportClassesSql(array('Ticket_963_User', 'Ticket_963_Email'));
        $this->assertEqual(count($sql), 3);
        $this->assertEqual($sql[0], 'CREATE TABLE ticket_963__user (id BIGINT AUTO_INCREMENT, username VARCHAR(255), password VARCHAR(255), PRIMARY KEY(id)) ENGINE = INNODB');
        $this->assertEqual($sql[1], 'CREATE TABLE ticket_963__email (user_id INT, address2 VARCHAR(255), PRIMARY KEY(user_id)) ENGINE = INNODB');
        $this->assertEqual($test = isset($sql[2]) ? $sql[2]:null, 'ALTER TABLE ticket_963__email ADD CONSTRAINT ticket_963__email_user_id_ticket_963__user_id FOREIGN KEY (user_id) REFERENCES ticket_963__user(id) ON DELETE CASCADE');
    }
}

class Ticket_963_User extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->hasColumn('username', 'string', 255);
    $this->hasColumn('password', 'string', 255);
  }

  public function setUp()
  {
    $this->hasOne('Ticket_963_Email as Email', array('local' => 'id',
                                 'foreign' => 'user_id'));
  }
}

class Ticket_963_Email extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->hasColumn('user_id', 'integer', 4, array('primary' => true));
    $this->hasColumn('address2', 'string', 255);
  }

  public function setUp()
  {
    $this->hasOne('Ticket_963_User as User', array(
                                'local' => 'user_id',
                                'foreign' => 'id',
                                'owningSide' => true,
                                'onDelete' => 'CASCADE'));
  }
}