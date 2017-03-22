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
 * Doctrine_Ticket_DC25_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC25_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC25_Article';
        $this->tables[] = 'Ticket_DC25_Tag';
        $this->tables[] = 'Ticket_DC25_ArticleTag';
        parent::prepareTables();
    }

    public function testTest()
    {
        $q = Doctrine_Core::getTable('Ticket_DC25_Article')
            ->createQuery('a')
            ->leftJoin('a.Tags t1')
            ->leftJoin('a.Tags t2');

        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.name AS t__name, t2.id AS t2__id, t2.name AS t2__name, t4.id AS t4__id, t4.name AS t4__name FROM ticket__d_c25__article t LEFT JOIN ticket__d_c25__article_tag t3 ON (t.id = t3.article_id) LEFT JOIN ticket__d_c25__tag t2 ON t2.id = t3.tag_id LEFT JOIN ticket__d_c25__article_tag t5 ON (t.id = t5.article_id) LEFT JOIN ticket__d_c25__tag t4 ON t4.id = t5.tag_id');
    }
}

class Ticket_DC25_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_DC25_Tag as Tags', array(
            'local' => 'article_id',
            'foreign' => 'tag_id',
            'refClass' => 'Ticket_DC25_ArticleTag'
        ));
    }
}

class Ticket_DC25_Tag extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_DC25_Article as Article', array(
            'local' => 'tag_id',
            'foreign' => 'article_id',
            'refClass' => 'Ticket_DC25_ArticleTag'
        ));
    }
}

class Ticket_DC25_ArticleTag extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('article_id', 'integer');
        $this->hasColumn('tag_id', 'integer');
    }
}