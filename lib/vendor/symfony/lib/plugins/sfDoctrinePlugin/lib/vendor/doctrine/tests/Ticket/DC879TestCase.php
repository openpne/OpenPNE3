<?php
/*
* $Id$
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
* Doctrine_Ticket_DC879_TestCase
*
* @package Doctrine
* @author Enrico Stahn <mail@enricostahn.com>
* @license http://www.opensource.org/licenses/lgpl-license.php LGPL
* @category Object Relational Mapping
* @link www.doctrine-project.org
* @since 1.0
* @version $Revision$
*/
class Doctrine_Ticket_DC879_TestCase extends Doctrine_UnitTestCase
{
    private $sqlStackCounter = 0;

    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC879_Model';
        parent::prepareTables();
    }

    public function testInit()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mssql');
        $this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh, 'DC879');
    }

    public function testWhere()
    {
        Doctrine_Core::getTable('Ticket_DC879_Model')
          ->createQuery('t')
          ->where('foo = DATE_PART("month", foo)')
          ->execute();

        $expected = "SELECT [t].[model_id] AS [t__model_id], [t].[username] AS [t__username], [t].[password] AS [t__password], [t].[foo] AS [t__foo] FROM [ticket__d_c879__model] [t] WHERE ([t].[foo] = DATEPART(month, [t].[foo]))";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }
}

class Ticket_DC879_Model extends Doctrine_Record
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
        $this->hasColumn('foo', 'timestamp');
    }
}
