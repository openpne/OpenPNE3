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
 * Doctrine_Export_Mssql_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Export_Mssql_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    { }
    public function prepareData()
    { }

    public function testAlterTableName()
    {
        $this->export->alterTable('user', array(
            'name' => 'userlist'
        ));
        
        $this->assertEqual($this->adapter->pop(), "EXECUTE sp_RENAME '[user]', 'userlist';");
}
    public function testAlterTableRename()
    {
        $this->export->alterTable('user', array(
            'rename' => array(
                'sex' => array(
                    'name' => 'gender',
                    'definition' => array(
                        'type' => 'text',
                        'length' => 1,
                        'default' => 'M',
                    ),
                )
            )
        ));

        $this->assertEqual($this->adapter->pop(), "EXECUTE sp_RENAME '[user].[sex]', 'gender', 'COLUMN';");
    }
    public function testAlterTableRemove()
    {
        $this->export->alterTable('user', array(
            'remove' => array(
                'file_limit' => array(),
                'time_limit' => array(),
            )
        ));

        $this->assertEqual($this->adapter->pop(), "ALTER TABLE user DROP COLUMN file_limit, time_limit;");
    }
    public function testAlterTableChange()
    {
        $this->export->alterTable('user', array(
            'change' => array(
                'name' => array(
                    'length' => '20',
                    'definition' => array(
                        'type' => 'text',
                        'length' => '20',
                    ),
                ),
            )
        ));

        $this->assertEqual($this->adapter->pop(), "ALTER TABLE user ALTER COLUMN name VARCHAR(20) NULL;");
    }
    public function testAlterTableThrowsExceptionWithoutValidTableName()
    {
        try {
            $this->export->alterTable(0, array(), array());

            $this->fail();
        } catch(Doctrine_Export_Exception $e) {
            $this->pass();
        }
    }
    public function testAlterTableMultipleColumnAlterationsRequireMultipleAlterTableQueries()
    {
        $this->export->alterTable('user', array(
            'change' => array(
                'name' => array(
                    'length' => '20',
                    'definition' => array(
                        'type' => 'text',
                        'length' => '20',
                    ),
                ),
                'name2' => array(
                    'length' => '20',
                    'definition' => array(
                        'type' => 'text',
                        'length' => '20',
                    ),
                ),
            )
        ));

        $this->assertEqual($this->adapter->pop(), 'ALTER TABLE user ALTER COLUMN name VARCHAR(20) NULL; ALTER TABLE user ALTER COLUMN name2 VARCHAR(20) NULL;');
    }
    public function testCreateTableExecutesSql()
    {
        $name = 'mytable';

        $fields  = array('id' => array('type' => 'integer', 'unsigned' => 1));
        

        $this->export->createTable($name, $fields);

        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (id BIGINT NULL)');
    }
    public function testCreateTableSupportsMultiplePks()
    {
        $name = 'mytable';
        $fields  = array('name' => array('type' => 'char', 'length' => 10, 'notnull' => true),
                         'type' => array('type' => 'integer', 'length' => 3, 'notnull' => true));

        $options = array('primary' => array('name', 'type'));
        $this->export->createTable($name, $fields, $options);

        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (name CHAR(10) NOT NULL, type INT NOT NULL, PRIMARY KEY([name], [type]))');
    }
    public function testCreateTableSupportsAutoincPks()
    {
        $name = 'mytable';

        $fields  = array('id' => array('type' => 'integer', 'notnull' => true, 'unsigned' => 1, 'autoincrement' => true));
        $options = array('primary' => array('id'));

        $this->export->createTable($name, $fields, $options);

        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (id BIGINT NOT NULL identity, PRIMARY KEY([id]))');
    }
    public function testCreateTableSupportsForeignKeys()
    {
        $name = 'mytable';

        $fields = array('id' => array('type' => 'boolean', 'primary' => true, 'notnull' => true),
                        'foreignKey' => array('type' => 'integer')
                        );
        $options = array('foreignKeys' => array(array('local' => 'foreignKey',
                                                      'foreign' => 'id',
                                                      'foreignTable' => 'sometable'))
                         );


        $sql = $this->export->createTableSql($name, $fields, $options);

        $this->assertEqual(count($sql), 2);
        $this->assertEqual($sql[0], 'CREATE TABLE mytable (id BIT NOT NULL, foreignKey INT NULL, PRIMARY KEY([id]))');
        $this->assertEqual($sql[1], 'ALTER TABLE [mytable] ADD FOREIGN KEY ([foreignKey]) REFERENCES [sometable]([id])');
    }
    public function testCreateDatabaseExecutesSql()
    {
        $this->export->createDatabase('db');
        
        $this->assertEqual($this->adapter->pop(), 'CREATE DATABASE db');
    }
    public function testDropDatabaseExecutesSql()
    {
        $this->export->dropDatabase('db');

        $this->assertEqual($this->adapter->pop(), 'DROP DATABASE db');
    }
    public function testDropIndexExecutesSql()
    {
        $this->export->dropIndex('sometable', 'relevancy');

        $this->assertEqual($this->adapter->pop(), 'DROP INDEX [relevancy_idx] ON [sometable]');
    }
    public function testCreateTableSupportsIndexesUsingSingleFieldString()
    {
        $fields  = array('id' => array('type' => 'integer', 'unsigned' => 1, 'autoincrement' => true, 'unique' => true, 'notnull' => true),
                         'name' => array('type' => 'string', 'length' => 4),
                         );

        $options = array('primary' => array('id'),
                         'indexes' => array('myindex' => array(
                                                    'fields' => array('name')))
                         );

        $this->export->createTable('sometable', $fields, $options);
        $this->assertEqual($this->adapter->pop(), 'CREATE INDEX [myindex] ON [sometable] ([name])');
        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE sometable (id BIGINT NOT NULL identity, name VARCHAR(4) NULL, PRIMARY KEY([id]))');
    }
    public function testCreateTableSupportsCompoundForeignKeys()
    {
        $name = 'mytable';

        $fields = array('id' => array('type' => 'boolean', 'primary' => true, 'notnull' => true),
                        'lang' => array('type' => 'integer', 'primary' => true, 'notnull' => true)
                        );
        $options = array('foreignKeys' => array(array('local' => array ('id', 'lang' ),
                                                      'foreign' => array ('id', 'lang'),
                                                      'foreignTable' => 'sometable'))
                         );

        $sql = $this->export->createTableSql($name, $fields, $options);
        
        $this->assertEqual(count($sql), 2);
        $this->assertEqual($sql[0], 'CREATE TABLE mytable (id BIT NOT NULL, lang INT NOT NULL, PRIMARY KEY([id], [lang]))');
        $this->assertEqual($sql[1], 'ALTER TABLE [mytable] ADD FOREIGN KEY ([id], [lang]) REFERENCES [sometable]([id], [lang])');
    }
}
