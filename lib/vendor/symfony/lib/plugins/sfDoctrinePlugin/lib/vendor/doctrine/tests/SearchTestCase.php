<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * 'AS IS' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
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
 * Doctrine_Search_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Search_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables = array('SearchTest');
        
        parent::prepareTables();
    }
    public function prepareData()
    { }

    public function testBuildingOfSearchRecordDefinition()
    {
        $e = new SearchTest();
        
        $this->assertTrue($e->SearchTestIndex instanceof Doctrine_Collection);
        
        $rel = $e->getTable()->getRelation('SearchTestIndex');

        $this->assertIdentical($rel->getLocal(), 'id');
        $this->assertIdentical($rel->getForeign(), 'id');
    }
    public function testSavingEntriesUpdatesIndex()
    {
        $e = new SearchTest();

        $e->title = 'Once there was an ORM framework';
        $e->content = 'There are many ORM frameworks, but nevertheless we decided to create one.';

        $e->save();

        $e = new SearchTest();

        $e->title = '007';
        $e->content = 'Awesome movie series';

        $e->save();
    }

    public function testSearchFromTableObject()
    {
        $results = Doctrine_Core::getTable('SearchTest')->search('orm');
        $this->assertEqual($results[0]['id'], 1);
        $query = Doctrine_Query::create()
            ->from('SearchTest s');
        $query = Doctrine_Core::getTable('SearchTest')->search('orm', $query);
        $this->assertEqual($query->getSqlQuery(), 'SELECT s.id AS s__id, s.title AS s__title, s.content AS s__content FROM search_test s WHERE (s.id IN (SELECT id FROM search_test_index WHERE keyword = ? GROUP BY id))');
        $results = $query->fetchArray();
        $this->assertEqual($results[0]['id'], 1);
    }

    public function testQuerying()
    {
        $q = new Doctrine_Query();

        $q->select('t.title')
          ->from('SearchTest t')
          ->innerJoin('t.SearchTestIndex i')
          ->where('i.keyword = ?');

        $array = $q->execute(array('orm'), Doctrine_Core::HYDRATE_ARRAY);

        $this->assertEqual($array[0]['title'], 'Once there was an ORM framework');

        $q = new Doctrine_Query();

        $q->select('t.title')
          ->from('SearchTest t')
          ->innerJoin('t.SearchTestIndex i')
          ->where('i.keyword = ?');

        $array = $q->execute(array('007'), Doctrine_Core::HYDRATE_ARRAY);

        $this->assertEqual($array[0]['title'], '007');
    }
    
    public function testUsingWordRange()
    {
        $q = new Doctrine_Query();

        $q->select('t.title, i.*')
          ->from('SearchTest t')
          ->innerJoin('t.SearchTestIndex i')
          ->where('i.keyword = ? OR i.keyword = ?');

        $array = $q->execute(array('orm', 'framework'), Doctrine_Core::HYDRATE_ARRAY);

        $this->assertEqual($array[0]['title'], 'Once there was an ORM framework');
    }

    public function testQueryingReturnsEmptyArrayForStopKeyword()
    {
        $q = new Doctrine_Query();

        $q->select('t.title')
          ->from('SearchTest t')
          ->innerJoin('t.SearchTestIndex i')
          ->where('i.keyword = ?');

        $array = $q->execute(array('was'), Doctrine_Core::HYDRATE_ARRAY);

        $this->assertEqual(count($array), 0);
    }

    public function testQueryingReturnsEmptyArrayForUnknownKeyword()
    {
        $q = new Doctrine_Query();

        $q->select('t.title')
          ->from('SearchTest t')
          ->innerJoin('t.SearchTestIndex i')
          ->where('i.keyword = ?');

        $array = $q->execute(array('someunknownword'), Doctrine_Core::HYDRATE_ARRAY);

        $this->assertEqual(count($array), 0);
    }

    public function testUpdateIndexInsertsNullValuesForBatchUpdatedEntries()
    {
        $e = new SearchTest();
        $tpl = $e->getTable()->getTemplate('Doctrine_Template_Searchable');
        $tpl->getPlugin()->setOption('batchUpdates', true);

        $e->title = 'Some searchable title';
        $e->content = 'Some searchable content';

        $e->save();
        
        $coll = Doctrine_Query::create()
                ->from('SearchTestIndex s')
                ->orderby('s.id DESC')
                ->limit(1)
                ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
                ->fetchOne();

        $this->assertEqual($coll['id'], 3);
        $this->assertEqual($coll['keyword'], null);
        $this->assertEqual($coll['field'], null);
        $this->assertEqual($coll['position'], null);
    }

    public function testBatchUpdatesUpdateAllPendingEntries()
    {
        $e = new SearchTest();
        $e->batchUpdateIndex();
        
        $coll = Doctrine_Query::create()
                ->from('SearchTestIndex s')
                ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
                ->execute();

        $coll = $this->conn->fetchAll('SELECT * FROM search_test_index');
        

    }

    public function testThrowExceptionIfInvalidTable()
    {
       try {
           $oQuery = new Doctrine_Search_Query(new Doctrine_Query());
           $this->fail('Should throw exception');
       } catch(Doctrine_Search_Exception $exception) {
           $this->assertEqual($exception->getMessage(), 'Invalid argument type. Expected instance of Doctrine_Table.');
       }
    }

    public function testGenerateSearchQueryForWeightedSearch()
    {
        $oQuery = new Doctrine_Search_Query('SearchTest');
        $oQuery->query('^test');
        $this->assertEqual($oQuery->getSqlQuery(), 'SELECT SUM(sub_relevance) AS relevance, id FROM search_test WHERE keyword = ? GROUP BY id ORDER BY relevance DESC');
    }

    public function testStandardAnalyzerCanHandleAccentedCharactersGracefullyWorks()
    {
        $analyzer = new Doctrine_Search_Analyzer_Standard();

        $words = $analyzer->analyze('un éléphant ça trompe énormément', 'utf-8');
        $this->assertEqual($words[1], 'elephant');
        $this->assertEqual($words[2], 'ca');
        $this->assertEqual($words[4], 'enormement');
    }
    
    public function testUtf8AnalyzerWorks()
    {
        $analyzer = new Doctrine_Search_Analyzer_Utf8(array('encoding' => 'utf-8'));

        $words = $analyzer->analyze('un Éléphant ça trompe énormément');
        $this->assertEqual($words[1], 'éléphant');
        $this->assertEqual($words[2], 'ça');
        $this->assertEqual($words[4], 'énormément');
    }
 
    public function testUtf8AnalyzerKnowsToHandleOtherEncodingsWorks()
    {
        $analyzer = new Doctrine_Search_Analyzer_Utf8();

        // convert our test string to iso8859-15
        $iso = iconv('UTF-8','ISO8859-15', 'un éléphant ça trompe énormément');

        $words = $analyzer->analyze($iso, 'ISO8859-15');
        $this->assertEqual($words[1], 'éléphant');
        $this->assertEqual($words[2], 'ça');
        $this->assertEqual($words[4], 'énormément');
    }
}