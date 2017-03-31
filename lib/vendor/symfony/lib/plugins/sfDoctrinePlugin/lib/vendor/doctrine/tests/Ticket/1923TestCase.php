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
 * Doctrine_Ticket_1923_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1923_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1923_User';
        $this->tables[] = 'Ticket_1923_User2';

        parent::prepareTables();
    }

    public function testTest()
    {
        $sql = Doctrine_Core::generateSqlFromArray(array('Ticket_1923_User'));
        $this->assertEqual($sql[1], 'CREATE INDEX username_idx ON ticket_1923__user (login)');

        $sql = Doctrine_Core::generateSqlFromArray(array('Ticket_1923_User2'));
        $this->assertEqual($sql[1], 'CREATE INDEX username2_idx ON ticket_1923__user2 (login DESC)');
    }
}

class Ticket_1923_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('login as username', 'string', 255);
        $this->hasColumn('password', 'string', 255);

        $this->index('username', array('fields' => array('username')));
    }
}

class Ticket_1923_User2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('login as username', 'string', 255);
        $this->hasColumn('password', 'string', 255);

        $this->index('username2', array('fields' => array('username' => array('sorting' => 'DESC'))));
    }
}