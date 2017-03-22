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
 * Doctrine_Ticket_DC241_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC241_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC241_Poll';
        $this->tables[] = 'Ticket_DC241_PollAnswer';
        parent::prepareTables();
    }

    public function testTest()
    {
        $q = Doctrine_Query::create()
        	->from('Ticket_DC241_Poll p')
        	->leftJoin('p.Answers pa ON pa.votes = ?', 100)
        	->addWhere('p.id = ?', 200)
        	->addWhere('p.id = ?', 300)
        	->addWhere('p.id = ?', 400)
        	->addWhere('p.id = ?', 400)
        	->groupBy('p.id')
        	->having('p.id > ?', 300)
        	->limit(10);

        $this->assertEqual($q->getCountSqlQuery(), 'SELECT COUNT(*) AS num_results FROM (SELECT t.id FROM ticket__d_c241__poll t LEFT JOIN module_polls_answers m ON (m.votes = ?) WHERE t.id = ? AND t.id = ? AND t.id = ? AND t.id = ? GROUP BY t.id HAVING t.id > ?) dctrn_count_query');

        try {
            $q->count();
            $this->pass();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class Ticket_DC241_Poll extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id_category', 'integer', null, array('notnull' => true));
		$this->hasColumn('question', 'string', 256);
	}
	
	public function setUp()
	{
		$this->hasMany('Ticket_DC241_PollAnswer as Answers', array('local' => 'id', 'foreign' => 'id_poll', 'orderBy' => 'position'));
	}
}

class Ticket_DC241_PollAnswer extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('module_polls_answers');
		
		$this->hasColumn('id_poll', 'integer', null, array('notnull' => true));
		$this->hasColumn('answer', 'string', 256);
		$this->hasColumn('votes', 'integer', null, array('notnull' => true, 'default' => 0));
		$this->hasColumn('position', 'integer');
	}
	
	public function setUp()
	{
		$this->hasOne('Ticket_DC241_Poll as Poll', array('local' => 'id_poll', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
	}
}