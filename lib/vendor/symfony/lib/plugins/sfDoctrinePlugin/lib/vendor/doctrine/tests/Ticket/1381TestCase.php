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
 * Doctrine_Ticket_1381_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1381_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'T1381_Comment';
        $this->tables[] = 'T1381_Article';

        parent::prepareTables();
    }
    
    
    public function prepareData()
    {
        $a = new T1381_Article();
        $a->title = 'When cleanData worked as expected!';
        $a->save();
        
        $c = new T1381_Comment();
        $c->article_id = $a->id;
        $c->body = 'Yeah! It will work one day.';
        $c->save();

        $c = new T1381_Comment();
        $c->article_id = $a->id;
        $c->body = 'It will!';
        $c->save();
        
        // Cleaning up IdentityMap
        Doctrine_Core::getTable('T1381_Article')->clear();
        Doctrine_Core::getTable('T1381_Comment')->clear();
    }
    
    public function testTicket()
    {
        try {
            // Now we fetch with data we want (it seems it overrides calculates columns of already fetched objects)
            $dql = 'SELECT c.*, a.* FROM T1381_Comment c INNER JOIN c.T1381_Article a';
            $items = Doctrine_Query::create()->query($dql, array(), Doctrine_Core::HYDRATE_ARRAY);

            // This should result in false, since we didn't fetch for this column
            $this->assertFalse(array_key_exists('ArticleTitle', $items[0]['T1381_Article']));
            
            // We fetch for data including new columns
            $dql = 'SELECT c.*, a.title as ArticleTitle FROM T1381_Comment c INNER JOIN c.T1381_Article a WHERE c.id = ?';
            $items = Doctrine_Query::create()->query($dql, array(1), Doctrine_Core::HYDRATE_ARRAY);
            $comment = $items[0];

            $this->assertTrue(array_key_exists('ArticleTitle', $comment));
        } catch (Doctrine_Exception $e) {
            $this->fail($e->getMessage());
        }
    }


    public function testTicketInverse()
    {
        try {
            // We fetch for data including new columns
            $dql = 'SELECT c.*, a.title as ArticleTitle FROM T1381_Comment c INNER JOIN c.T1381_Article a WHERE c.id = ?';
            $items = Doctrine_Query::create()->query($dql, array(1), Doctrine_Core::HYDRATE_ARRAY);
            $comment = $items[0];

            $this->assertTrue(array_key_exists('ArticleTitle', $comment));

            // Now we fetch with data we want (it seems it overrides calculates columns of already fetched objects)
            $dql = 'SELECT c.*, a.* FROM T1381_Comment c INNER JOIN c.T1381_Article a';
            $items = Doctrine_Query::create()->query($dql, array(), Doctrine_Core::HYDRATE_ARRAY);

            // This should result in false, since we didn't fetch for this column
            $this->assertFalse(array_key_exists('ArticleTitle', $items[0]['T1381_Article']));

            // Assert that our existent component still has the column, even after new hydration on same object
            $this->assertTrue(array_key_exists('ArticleTitle', $comment));

            // Fetch including new columns again
            $dql = 'SELECT c.id, a.*, a.id as ArticleTitle FROM T1381_Comment c INNER JOIN c.T1381_Article a';
            $items = Doctrine_Query::create()->query($dql, array(), Doctrine_Core::HYDRATE_ARRAY);

            // Assert that new calculated column with different content do not override the already fetched one
            $this->assertTrue(array_key_exists('ArticleTitle', $items[0]));
            
            // Assert that our existent component still has the column, even after new hydration on same object
            $this->assertTrue(array_key_exists('ArticleTitle', $comment));
            $this->assertTrue($comment, 'When cleanData worked as expected!');
        } catch (Doctrine_Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}


class T1381_Article extends Doctrine_Record
{
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('title', 'string', 255, array('notnull' => true));
    }
    
    public function setUp() {
        $this->hasMany(
            'T1381_Comment',
            array(
                'local' => 'id',
                'foreign' => 'article_id'
            )
        );
    }
}


class T1381_Comment extends Doctrine_Record
{
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('body', 'string', null, array('notnull' => true));
        $this->hasColumn('article_id', 'integer', null, array('notnull' => true));
    }

    public function setUp() {
        $this->hasOne(
            'T1381_Article',
            array(
                'local' => 'article_id',
                'foreign' => 'id'
            )
        );
    }
}