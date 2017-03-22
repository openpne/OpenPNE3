<?php
/*
 *  $Id: DbTestCase.php 7490 2010-03-29 19:53:27Z jwage $
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
 * Doctrine_Cache_Db_TestCase
 *
 * @package     Doctrine
 * @subpackage  Doctrine_Cache
 * @author      David Abdemoulaie <dave@hobodave.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.2
 * @version     $Revision: 7490 $
 */
class Doctrine_Cache_Db_TestCase extends Doctrine_Cache_Abstract_TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->cache = new Doctrine_Cache_Db(array(
            'connection' => $this->connection,
            'tableName' => 'd_cache',
        ));
        $this->connection->exec('DROP TABLE IF EXISTS d_cache');
        $this->cache->createTable();
    }
    protected function _clearCache()
    {
        $this->connection->exec('DELETE FROM d_cache');
    }

    protected function _isEnabled()
    {
        return true;
    }

    protected function _getCacheDriver()
    {
        return $this->cache;
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

        $this->assertTrue($cache->contains('hans_query'));
    }
}
