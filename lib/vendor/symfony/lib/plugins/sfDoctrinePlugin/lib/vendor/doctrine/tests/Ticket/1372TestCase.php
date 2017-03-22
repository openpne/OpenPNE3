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
 * Doctrine_Ticket_1372_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1372_TestCase extends Doctrine_UnitTestCase 
{
    /* Test array of SQL queries to ensure uniqueness of queries */
    public function testExportSql()
    {
        $drivers = array('mysql',
                         'sqlite',
                         'pgsql',
                         'oracle',
                         'mssql');

        foreach ($drivers as $driver)
        {
            $dbh = new Doctrine_Adapter_Mock($driver);

            $conn = Doctrine_Manager::getInstance()->connection($dbh, $driver);

            $sql = $conn->export->exportSortedClassesSql(array('Ticket_1372_ParentClass', 'Ticket_1372_Child_1', 'Ticket_1372_Child_2'), false);

            $this->assertEqual($sql, array_unique($sql));

            $conn = null;

            $dbh = null;
        }
    }
}

/*
# schema in YAML format
---
Ticket_1372_ParentClass:
  columns:
    id:
      primary: true
      notnull: true
      autoincrement: true
      type: integer(4)
    type:
      unique: true
      notnull: true
      type: integer
    value_1:
      type: integer(4)
    value_2:
      type: integer(4)
  indexes:
    type_idx:
      fields: [type]
    values_idx:
      fields: [value_1, value_2]
  attributes:
    export:   [all]

Ticket_1372_Child_1:
  inheritance:
    extends: Ticket_1372_ParentClass
    type: column_aggregation
    keyField: type
    keyValue: 1

Ticket_1372_Child_2:
  inheritance:
    extends: Ticket_1372_ParentClass
    type: column_aggregation
    keyField: type
    keyValue: 2
*/

class Ticket_1372_ParentClass extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('parent_class');

        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true, 'type' => 'integer', 'length' => '4'));
        $this->hasColumn('type', 'integer', null, array('unique' => true, 'notnull' => true, 'type' => 'integer'));
        $this->hasColumn('value_1', 'integer', 4, array('type' => 'integer', 'length' => '4'));
        $this->hasColumn('value_2', 'integer', 4, array('type' => 'integer', 'length' => '4'));

        $this->index('type_idx', array('fields' => array(0 => 'type')));
        $this->index('values_idx', array('fields' => array(0 => 'value_1', 1 => 'value_2')));

        $this->setAttribute(Doctrine_Core::ATTR_EXPORT, Doctrine_Core::EXPORT_ALL);

        $this->setSubClasses(array('Child_1' => array('type' => 1), 'Child_2' => array('type' => 2)));
    }
}

class Ticket_1372_Child_1 extends Ticket_1372_ParentClass
{
}

class Ticket_1372_Child_2 extends Ticket_1372_ParentClass
{
}
