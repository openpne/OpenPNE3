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
 * Doctrine_Ticket_668_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_668_TestCase extends Doctrine_UnitTestCase {

    public function prepareTables() {
      $this->tables = array();
      $this->tables[] = 'T668_User';
      parent::prepareTables();
    }


    public function prepareData() {}


    public function testTicket()
    {
        $query = Doctrine_Query::create()
                ->select('u.id')
                ->from('T668_User u')
                ->where("u.name LIKE '%foo OR bar%'");
        $this->assertEqual("SELECT u.id FROM T668_User u WHERE u.name LIKE '%foo OR bar%'", $query->getDql());
        $this->assertEqual($query->getSqlQuery(), "SELECT t.id AS t__id FROM t668_user t WHERE (t.name LIKE '%foo OR bar%')");
    }
}


class T668_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('t668_user');
        $this->hasColumn('id', 'integer', 3, array('autoincrement' => true, 'unsigned' => true, 'primary' => true, 'notnull' => true));
        $this->hasColumn('name', 'string', 100);
    }
}