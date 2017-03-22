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
 * Doctrine_Import_PluginHierarchy_TestCase
 *
 * @package     Doctrine
 * @subpackage  Doctrine_Import_Builder
 * @author      Brice Figureau <brice+doctrine@daysofwonder.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Import_PluginHierarchy_TestCase extends Doctrine_UnitTestCase
{

    public function testImportOfHieriarchyOfPluginGeneration()
    {
        $yml = <<<END
---
WikiTest:
  actAs:
    I18n:
      fields: [title, content]
      actAs:
        Versionable:
          fields: [title, content]
        Searchable:
          fields: [title, content]
        Sluggable:
          fields: [title]
  columns:
    title: string(255)
    content: string
END;
        
        file_put_contents('wiki.yml', $yml);
        $path = dirname(__FILE__) . '/tmp/import_builder_test';

        $import = new Doctrine_Import_Schema();
        $import->setOption('generateTableClasses', true);
        $import->importSchema('wiki.yml', 'yml', $path);

        // check that the plugin hierarchy will produce the right sql statements
        // this is almost an end-to-end testing :-)
        $models = Doctrine_Core::loadModels($path, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);

        $sql = $this->conn->export->exportSortedClassesSql(array('WikiTest'));
        $sql = current($sql);

        $result = array(
            0 => 'CREATE TABLE wiki_test_translation_version (id INTEGER, lang CHAR(2), title VARCHAR(255), content TEXT, version INTEGER, PRIMARY KEY(id, lang, version))',
            1 => 'CREATE TABLE wiki_test_translation_index (keyword VARCHAR(200), field VARCHAR(50), position INTEGER, id INTEGER, lang CHAR(2), PRIMARY KEY(keyword, field, position, id, lang))',
            2 => 'CREATE TABLE wiki_test_translation (id INTEGER, title VARCHAR(255), content TEXT, lang CHAR(2), version INTEGER, slug VARCHAR(255), PRIMARY KEY(id, lang))',
            3 => 'CREATE TABLE wiki_test (id INTEGER PRIMARY KEY AUTOINCREMENT)',
            4 => 'CREATE UNIQUE INDEX wiki_test_translation_sluggable_idx ON wiki_test_translation (slug)',
        );
            
        foreach($sql as $idx => $req) {
            $this->assertEqual($req, $result[$idx]);
        }        
        
        Doctrine_Lib::removeDirectories($path);
        unlink('wiki.yml');
    }
}