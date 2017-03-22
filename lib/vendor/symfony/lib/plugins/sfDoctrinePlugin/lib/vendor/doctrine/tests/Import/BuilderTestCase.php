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
 * Doctrine_Import_Builder_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Import_Builder_TestCase extends Doctrine_UnitTestCase 
{
    public function testInheritanceGeneration()
    {
        $path = dirname(__FILE__) . '/import_builder_test';

        $import = new Doctrine_Import_Schema();
        $import->setOption('generateTableClasses', true);
        $import->importSchema('schema.yml', 'yml', $path);

        $models = Doctrine_Core::loadModels($path, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);

        $schemaTestInheritanceParent = new ReflectionClass('SchemaTestInheritanceParent');
        $schemaTestInheritanceChild1 = new ReflectionClass('SchemaTestInheritanceChild1');
        $schemaTestInheritanceChild2 = new ReflectionClass('SchemaTestInheritanceChild2');

        $schemaTestInheritanceParentTable = new ReflectionClass('SchemaTestInheritanceParentTable');
        $schemaTestInheritanceChild1Table = new ReflectionClass('SchemaTestInheritanceChild1Table');
        $schemaTestInheritanceChild2Table = new ReflectionClass('SchemaTestInheritanceChild2Table');

        $this->assertTrue($schemaTestInheritanceParent->isSubClassOf('Doctrine_Record'));
        $this->assertTrue($schemaTestInheritanceParent->isSubClassOf('BaseSchemaTestInheritanceParent'));
        $this->assertTrue($schemaTestInheritanceParent->isSubClassOf('PackageSchemaTestInheritanceParent'));
        $this->assertTrue($schemaTestInheritanceChild1->isSubClassOf('BaseSchemaTestInheritanceChild1'));
        $this->assertTrue($schemaTestInheritanceChild2->isSubClassOf('BaseSchemaTestInheritanceChild2'));
        
        $this->assertTrue($schemaTestInheritanceChild1->isSubClassOf('SchemaTestInheritanceParent'));
        $this->assertTrue($schemaTestInheritanceChild1->isSubClassOf('BaseSchemaTestInheritanceParent'));
        
        $this->assertTrue($schemaTestInheritanceChild2->isSubClassOf('SchemaTestInheritanceParent'));
        $this->assertTrue($schemaTestInheritanceChild2->isSubClassOf('BaseSchemaTestInheritanceParent'));
        $this->assertTrue($schemaTestInheritanceChild2->isSubClassOf('SchemaTestInheritanceChild1'));
        $this->assertTrue($schemaTestInheritanceChild2->isSubClassOf('BaseSchemaTestInheritanceChild1'));
        $this->assertTrue($schemaTestInheritanceChild2->isSubClassOf('PackageSchemaTestInheritanceParent'));

        $this->assertTrue($schemaTestInheritanceParentTable->isSubClassOf('Doctrine_Table'));
        $this->assertTrue($schemaTestInheritanceChild1Table->isSubClassOf('SchemaTestInheritanceParentTable'));
        $this->assertTrue($schemaTestInheritanceChild1Table->isSubClassOf('PackageSchemaTestInheritanceParentTable'));

        $this->assertTrue($schemaTestInheritanceChild2Table->isSubClassOf('SchemaTestInheritanceParentTable'));
        $this->assertTrue($schemaTestInheritanceChild2Table->isSubClassOf('PackageSchemaTestInheritanceParentTable'));
        $this->assertTrue($schemaTestInheritanceChild2Table->isSubClassOf('SchemaTestInheritanceChild1Table'));
        $this->assertTrue($schemaTestInheritanceChild2Table->isSubClassOf('PackageSchemaTestInheritanceChild1Table'));

        # Simple Inheritance
        $schemaTestSimpleInheritanceParent = new ReflectionClass('SchemaTestSimpleInheritanceParent');
        $schemaTestSimpleInheritanceChild = new ReflectionClass('SchemaTestSimpleInheritanceChild');

        $this->assertTrue($schemaTestSimpleInheritanceParent->hasMethod('setTableDefinition'));
        $this->assertTrue($schemaTestSimpleInheritanceChild->isSubClassOf('SchemaTestSimpleInheritanceParent'));

        # Class Table Inheritance
        $schemaTestClassTableInheritanceParent = new ReflectionClass('SchemaTestClassTableInheritanceParent');
        $schemaTestClassTableInheritanceChild = new ReflectionClass('SchemaTestClassTableInheritanceChild');

        # Concrete Inheritance
        $schemaTestConcreteInheritanceParent = new ReflectionClass('SchemaTestConcreteInheritanceParent');
        $schemaTestConcreteInheritanceChild = new ReflectionClass('SchemaTestConcreteInheritanceChild');

        # Column Aggregation Inheritance
        $schemaTestColumnAggregationInheritanceParent = new ReflectionClass('SchemaTestColumnAggregationInheritanceParent');
        $schemaTestColumnAggregationInheritanceChild = new ReflectionClass('SchemaTestColumnAggregationInheritanceChild');

        $sql = Doctrine_Core::generateSqlFromArray(array('SchemaTestSimpleInheritanceParent', 'SchemaTestSimpleInheritanceChild'));
        $this->assertEqual(count($sql), 1);
        $this->assertEqual($sql[0], 'CREATE TABLE schema_test_simple_inheritance_parent (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255), description VARCHAR(255))');

        $sql = Doctrine_Core::generateSqlFromArray(array('SchemaTestClassTableInheritanceParent', 'SchemaTestClassTableInheritanceChild'));
        $this->assertEqual(count($sql), 2);
        $this->assertEqual($sql[0], 'CREATE TABLE schema_test_class_table_inheritance_parent (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255))');
        $this->assertEqual($sql[1], 'CREATE TABLE schema_test_class_table_inheritance_child (id INTEGER, title VARCHAR(255), description VARCHAR(255), PRIMARY KEY(id))');

        $sql = Doctrine_Core::generateSqlFromArray(array('SchemaTestConcreteInheritanceParent', 'SchemaTestConcreteInheritanceChild'));
        $this->assertEqual(count($sql), 2);
        $this->assertEqual($sql[0], 'CREATE TABLE schema_test_concrete_inheritance_parent (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255))');
        $this->assertEqual($sql[1], 'CREATE TABLE schema_test_concrete_inheritance_child (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255), title VARCHAR(255), description VARCHAR(255))');

        $sql = Doctrine_Core::generateSqlFromArray(array('SchemaTestColumnAggregationInheritanceParent', 'SchemaTestColumnAggregationInheritanceChild'));
        $this->assertEqual(count($sql), 2);
        $this->assertEqual($sql[0], 'CREATE TABLE schema_test_column_aggregation_inheritance_parent (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255), type VARCHAR(255), title VARCHAR(255), description VARCHAR(255))');
        $this->assertEqual($sql[1], 'CREATE INDEX schema_test_column_aggregation_inheritance_parent_type_idx ON schema_test_column_aggregation_inheritance_parent (type)');

        Doctrine_Lib::removeDirectories($path);
    }

    public function testBaseTableClass()
    {
        $builder = new Doctrine_Import_Builder();
        $builder->setOption('baseTableClassName', 'MyBaseTable');
        $class = $builder->buildTableClassDefinition('MyTestTable', array('className' => 'MyTest'));
        $this->assertTrue(strpos($class, 'class MyTestTable extends MyBaseTable'));
    }
}