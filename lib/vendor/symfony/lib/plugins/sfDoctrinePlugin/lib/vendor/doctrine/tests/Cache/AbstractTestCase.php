<?php
/*
 *  $Id: AbstractTestCase.php 7490 2010-03-29 19:53:27Z jwage $
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
 * Doctrine_Cache_Abstract_TestCase
 *
 * @package     Doctrine
 * @subpackage  Doctrine_Cache
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision: 7490 $
 */
abstract class Doctrine_Cache_Abstract_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array('User');
        parent::prepareTables();
    }

    public function prepareData()
    {
        $user = new User();
        $user->name = 'Hans';
        $user->save();
    }

    public function testAsResultCache()
    {
        if ( !$this->_isEnabled()) {
            return;
        }
        $this->_clearCache();
        $cache = $this->_getCacheDriver();

        $this->conn->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $cache);

        $queryCountBefore = $this->conn->count();

        for ($i = 0; $i < 10; $i++) {
            $u = Doctrine_Query::create()
                ->from('User u')
                ->addWhere('u.name = ?', array('Hans'))
                ->useResultCache($cache, 3600, 'hans_query')
                ->execute();
            $this->assertEqual(1, count($u));
            $this->assertEqual("Hans", $u[0]->name);
        }

        // Just 1 query should be run
        $this->assertEqual($queryCountBefore + 1, $this->conn->count());
        $this->assertTrue($cache->contains('hans_query'));
    }

    public function testCacheCore()
    {
        if ( !$this->_isEnabled()) {
            return;
        }
        $this->_clearCache();
        $cache = $this->_getCacheDriver();

        $object = 'test_data';
        $cache->save('foo', $object, 3600);
        $this->assertTrue($cache->contains('foo'));

        $this->assertEqual($cache->fetch('foo'), 'test_data');

        $cache->delete('foo');
        $this->assertFalse($cache->contains('foo'));
    }

    public function testDeleteByPrefix()
    {
        if ( !$this->_isEnabled()) {
            return;
        }
        $this->_clearCache();
        $cache = $this->_getCacheDriver();

        $object = 'test_data';
        $cache->save('prefix_foo', $object, 3600);
        $cache->save('prefix_bar', $object, 3600);
        $cache->save('foo', $object, 3600);

        $cache->deleteByPrefix('prefix_');
        $this->assertFalse($cache->contains('prefix_foo'));
        $this->assertFalse($cache->contains('prefix_bar'));
        $this->assertTrue($cache->contains('foo'));
    }

    public function testDeleteBySuffix()
    {
        if ( !$this->_isEnabled()) {
            return;
        }
        $this->_clearCache();
        $cache = $this->_getCacheDriver();

        $object = 'test_data';
        $cache->save('foo_suffix', $object, 3600);
        $cache->save('bar_suffix', $object, 3600);
        $cache->save('foo', $object, 3600);

        $cache->deleteBySuffix('_suffix');
        $this->assertFalse($cache->contains('foo_suffix'));
        $this->assertFalse($cache->contains('bar_suffix'));
        $this->assertTrue($cache->contains('foo'));
    }
    
    public function testDeleteByRegex()
    {
        if ( !$this->_isEnabled()) {
            return;
        }
        $this->_clearCache();
        $cache = $this->_getCacheDriver();

        $object = 'test_data';
        $cache->save('foo_match_me', $object, 3600);
        $cache->save('bar_match_me', $object, 3600);
        $cache->save('foo', $object, 3600);

        $cache->deleteByRegex('/match/');
        $this->assertFalse($cache->contains('foo_match_me'));
        $this->assertFalse($cache->contains('bar_match_me'));
        $this->assertTrue($cache->contains('foo'));
    }
    
    abstract protected function _clearCache();    
    abstract protected function _isEnabled();
    abstract protected function _getCacheDriver();
}
