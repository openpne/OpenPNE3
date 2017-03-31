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
 * Doctrine_Query_Cache_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Query_Cache_TestCase extends Doctrine_UnitTestCase
{

    public function testQueryCacheAddsQueryIntoCache()
    {
        $cache = $this->_getCacheDriver();

        $q = Doctrine_Query::create()
            ->select('u.id, u.name, p.id')
            ->from('User u')
            ->leftJoin('u.Phonenumber p')
            ->where('u.name = ?', 'walhala')
            ->useQueryCache($cache);

        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateQueryCacheHash()));
        $this->assertEqual(count($coll), 0);

        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateQueryCacheHash()));
        $this->assertEqual(count($coll), 0);
    }

    public function testQueryCacheWorksWithGlobalConfiguration()
    {
        $cache = $this->_getCacheDriver();

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cache);

        $q = Doctrine_Query::create()
            ->select('u.id, u.name, p.id')
            ->from('User u')
            ->leftJoin('u.Phonenumber p');

        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateQueryCacheHash()));
        $this->assertEqual(count($coll), 8);

        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateQueryCacheHash()));
        $this->assertEqual(count($coll), 8);
    }

    public function testResultSetCacheAddsResultSetsIntoCache()
    {
        $q = new Doctrine_Query();

        $cache = $this->_getCacheDriver();
        $q->useResultCache($cache)->select('u.name')->from('User u');
        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateResultCacheHash()));
        $this->assertEqual(count($coll), 8);

        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateResultCacheHash()));
        $this->assertEqual(count($coll), 8);
    }

    public function testResultSetCacheSupportsQueriesWithJoins()
    {
        $q = new Doctrine_Query();

        $cache = $this->_getCacheDriver();
        $q->useResultCache($cache);
        $q->select('u.name')->from('User u')->leftJoin('u.Phonenumber p');
        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateResultCacheHash()));
        $this->assertEqual(count($coll), 8);

        $coll = $q->execute();

        $this->assertTrue($cache->contains($q->calculateResultCacheHash()));
        $this->assertEqual(count($coll), 8);
    }

    public function testResultSetCacheSupportsPreparedStatements()
    {
        $q = new Doctrine_Query();

        $cache = $this->_getCacheDriver();
        $q->useResultCache($cache);
        $q->select('u.name')->from('User u')->leftJoin('u.Phonenumber p')
          ->where('u.id = ?');

        $coll = $q->execute(array(5));

        $this->assertTrue($coll instanceof Doctrine_Collection);
        $this->assertEqual(5, $coll[0]->id);
        $this->assertTrue($coll[0] instanceof Doctrine_Record);
        $this->assertTrue($coll[0]->Phonenumber[0] instanceof Doctrine_Record);
        $this->assertTrue($cache->contains($q->calculateResultCacheHash(array(5))));
        $this->assertEqual(count($coll), 1);
        $coll->free(true);

        $coll = $q->execute(array(5));

        $this->assertTrue($coll instanceof Doctrine_Collection);
        $this->assertEqual(5, $coll[0]->id);
        $this->assertTrue($coll[0] instanceof Doctrine_Record);
        // references to related objects are not serialized/unserialized, so the following
        // would trigger an additional query (lazy-load).
        //echo $this->conn->count() . "<br/>";
        //$this->assertTrue($coll[0]->Phonenumber[0] instanceof Doctrine_Record);
        //echo $this->conn->count() . "<br/>"; // count is increased => lazy load
        $this->assertTrue($cache->contains($q->calculateResultCacheHash(array(5))));
        $this->assertEqual(count($coll), 1);
    }

    public function testUseCacheSupportsBooleanTrueAsParameter()
    {
        $q = new Doctrine_Query();

        $cache = $this->_getCacheDriver();
        $this->conn->setAttribute(Doctrine_Core::ATTR_CACHE, $cache);

        $q->useResultCache(true);
        $q->select('u.name')->from('User u')->leftJoin('u.Phonenumber p')
          ->where('u.id = ?');

        $coll = $q->execute(array(5));

        $this->assertTrue($cache->contains($q->calculateResultCacheHash(array(5))));
        $this->assertEqual(count($coll), 1);

        $coll = $q->execute(array(5));

        $this->assertTrue($cache->contains($q->calculateResultCacheHash(array(5))));
        $this->assertEqual(count($coll), 1);

        $this->conn->setAttribute(Doctrine_Core::ATTR_CACHE, null);
    }

    public function testResultCacheLifeSpan()
    {
        // initially NULL = not cached
        $q = new Doctrine_Query();
        $this->assertIdentical(null, $q->getResultCacheLifeSpan());
        $q->free();

        // 0 = cache forever
        $this->manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, 0);
        $q = new Doctrine_Query();
        $this->assertIdentical(0, $q->getResultCacheLifeSpan());
        $q->free();

        $this->manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, 3600);
        $q = new Doctrine_Query();
        $this->assertIdentical(3600, $q->getResultCacheLifeSpan());
        $q->free();

        // test that value set on connection level has precedence
        $this->conn->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, 42);
        $q = new Doctrine_Query();
        $this->assertIdentical(42, $q->getResultCacheLifeSpan());
        $q->free();

        // test that value set on the query has highest precedence
        $q = new Doctrine_Query();
        $q->useResultCache(true, 1234);
        $this->assertIdentical(1234, $q->getResultCacheLifeSpan());
        $q->setResultCacheLifeSPan(4321);
        $this->assertIdentical(4321, $q->getResultCacheLifeSpan());
        $q->free();
    }

    public function testQueryCacheLifeSpan()
    {
        // initially NULL = not cached
        $q = new Doctrine_Query();
        $this->assertIdentical(null, $q->getQueryCacheLifeSpan());
        $q->free();

        // 0 = forever
        $this->manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE_LIFESPAN, 0);
        $q = new Doctrine_Query();
        $this->assertIdentical(0, $q->getQueryCacheLifeSpan());
        $q->free();

        $this->manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE_LIFESPAN, 3600);
        $q = new Doctrine_Query();
        $this->assertIdentical(3600, $q->getQueryCacheLifeSpan());
        $q->free();

        // test that value set on connection level has precedence
        $this->conn->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE_LIFESPAN, 42);
        $q = new Doctrine_Query();
        $this->assertIdentical(42, $q->getQueryCacheLifeSpan());
        $q->free();

        // test that value set on the query has highest precedence
        $q = new Doctrine_Query();
        $q->setQueryCacheLifeSpan(4321);
        $this->assertIdentical(4321, $q->getQueryCacheLifeSpan());
        $q->free();
    }

    public function testQueryCacheCanBeDisabledForSingleQuery()
    {
        $cache = $this->_getCacheDriver();
        $q = new Doctrine_Query();
        $q->select('u.name')->from('User u')->leftJoin('u.Phonenumber p')->where('u.name = ?', 'walhala')
                ->useQueryCache(false);

        $coll = $q->execute();

        $this->assertFalse($cache->contains($q->calculateQueryCacheHash()));
        $this->assertEqual(count($coll), 0);

        $coll = $q->execute();

        $this->assertFalse($cache->contains($q->calculateQueryCacheHash()));
        $this->assertEqual(count($coll), 0);
    }
    
    protected function _getCacheDriver()
    {
        return new Doctrine_Cache_Array();
    }
}