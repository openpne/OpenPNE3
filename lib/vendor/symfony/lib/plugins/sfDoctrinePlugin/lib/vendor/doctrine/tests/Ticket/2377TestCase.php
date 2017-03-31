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
 * Doctrine_Ticket_2355_TestCase
 *
 * @package     Doctrine
 * @author      Jacek Dębowczyk <j.debowczyk@diface.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$
 */
class Doctrine_Ticket_2377_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_2377_Author';
        $this->tables[] = 'Ticket_2377_Article';
        parent::prepareTables();
    }

    public function testSynchronize()
    {
        try {
			$author = new Ticket_2377_Author();
			$article = new Ticket_2377_Article();
			$article->Author = $author;
        
			$array = $article->toArray(true);
				
			$article2 = new Ticket_2377_Article();
			$article2->synchronizeWithArray($array);

			$this->assertTrue($article2->Author instanceof Ticket_2377_Author);

            $this->pass();
        } catch (Exception $e) {
			$this->fail();
        }
    }
}

class Ticket_2377_Author extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('author');
        $this->hasColumn('id', 'integer', 2,
          array('type' => 'integer', 'primary' => true, 'autoincrement' => true, 'length' => '2'));
        $this->hasColumn('name', 'string', 2,
          array('type' => 'string', 'length' => '100'));
    }

    public function setUp()
    {
        $this->hasMany('Ticket_2377_Article as Article', array('local' => 'id', 'foreign' => 'author_id'));
    }
}

class Ticket_2377_Article extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('article');
        $this->hasColumn('id', 'integer', 2,
          array('type' => 'integer', 'primary' => true, 'autoincrement' => true, 'length' => '2'));
        $this->hasColumn('author_id', 'integer', 2,
          array('type' => 'integer', 'unsigned' => true, 'length' => '2'));
        $this->hasColumn('content', 'string', 100,
          array('type' => 'string', 'length' => '100'));
    }

    public function setUp()
    {
        $this->hasOne('Ticket_2377_Author as Author', array('local' => 'author_id', 'foreign' => 'id'));
    }
}