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
 * Doctrine_Ticket_1630_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1630_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1630_BlogPost';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $test = new Ticket_1630_BlogPost();
        $test->Translation['en']->title = 'en test';
        $test->Translation['fr']->title = 'fr test';
        $test->body = 'test';
        $test->save();

        $test = new Ticket_1630_BlogPost();
        $test->Translation['en']->title = 'cool';
        $test->body = 'very cool';
        $test->save();
    }

    public function testTest()
    {
        $cacheConn = Doctrine_Manager::getInstance()->openConnection('sqlite::memory:', 'cache', false);
		$cacheDriver = new Doctrine_Cache_Db(array('tableName' => 'cache', 'connection' => $cacheConn));
		$cacheDriver->createTable();

        $currentCacheDriver = $this->conn->getAttribute(Doctrine_Core::ATTR_QUERY_CACHE);
		$this->conn->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cacheDriver);

        try {
            $q = Doctrine_Query::create()
                ->from('Ticket_1630_BlogPost p')
                ->leftJoin('p.Translation t INDEXBY t.lang');
            $results = $q->execute();
            $results = $q->execute();

            $this->pass();
        } catch (Exception $e) {
            $this->fail();
        }

        $this->conn->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $currentCacheDriver);
    }
}

class Ticket_1630_BlogPost extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 255);
        $this->hasColumn('body', 'clob');
    }

    public function setUp()
    {
        $i18n = new Doctrine_Template_I18n(array('fields' => array('title')));
        $this->actAs($i18n);
    }
}