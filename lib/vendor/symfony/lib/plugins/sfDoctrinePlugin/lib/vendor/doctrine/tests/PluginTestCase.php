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
 * Doctrine_Plugin_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Plugin_TestCase extends Doctrine_UnitTestCase 
{

    public function prepareData()
    { }

    public function prepareTables()
    { }

    public function testNestedPluginsGetExportedRecursively()
    {
        $sql = $this->conn->export->exportSortedClassesSql(array('Wiki'));
        $sql = current($sql);

        $this->assertEqual($sql[0], 'CREATE TABLE wiki_translation_version (id INTEGER, lang CHAR(2), title VARCHAR(255), content TEXT, version INTEGER, PRIMARY KEY(id, lang, version))');
        $this->assertEqual($sql[1], 'CREATE TABLE wiki_translation_index (keyword VARCHAR(200), field VARCHAR(50), position INTEGER, id INTEGER, lang CHAR(2), PRIMARY KEY(keyword, field, position, id, lang))');
        $this->assertEqual($sql[2], 'CREATE TABLE wiki_translation (id INTEGER, title VARCHAR(255), content TEXT, lang CHAR(2), version INTEGER, slug VARCHAR(255), PRIMARY KEY(id, lang))');
        $this->assertEqual($sql[3], 'CREATE TABLE wiki (id INTEGER PRIMARY KEY AUTOINCREMENT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');

        foreach ($sql as $query) {
            $this->conn->exec($query);
        }

    }

    public function testCreatingNewRecordsInvokesAllPlugins()
    {
        $wiki = new Wiki();
        $wiki->state(Doctrine_Record::STATE_TDIRTY);
        $wiki->save();

        $fi = $wiki->Translation['FI'];
        $fi->title = 'Michael Jeffrey Jordan';
        $fi->content = "Michael Jeffrey Jordan (s. 17. helmikuuta 1963, Brooklyn, New York) on yhdysvaltalainen entinen NBA-koripalloilija, jota pidet��n yleisesti kaikkien aikojen parhaana pelaajana.";

        $fi->save();
        $this->assertEqual($fi->version, 1);

        $fi->title = 'Micheal Jordan';
        $fi->save();
        
        $this->assertEqual($fi->version, 2);
    }

    public function testSavingUnmodifiedRecordsDoesNotInvokeTimestampableListener()
    {
    	$this->conn->clear();

        $wiki = Doctrine_Query::create()->from('Wiki w')->where('w.id = 1')->fetchOne();
        
        $wiki->save();

        $this->assertEqual($wiki->Translation['FI']->version, 2);
    }

    public function testSearchableChildTemplate()
    {
    	  $this->conn->clear();

        $wiki = new Wiki();
        $wiki->state(Doctrine_Record::STATE_TDIRTY);
        $wiki->save();
        $fi = $wiki->Translation['FI'];
        $fi->title = 'New Title';
        $fi->content = "Sorry, I'm not able to write a Finish sentence about Michael Jordan...";

        $fi->save();

        $t = Doctrine_Core::getTable('WikiTranslationIndex');
        $oQuery = new Doctrine_Search_Query($t);
        $oQuery->query("jordan");
        $out = $this->conn->fetchAll($oQuery->getSqlQuery(), $oQuery->getParams());

        $this->assertEqual($out[0]['relevance'], 2);
        $this->assertEqual($out[1]['relevance'], 1);
        $this->assertEqual($out[0]['id'], 1);
        $this->assertEqual($out[1]['id'], 2);
    }

    public function testSluggableChildTemplate()
    {
    	  $this->conn->clear();

        $wiki = new Wiki();
        $wiki->state(Doctrine_Record::STATE_TDIRTY);
        $wiki->save();
        $fi = $wiki->Translation['FI'];
        $fi->title = 'This is the title';
        $fi->content = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nulla sed.";

        $fi->save();
        $this->assertEqual($fi->slug, 'this-is-the-title');
		}
}

class Wiki extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 255);
        $this->hasColumn('content', 'string');
    }

    public function setUp()
    {
        $options = array('fields' => array('title', 'content'));
        $auditLog = new Doctrine_Template_Versionable($options);
        $search = new Doctrine_Template_Searchable($options);
        $slug = new Doctrine_Template_Sluggable(array('fields' => array('title'), 'indexName' => 'plugin_test_case_sluggable'));
        $i18n = new Doctrine_Template_I18n($options);

        $i18n->addChild($auditLog)
             ->addChild($search)
             ->addChild($slug);

        $this->actAs($i18n);

        $this->actAs('Timestampable');
    }
}