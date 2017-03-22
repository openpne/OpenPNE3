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
 * Doctrine_Import_Schema_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Import_Schema_TestCase extends Doctrine_UnitTestCase 
{
    public $buildSchema;
    public $schema;
    
    public function testYmlImport()
    {
        $path = dirname(__FILE__) . '/import_builder_test';
        
        $import = new Doctrine_Import_Schema();
        $import->importSchema('schema.yml', 'yml', $path);
        
        if ( ! file_exists($path . '/SchemaTestUser.php')) {
            $this->fail();
        }
        
        if ( ! file_exists($path . '/SchemaTestProfile.php')) {
            $this->fail();
        }

        $this->assertEqual(Doctrine_Core::getTable('AliasTest')->getFieldName('test_col'), 'test_col_alias');

        Doctrine_Lib::removeDirectories($path);
    }
    
    public function testBuildSchema()
    {
        $schema = new Doctrine_Import_Schema();
        $array = $schema->buildSchema('schema.yml', 'yml');
        
        $model = $array['SchemaTestUser'];

        $this->assertTrue(array_key_exists('connection', $model));
        $this->assertTrue(array_key_exists('className', $model));
        $this->assertTrue(array_key_exists('tableName', $model));
        $this->assertTrue(array_key_exists('columns', $model) && is_array($model['columns']));
        $this->assertTrue(array_key_exists('relations', $model) && is_array($model['relations']));
        $this->assertTrue(array_key_exists('indexes', $model) && is_array($model['indexes']));
        $this->assertTrue(array_key_exists('attributes', $model) && is_array($model['attributes']));
        $this->assertTrue(array_key_exists('templates', $model) && is_array($model['columns']));
        $this->assertTrue(array_key_exists('actAs', $model) && is_array($model['actAs']));
        $this->assertTrue(array_key_exists('options', $model) && is_array($model['options']));
        $this->assertTrue(array_key_exists('package', $model));
        $this->assertTrue(array_key_exists('inheritance', $model) && is_array($model['inheritance']));
        $this->assertTrue(array_key_exists('detect_relations', $model) && is_bool($model['detect_relations']));
        $this->assertEqual($array['AliasTest']['columns']['test_col']['name'], 'test_col as test_col_alias');
    }
    
    public function testSchemaRelationshipCompletion()
    {
        $this->buildSchema = new Doctrine_Import_Schema();
        $this->schema = $this->buildSchema->buildSchema('schema.yml', 'yml');
        
        foreach ($this->schema as $name => $properties) {
            foreach ($properties['relations'] as $alias => $relation) {
                if ( ! $this->_verifyMultiDirectionalRelationship($name, $alias, $relation)) {
                    $this->fail();
                    
                    return false;
                }
            }
        }
        
        $this->pass();
    }
    
    protected function _verifyMultiDirectionalRelationship($class, $relationAlias, $relation)
    {
        $foreignClass = $relation['class'];
        $foreignAlias = isset($relation['foreignAlias']) ? $relation['foreignAlias']:$class;
        
        $foreignClassRelations = $this->schema[$foreignClass]['relations'];
        
        // Check to see if the foreign class has the opposite end defined for the class/foreignAlias
        if (isset($foreignClassRelations[$foreignAlias])) {
            return true;
        } else {
            return false;
        }
    }
}