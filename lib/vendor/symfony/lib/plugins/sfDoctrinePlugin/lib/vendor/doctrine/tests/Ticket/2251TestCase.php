<?php
/*
 *    $Id: 2229TestCase.php 5871 2009-06-10 08:46:00Z Garfield-fr $
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
 * Doctrine_Ticket_2251_TestCase
 *
 * @package         Doctrine
 * @author          Daniel Cousineau <dcousineau@gmail.com>
 * @license         http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category        Object Relational Mapping
 * @link            www.doctrine-project.org
 * @since           1.0
 * @version         $Revision$
 */
class Doctrine_Ticket_2251_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_2251_TestStringLength';
        parent::prepareTables();
    }

    public function testEmptyStringLengthSQLExport()
    {
        $drivers = array(
            'mysql',
            'sqlite',
            'pgsql',
            'oracle',
            'mssql'
        );
        
        $expected = array(
            'mysql'     => 'CREATE TABLE test_string_length (id BIGINT AUTO_INCREMENT, test_string TEXT, PRIMARY KEY(id)) ENGINE = INNODB',
            'sqlite'    => 'CREATE TABLE test_string_length (id INTEGER PRIMARY KEY AUTOINCREMENT, test_string TEXT)',
            'pgsql'     => 'CREATE TABLE test_string_length (id BIGSERIAL, test_string TEXT, PRIMARY KEY(id))',
            'oracle'    => 'CREATE TABLE test_string_length (id NUMBER(20), test_string CLOB, PRIMARY KEY(id))',
            'mssql'     => 'CREATE TABLE test_string_length (id INT NOT NULL identity, test_string TEXT NULL, PRIMARY KEY([id]))'
        );

        foreach ($drivers as $driver)
        {
            $dbh = new Doctrine_Adapter_Mock($driver);

            $conn = Doctrine_Manager::getInstance()->connection($dbh, $driver);

            list($sql) = $conn->export->exportSortedClassesSql(array('Ticket_2251_TestStringLength'), false);

            $this->assertEqual($sql, $expected[$driver]);

            unset($conn);
            unset($dbh);
        }
    }
}

class Ticket_2251_TestStringLength extends Doctrine_Record
{
    public function setTableDefinition()
    {
            $this->setTableName('test_string_length');
            $this->hasColumn('test_string', 'string');
    }

    public function setUp()
    {
        parent::setUp();
    }
}