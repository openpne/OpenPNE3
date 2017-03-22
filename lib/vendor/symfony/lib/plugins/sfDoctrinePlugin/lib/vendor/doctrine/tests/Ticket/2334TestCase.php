<?php
/*
 *    $Id$
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
 * Ticket_2334_TestMSSQLUnsignedInt
 *
 * @package         Doctrine
 * @author          Daniel Cousineau <dcousineau@gmail.com>
 * @license         http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category        Object Relational Mapping
 * @link            www.doctrine-project.org
 * @since           1.0
 * @version         $Revision$
 */
class Doctrine_Ticket_2334_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_2334_TestMSSQLUnsignedInt';
        parent::prepareTables();
    }
    public function testMSSQLUnsignedInt()
    {
        $dbh = new Doctrine_Adapter_Mock('mssql');

        $conn = Doctrine_Manager::getInstance()->connection($dbh, 'mssql');

        list($sql) = $conn->export->exportSortedClassesSql(array('Ticket_2334_TestMSSQLUnsignedInt'), false);

        $this->assertEqual($sql, 'CREATE TABLE test_string_length (id INT NOT NULL identity, test_int BIGINT NULL, PRIMARY KEY([id]))');

        unset($conn);
        unset($dbh);
    }
}

class Ticket_2334_TestMSSQLUnsignedInt extends Doctrine_Record
{
        public function setTableDefinition()
        {
                $this->setTableName('test_string_length');
                $this->hasColumn('test_int', 'int', null, array('unsigned' => true));
        }

        public function setUp()
        {
            parent::setUp();
        }
}