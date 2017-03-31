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
 * Doctrine_Ticket_1589_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$ 
 */
class Doctrine_Ticket_1604_TestCase extends Doctrine_UnitTestCase 
{
    public function testExport()
    {
        $conn = Doctrine_Manager::connection('mysql://root@localhost/test');
        $sql = $conn->export->exportClassesSql(array('Ticket_1604_User', 'Ticket_1604_EmailAdresses'));

        $def = array(
            "CREATE TABLE ticket_1604__user (id BIGINT AUTO_INCREMENT, name VARCHAR(30), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB",
            "CREATE TABLE ticket_1604__email_adresses (id BIGINT AUTO_INCREMENT, user_id BIGINT, address VARCHAR(30), INDEX user_id_idx (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = INNODB",
            "ALTER TABLE ticket_1604__email_adresses ADD CONSTRAINT ticket_1604__email_adresses_user_id_ticket_1604__user_id FOREIGN KEY (user_id) REFERENCES ticket_1604__user(id)"
        );
        
        $this->assertEqual($sql, $def);
    }
}
    
class Ticket_1604_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 30);
        
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1604_EmailAdresses as emailAdresses', array('local' => 'id', 'foreign' => 'userId', "onDelete" => "CASCADE")); 
    }
}

class Ticket_1604_EmailAdresses extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id as userId', 'integer');
        $this->hasColumn('address', 'string', 30);
        
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }
    
    public function setUp()
    {
        $this->hasOne('Ticket_1604_User as user', array('local' => 'userId', 'foreign' => 'id')); 
    }
}
