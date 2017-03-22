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
 * Doctrine_Export_Oracle_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Export_Oracle_TestCase extends Doctrine_UnitTestCase 
{
    public function testCreateSequenceExecutesSql() 
    {
        $sequenceName = 'sequence';
        $start = 1;
        $query = 'CREATE SEQUENCE ' . $sequenceName . '_seq START WITH ' . $start . ' INCREMENT BY 1 NOCACHE';

        $this->export->createSequence($sequenceName, $start);
        
        $this->assertEqual($this->adapter->pop(), $query);
    }

    public function testDropSequenceExecutesSql() 
    {
        $sequenceName = 'sequence';

        $query = 'DROP SEQUENCE ' . $sequenceName;

        $this->export->dropSequence($sequenceName);
        
        $this->assertEqual($this->adapter->pop(), $query . '_seq');
    }
    public function testCreateTableExecutesSql() 
    {
        $name = 'mytable';
        
        $fields  = array('id' => array('type' => 'integer'));
        $options = array('type' => 'MYISAM');
        
        $this->export->createTable($name, $fields);

        $this->assertEqual($this->adapter->pop(), 'COMMIT');
        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (id INTEGER)');
        $this->assertEqual($this->adapter->pop(), 'BEGIN TRANSACTION');
    }
    public function testCreateTableSupportsDefaultAttribute() 
    {
        $name = 'mytable';
        $fields  = array('name' => array('type' => 'char', 'length' => 10, 'default' => 'def'),
                         'type' => array('type' => 'integer', 'length' => 3, 'default' => 12)
                         );
                         
        $options = array('primary' => array('name', 'type'));
        $this->export->createTable($name, $fields, $options);
        

        $this->assertEqual($this->adapter->pop(), 'COMMIT');
        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (name CHAR(10) DEFAULT \'def\', type NUMBER(8) DEFAULT 12, PRIMARY KEY(name, type))');
        $this->assertEqual($this->adapter->pop(), 'BEGIN TRANSACTION');
    }
    
    public function testCreateTableWithOwnParams()
    {
        $this->conn->setParam('char_unit', 'CHAR');
        $this->conn->setParam('varchar2_max_length', 1000);
        
        $fields = array(
            'type' => array('type' => 'char', 'length' => 10, 'default' => 'admin'),
            'name' => array('type' => 'string', 'length' => 1000),
            'about' => array('type' => 'string', 'length' => 1001, 'default' => 'def'),
        );
        
        $sql = $this->export->createTableSql('mytable', $fields);
        $this->assertEqual($sql[0], "CREATE TABLE mytable (type CHAR(10 CHAR) DEFAULT 'admin', name VARCHAR2(1000 CHAR), about CLOB DEFAULT 'def')");
        
        $this->conn->setParam('char_unit', null);
        $this->conn->setParam('varchar2_max_length', 4000);
    }
    
    public function testCreateTableSupportsMultiplePks() 
    {
        $name = 'mytable';
        $fields  = array('name' => array('type' => 'char', 'length' => 10),
                         'type' => array('type' => 'integer', 'length' => 3));
                         
        $options = array('primary' => array('name', 'type'));
        $this->export->createTable($name, $fields, $options);
        

        $this->assertEqual($this->adapter->pop(), 'COMMIT');
        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (name CHAR(10), type NUMBER(8), PRIMARY KEY(name, type))');
        $this->assertEqual($this->adapter->pop(), 'BEGIN TRANSACTION');
    }
    public function testCreateTableSupportsAutoincPks() 
    {
        $name = 'mytable';
        
        $fields  = array('id' => array('type' => 'integer', 'autoincrement' => true));


        $this->export->createTable($name, $fields);

        $this->assertEqual($this->adapter->pop(), 'COMMIT');
        $this->assertEqual(substr($this->adapter->pop(), 0, 14), 'CREATE TRIGGER');
        $this->assertEqual($this->adapter->pop(), 'CREATE SEQUENCE MYTABLE_seq START WITH 1 INCREMENT BY 1 NOCACHE');  
        $this->assertEqual(substr($this->adapter->pop(), 0, 7), "DECLARE");
        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (id INTEGER)');
        $this->assertEqual($this->adapter->pop(), 'BEGIN TRANSACTION');
    }

    public function testCreateTableSupportsCharType() 
    {
        $name = 'mytable';
        
        $fields  = array('id' => array('type' => 'char', 'length' => 3));

        $this->export->createTable($name, $fields);

        $this->adapter->pop();
        $this->assertEqual($this->adapter->pop(), 'CREATE TABLE mytable (id CHAR(3))');
    }
    public function testCreateTableSupportsUniqueConstraint()
    {
        $fields  = array('id' => array('type' => 'integer', 'unsigned' => 1, 'autoincrement' => true, 'unique' => true),
                         'name' => array('type' => 'string', 'length' => 4),
                         );

        $options = array('primary' => array('id'),
                         );

        $sql = $this->export->createTableSql('sometable', $fields, $options);

        $this->assertEqual($sql[0], 'CREATE TABLE sometable (id INTEGER UNIQUE, name VARCHAR2(4), PRIMARY KEY(id))');
    }
    public function testCreateTableSupportsIndexes()
    {
        $fields  = array('id' => array('type' => 'integer', 'unsigned' => 1, 'autoincrement' => true, 'unique' => true),
                         'name' => array('type' => 'string', 'length' => 4),
                         );

        $options = array('primary' => array('id'),
                         'indexes' => array('myindex' => array('fields' => array('id', 'name')))
                         );

        $sql = $this->export->createTableSql('sometable', $fields, $options);

        $this->assertEqual($sql[0], 'CREATE TABLE sometable (id INTEGER UNIQUE, name VARCHAR2(4), PRIMARY KEY(id))');
        $this->assertEqual($sql[4], 'CREATE INDEX myindex ON sometable (id, name)');
        
        $fields = array('id'=> array('type'=>'integer', 'unisgned' => 1, 'autoincrement' => true),
                        'name' => array('type' => 'string', 'length' => 4),
                        'category' => array('type'=>'integer', 'length'=>2),
                        );
        $options = array('primary' => array('id'),
                         'indexes' => array('category_index' => array('fields'=> array('category')), 'unique_index' => array('type'=> 'unique', 'fields'=> array('id', 'name'))),
                         );
        $sql = $this->export->createTableSql('sometable', $fields, $options);
        $this->assertEqual($sql[0], 'CREATE TABLE sometable (id INTEGER, name VARCHAR2(4), category NUMBER(5), PRIMARY KEY(id), CONSTRAINT unique_index UNIQUE (id, name))');
        $this->assertEqual($sql[4], 'CREATE INDEX category_index ON sometable (category)');
    }
    
    public function testIdentifierQuoting()
    {
    	$this->conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
        
        $fields = array('id' => array('type' => 'integer', 'unsigned' => 1, 'autoincrement' => true),
                        'name' => array('type' => 'string', 'length' => 4),
                        );
        $options = array('primary' => array('id'),
                         'indexes' => array('myindex' => array('fields' => array('id', 'name')))
                         );
                         
        $sql  = $this->export->createTableSql('sometable', $fields, $options);
        $this->assertEqual($sql[0], 'CREATE TABLE "sometable" ("id" INTEGER, "name" VARCHAR2(4), PRIMARY KEY("id"))');
        $this->assertEqual($sql[1], 'DECLARE
  constraints_Count NUMBER;
BEGIN
  SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count FROM USER_CONSTRAINTS WHERE TABLE_NAME = \'sometable\' AND CONSTRAINT_TYPE = \'P\';
  IF constraints_Count = 0 THEN
    EXECUTE IMMEDIATE \'ALTER TABLE "sometable" ADD CONSTRAINT "sometable_AI_PK_idx" PRIMARY KEY ("id")\';
  END IF;
END;');
        $this->assertEqual($sql[2], 'CREATE SEQUENCE "sometable_seq" START WITH 1 INCREMENT BY 1 NOCACHE');
        $this->assertEqual($sql[3], 'CREATE TRIGGER "sometable_AI_PK"
   BEFORE INSERT
   ON "sometable"
   FOR EACH ROW
DECLARE
   last_Sequence NUMBER;
   last_InsertID NUMBER;
BEGIN
   IF (:NEW."id" IS NULL OR :NEW."id" = 0) THEN
      SELECT "sometable_seq".NEXTVAL INTO :NEW."id" FROM DUAL;
   ELSE
      SELECT NVL(Last_Number, 0) INTO last_Sequence
        FROM User_Sequences
       WHERE UPPER(Sequence_Name) = UPPER(\'sometable_seq\');
      SELECT :NEW."id" INTO last_InsertID FROM DUAL;
      WHILE (last_InsertID > last_Sequence) LOOP
         SELECT "sometable_seq".NEXTVAL INTO last_Sequence FROM DUAL;
      END LOOP;
   END IF;
END;');
		$this->assertEqual($sql[4], 'CREATE INDEX "myindex" ON "sometable" ("id", "name")');
		
		// test dropping sequence		
		$sql = $this->export->dropSequenceSql('sometable');
		$this->assertEqual($sql, 'DROP SEQUENCE "sometable_seq"');
		
        $this->conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, false);
    }
}
