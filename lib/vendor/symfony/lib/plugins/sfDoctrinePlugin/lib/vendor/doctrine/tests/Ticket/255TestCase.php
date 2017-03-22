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
 * Doctrine_Ticket_255_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_255_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_255_User';
        parent::prepareTables();
    }

    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, true);
        $user = new Ticket_255_User();
        $user->username = 'jwage';
        $user->email_address = 'jonwage@gmail.com';
        $user->password = 'changeme';
        $user->save();

        try {
            $user = new Ticket_255_User();
            $user->username = 'jwage';
            $user->email_address = 'jonwage@gmail.com';
            $user->password = 'changeme';
            $user->save();
            $this->fail();
        } catch (Exception $e) {
            $this->pass();
        }

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, false);
    }

    public function testTest2()
    {
        $dbh = new Doctrine_Adapter_Mock('mysql');
        $conn = Doctrine_Manager::connection($dbh);
        $sql = $conn->export->exportClassesSql(array('Ticket_255_User'));

        $this->assertEqual($sql[0], 'CREATE TABLE ticket_255__user (id BIGINT AUTO_INCREMENT, username VARCHAR(255), email_address VARCHAR(255), password VARCHAR(255), UNIQUE INDEX username_email_address_unqidx_idx (username, email_address), PRIMARY KEY(id)) ENGINE = INNODB');
    }
}

class Ticket_255_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('email_address', 'string', 255);
        $this->hasColumn('password', 'string', 255);

        $this->unique(array('username', 'email_address'));
    }
}