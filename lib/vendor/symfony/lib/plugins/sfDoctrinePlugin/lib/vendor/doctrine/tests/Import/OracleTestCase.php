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
 * Doctrine_Import_Oracle_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Import_Oracle_TestCase extends Doctrine_UnitTestCase 
{
    public function testListSequencesExecutesSql()
    {
        $this->conn->setAttribute(Doctrine_Core::ATTR_EMULATE_DATABASE, true);

        $this->import->listSequences('table');
        
        $this->assertEqual($this->adapter->pop(), "SELECT sequence_name FROM sys.user_sequences");
    }
    public function testListTableColumnsExecutesSql()
    {
        $this->import->listTableColumns('table');

        $q = "SELECT tc.column_name, data_type,
CASE WHEN data_type = 'NUMBER' THEN data_precision ELSE data_length END AS data_length,
nullable, data_default, data_scale, data_precision, pk.primary
FROM all_tab_columns tc
LEFT JOIN (
 select 'primary' primary, cc.table_name, cc.column_name from all_constraints cons
 join all_cons_columns cc on cons.constraint_name = cc.constraint_name
 where cons.constraint_type = 'P'
) pk ON pk.column_name = tc.column_name and pk.table_name = tc.table_name
WHERE tc.table_name = :tableName ORDER BY column_id";

        $this->assertEqual($this->adapter->pop(), $q);
    }
    public function testListTableIndexesExecutesSql()
    {
        $this->import->listTableIndexes('table');

        $q = 'SELECT index_name name FROM user_indexes'
           . " WHERE table_name = 'table' OR table_name = 'TABLE'"
           . " AND generated = 'N'";

        $this->assertEqual($this->adapter->pop(), $q);
    }
    public function testListTablesExecutesSql()
    {
        $this->import->listTables();
        
        $q = "SELECT * FROM user_objects WHERE object_type = 'TABLE' and object_name in (select table_name from user_tables)";
        $this->assertEqual($this->adapter->pop(), $q);
    }
    public function testListDatabasesExecutesSql()
    {
        $this->import->listDatabases();
        
        $q = 'SELECT username FROM sys.user_users';
        $this->assertEqual($this->adapter->pop(), $q);
    }
    public function testListUsersExecutesSql()
    {
        $this->import->listUsers();
        
        $q = 'SELECT username FROM sys.all_users';
        $this->assertEqual($this->adapter->pop(), $q);
    }
    public function testListViewsExecutesSql()
    {
        $this->import->listViews();
        
        $q = 'SELECT view_name FROM sys.user_views';
        $this->assertEqual($this->adapter->pop(), $q);
    }
    public function testListFunctionsExecutesSql()
    {
        $this->import->listFunctions();
        
        $q = "SELECT name FROM sys.user_source WHERE line = 1 AND type = 'FUNCTION'";
        $this->assertEqual($this->adapter->pop(), $q);
    }
    public function testListTableConstraintsExecutesSql()
    {
        $this->import->listTableConstraints('table');
        
        $q = "SELECT index_name name FROM user_constraints"
           . " WHERE table_name = 'table' OR table_name = 'TABLE'";

        $this->assertEqual($this->adapter->pop(), $q);
    }
}
