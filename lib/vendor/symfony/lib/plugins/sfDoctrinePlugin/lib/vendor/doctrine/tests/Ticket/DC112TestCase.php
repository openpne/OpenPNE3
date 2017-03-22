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
 * Doctrine_Ticket_DDC47_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC112_TestCase extends Doctrine_UnitTestCase
{
    public function testResultCacheSetHash()
    {
        $cacheDriver = new Doctrine_Cache_Array();

        $q1 = Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'test1');

        $coll = $q1->execute();

        $this->assertTrue($cacheDriver->contains('test1'));
        $this->assertEqual(count($coll), 8);

        $coll = $q1->execute();

        $this->assertTrue($cacheDriver->contains('test1'));
        $this->assertEqual(count($coll), 8);

        $q2 = Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'test2');

        $coll = $q2->execute();
        $this->assertTrue($cacheDriver->contains('test1'));
        $this->assertTrue($cacheDriver->contains('test2'));
        $this->assertEqual(count($coll), 8);

        $q2->clearResultCache();
        $this->assertTrue($cacheDriver->contains('test1'));
        $this->assertFalse($cacheDriver->contains('test2'));

        $cacheDriver->delete('test1');
        $this->assertFalse($cacheDriver->contains('test1'));

        $q = Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver)
            ->setResultCacheHash('testing');

        $coll = $q->execute();
        $this->assertTrue($cacheDriver->contains('testing'));

        $this->assertEqual($q->getResultCacheHash(), 'testing');
        $q->setResultCacheHash(null);
        $this->assertEqual($q->getResultCacheHash(), '9b6aafa501ac37b902719cd5061f412d');
    }

    public function testDeleteByRegex()
    {
        $cacheDriver = new Doctrine_Cache_Array(array(
            'prefix' => 'test_'
        ));

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'doctrine_query_one')
            ->execute();

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'doctrine_query_two')
            ->execute();

        $count = $cacheDriver->deleteByRegex('/test_doctrine_query_.*/');
        $this->assertEqual($count, 2);
        $this->assertFalse($cacheDriver->contains('doctrine_query_one'));
        $this->assertFalse($cacheDriver->contains('doctrine_query_two'));
    }

    public function testDeleteByPrefix()
    {
        $cacheDriver = new Doctrine_Cache_Array(array(
            'prefix' => 'test_'
        ));

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'doctrine_query_one')
            ->execute();

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'doctrine_query_two')
            ->execute();

        $count = $cacheDriver->deleteByPrefix('test_');
        $this->assertEqual($count, 2);
        $this->assertFalse($cacheDriver->contains('doctrine_query_one'));
        $this->assertFalse($cacheDriver->contains('doctrine_query_two'));
    }

    public function testDeleteBySuffix()
    {
        $cacheDriver = new Doctrine_Cache_Array();

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'one_query')
            ->execute();

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'two_query')
            ->execute();

        $count = $cacheDriver->deleteBySuffix('_query');
        $this->assertEqual($count, 2);
        $this->assertFalse($cacheDriver->contains('one_query'));
        $this->assertFalse($cacheDriver->contains('two_query'));
    }

    public function testDeleteWithWildcard()
    {
        $cacheDriver = new Doctrine_Cache_Array();

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'user_query_one')
            ->execute();

        Doctrine_Query::create()
            ->from('User u')
            ->useResultCache($cacheDriver, 3600, 'user_query_two')
            ->execute();

        $count = $cacheDriver->delete('user_query_*');
        $this->assertEqual($count, 2);
        $this->assertFalse($cacheDriver->contains('user_query_one'));
        $this->assertFalse($cacheDriver->contains('user_query_two'));
    }
}
