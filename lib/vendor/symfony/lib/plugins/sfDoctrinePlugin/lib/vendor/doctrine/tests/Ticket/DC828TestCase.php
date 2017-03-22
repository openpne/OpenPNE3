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
 * Doctrine_Ticket_DC828_TestCase
 *
 * @package     Doctrine
 * @author      Enrico Stahn <mail@enricostahn.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC828_TestCase extends Doctrine_UnitTestCase
{
    private $sqlStackCounter = 0;

    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC828_Model';
        parent::prepareTables();
    }

    public function testInit()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mssql');
        $this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh, 'DC828');
    }

    public function testLimit()
    {
        Doctrine_Query::create()->select()->from('Ticket_DC828_Model')->limit(10)->execute();

        $expected = 'SELECT TOP 10 [t].[model_id] AS [t__model_id], [t].[username] AS [t__username], [t].[password] AS [t__password] FROM [ticket__d_c828__model] [t]';
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }

    public function testLimitOffsetWithoutOrderBy()
    {
        $exception = false;

        try {
            Doctrine_Query::create()->select()->from('Ticket_DC828_Model')->limit(10)->offset(5)->execute();
        } catch (Doctrine_Connection_Exception $e) {
            $exception = true;
            $this->assertEqual($e->getMessage(), 'OFFSET cannot be used in MSSQL without ORDER BY due to emulation reasons.');
        }

        $this->assertTrue($exception);
    }

    public function testOrderBy()
    {
        Doctrine_Query::create()->select()->from('Ticket_DC828_Model')->orderBy('username')->execute();

        $expected = 'SELECT [t].[model_id] AS [t__model_id], [t].[username] AS [t__username], [t].[password] AS [t__password] FROM [ticket__d_c828__model] [t] ORDER BY [t].[username]';
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }

    public function testOrderByLimit()
    {
        Doctrine_Query::create()->select()->from('Ticket_DC828_Model')->orderBy('username')->limit(10)->execute();

        $expected = 'SELECT TOP 10 [t].[model_id] AS [t__model_id], [t].[username] AS [t__username], [t].[password] AS [t__password] FROM [ticket__d_c828__model] [t] ORDER BY [t].[username]';
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }

    public function testOrderByLimitOffset()
    {
        Doctrine_Query::create()->select()->from('Ticket_DC828_Model')->orderBy('username')->limit(10)->offset(5)->execute();

        $expected = 'SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY [t].[username]) AS [DOCTRINE_ROWNUM], [t].[model_id] AS [t__model_id], [t].[username] AS [t__username], [t].[password] AS [t__password] FROM [ticket__d_c828__model] [t] ) AS [doctrine_tbl] WHERE [DOCTRINE_ROWNUM] BETWEEN 6 AND 15';
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }

    public function testMultipleOrderByFields()
    {
        Doctrine_Query::create()->select()->from('Ticket_DC828_Model')->orderBy('username, password')->limit(10)->offset(5)->execute();

        $expected = 'SELECT * FROM (SELECT ROW_NUMBER() OVER (ORDER BY [t].[username], [t].[password]) AS [DOCTRINE_ROWNUM], [t].[model_id] AS [t__model_id], [t].[username] AS [t__username], [t].[password] AS [t__password] FROM [ticket__d_c828__model] [t] ) AS [doctrine_tbl] WHERE [DOCTRINE_ROWNUM] BETWEEN 6 AND 15';
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }
}

class Ticket_DC828_Model extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('model_id as id', 'integer', null, array(
            'type' => 'integer',
            'unsigned' => false,
            'primary' => true,
            'autoincrement' => true,
        ));
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }
}
