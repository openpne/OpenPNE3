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
 * Doctrine_Ticket_DC841_TestCase
 *
 * @package     Doctrine
 * @author      Enrico Stahn <mail@enricostahn.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC841_TestCase extends Doctrine_UnitTestCase 
{
    private $sqlStackCounter = 0;
    
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC841_Model';
        parent::prepareTables();
    }

    public function testInit()
    {
        $this->dbh = new Doctrine_Adapter_Mock('mssql');
        $this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh, 'DC841');
    }
    
    public function testSelect()
    {
        Doctrine_Core::getTable('Ticket_DC841_Model')
            ->createQuery('t')
            ->where('t.username <> ?', 'foo')
            ->andWhere('t.foo = ?', 't.foo = ?')
            ->andWhere('t.foo <> ?', 'bar')
            ->andWhere('t.foo LIKE ?', 'foo')
            ->execute();

        $expected = "SELECT [t].[id] AS [t__id], [t].[username] AS [t__username], [t].[password] AS [t__password], [t].[foo] AS [t__foo] FROM [ticket__d_c841__model] [t] WHERE ([t].[username] <> 'foo' AND [t].[foo] = 't.foo = ?' AND [t].[foo] <> 'bar' AND [t].[foo] LIKE 'foo')";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }
    
    public function testSelectJoinWith()
    {
        Doctrine_Core::getTable('Ticket_DC841_Model')
            ->createQuery('t')
            ->leftJoin('t.Ticket_DC841_Model WITH t.id = ?', array(30))
            ->where('t.username = ?', 'foo')
            ->execute();

        $expected = "SELECT [t].[id] AS [t__id], [t].[username] AS [t__username], [t].[password] AS [t__password], [t].[foo] AS [t__foo], [t2].[id] AS [t2__id], [t2].[username] AS [t2__username], [t2].[password] AS [t2__password], [t2].[foo] AS [t2__foo] FROM [ticket__d_c841__model] [t] LEFT JOIN [ticket__d_c841__model] [t2] ON [t].[id] = [t2].[id] AND ([t].[id] = 30) WHERE ([t].[username] = 'foo')";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }
    
    public function testSelectWithStaticParameter()
    {
        return; // doesn't work
        Doctrine_Core::getTable('Ticket_DC841_Model')
            ->createQuery('t')
            ->where('t.username = ?', 'foo')
            ->andWhere("t.foo = 't.foo = ?'")
            ->andWhere("t.password = ?", 'test')
            ->execute();

        $expected = "SELECT [t].[id] AS [t__id], [t].[username] AS [t__username], [t].[password] AS [t__password], [t].[foo] AS [t__foo] FROM [ticket__d_c841__model] [t] WHERE ([t].[username] = 'foo' AND [t].[foo] = 't.foo = ?' AND [t].[password] = 'test')";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }
    
    public function testSelectWithIn()
    {
        Doctrine_Core::getTable('Ticket_DC841_Model')
            ->createQuery('t')
            ->whereIn('t.username', array('foo', 'bar'))
            ->execute();

        $expected = "SELECT [t].[id] AS [t__id], [t].[username] AS [t__username], [t].[password] AS [t__password], [t].[foo] AS [t__foo] FROM [ticket__d_c841__model] [t] WHERE ([t].[username] IN ('foo', 'bar'))";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }
    
    public function testInsert()
    {
        try {
          $o = new Ticket_DC841_Model();
          $o->username = 'abc';
          $o->password = 'abc';
          $o->foo = 'abc';
          $o->save();
        } catch (Doctrine_Connection_Exception $e) {
            // Ignore: Couldn't get last insert identifier.
        }

        $this->sqlStackCounter += 1;
        
        $expected = "INSERT INTO [ticket__d_c841__model] ([username], [password], [foo]) VALUES ('abc', 'abc', 'abc')";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }

    public function testUpdate()
    {
        $o = new Ticket_DC841_Model();
        $o->assignIdentifier(33);
        $o->username = 'abc';
        $o->password = 'abc';
        $o->foo = 'abc';
        $o->state(Doctrine_Record::STATE_CLEAN);

        $this->sqlStackCounter += 7;
            
        $o->password = 'foobar';
        $o->save();

        $expected = "UPDATE [ticket__d_c841__model] SET [password] = 'foobar' WHERE id = 33";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }

    public function testDelete()
    {
        $o = new Ticket_DC841_Model();
        $o->assignIdentifier(33);
        $o->username = 'abc';
        $o->password = 'abc';
        $o->foo = 'abc';
        $o->state(Doctrine_Record::STATE_CLEAN);

        $this->sqlStackCounter += 5;
            
        $o->delete();

        $expected = "DELETE FROM [ticket__d_c841__model] WHERE [id] = 33";
        $sql = current(array_slice($this->dbh->getAll(), $this->sqlStackCounter++, 1));

        $this->assertEqual($expected, $sql);
    }
}

class Ticket_DC841_Model extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array(
            'type' => 'integer',
            'unsigned' => false,
            'primary' => true,
            'autoincrement' => true,
        ));
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
        $this->hasColumn('foo', 'string', 255);
    }
    
    public function setUp()
    {
        $this->hasOne('Ticket_DC841_Model', array(
             'local' => 'id',
             'foreign' => 'id'));    
    }
}