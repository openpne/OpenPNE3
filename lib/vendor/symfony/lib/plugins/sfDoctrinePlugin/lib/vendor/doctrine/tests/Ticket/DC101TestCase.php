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
 * Doctrine_Ticket_DC101_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC101_TestCase extends Doctrine_UnitTestCase 
{
    public function testTest()
    {
        $dbh = new Doctrine_Adapter_Mock('mysql');

        $conn = Doctrine_Manager::getInstance()->connection($dbh, 'mysql', false);

        $sql = $conn->export->exportSortedClassesSql(array('Ticket_DC101_User', 'Ticket_DC101_Profile'), false);
        $this->assertEqual($sql[2], 'ALTER TABLE ticket__d_c101__profile ADD CONSTRAINT user_id_fk FOREIGN KEY (user_id) REFERENCES ticket__d_c101__user(id)');
    }
}

class Ticket_DC101_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC101_Profile as Profile', array(
            'local' => 'id',
            'foreign' => 'user_id'
        ));
    }
}

class Ticket_DC101_Profile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('user_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC101_User as User', array(
            'local' => 'user_id',
            'foreign' => 'id',
            'foreignKeyName' => 'user_id_fk'
        ));
    }
}