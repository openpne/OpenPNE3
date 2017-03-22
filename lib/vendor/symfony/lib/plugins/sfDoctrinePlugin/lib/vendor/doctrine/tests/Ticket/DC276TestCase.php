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
 * Doctrine_Ticket_DC276_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC276_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC276_Post';
        $this->tables[] = 'Ticket_DC276_Comment';
        parent::prepareTables();
    }

    public function testTest()
    {
        $q = Doctrine_Query::create()
            ->from('Ticket_DC276_Post p, p.Comments c')
            ->select('p.*, c.*, COUNT(c.id) AS comment_count')
            ->groupBy('p.id')
            ->having('comment_count <= p.max_comments');
        $this->assertEqual($q->getSqlQuery(), 'SELECT t.id AS t__id, t.content AS t__content, t.max_comments AS t__max_comments, t2.id AS t2__id, t2.post_id AS t2__post_id, t2.content AS t2__content, COUNT(t2.id) AS t2__0 FROM ticket__d_c276__post t LEFT JOIN ticket__d_c276__comment t2 ON t.id = t2.post_id GROUP BY t.id HAVING t2__0 <= t.max_comments');
        $q->execute();
    }
}

class Ticket_DC276_Post extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('content', 'string', 1000, array(
             'type' => 'string',
             'length' => '1000',
             ));
        $this->hasColumn('max_comments', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC276_Comment as Comments', array(
             'local' => 'id',
             'foreign' => 'post_id'));
    }
}

class Ticket_DC276_Comment extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('post_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('content', 'string', 100, array(
             'type' => 'string',
             'length' => '100',
             ));
    }

    public function setUp()
    {
        $this->hasMany('Ticket_DC276_Post', array(
             'local' => 'post_id',
             'foreign' => 'id'));
    }
}