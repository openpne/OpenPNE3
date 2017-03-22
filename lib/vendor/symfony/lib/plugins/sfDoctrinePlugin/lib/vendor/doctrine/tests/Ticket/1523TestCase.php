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
 * Doctrine_Ticket_1523_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1523_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1523_User';
        $this->tables[] = 'Ticket_1523_Group';
        parent::prepareTables();
    }

    public function testTest()
    {
        $q = Doctrine_Query::create()
			->from('Ticket_1523_User u')
			->innerJoin('u.Ticket_1523_Group g')
			->where('EXISTS (SELECT uu.id FROM Ticket_1523_User uu WHERE uu.id = u.id)')
			->orderBy('u.code ASC');

		$pager = new Doctrine_Pager($q, 1, 10);
		$pager->execute(array());
		$this->assertEqual($pager->getQuery()->getSqlQuery(), 'SELECT t.id AS t__id, t.code AS t__code, t2.id AS t2__id, t2.tmp_id AS t2__tmp_id FROM ticket_1523__user t INNER JOIN ticket_1523__group t2 ON t.id = t2.tmp_id WHERE t.id IN (SELECT DISTINCT t3.id FROM ticket_1523__user t3 INNER JOIN ticket_1523__group t4 ON t3.id = t4.tmp_id WHERE EXISTS (SELECT t5.id AS t3__id FROM ticket_1523__user t5 WHERE (t5.id = t3.id)) ORDER BY t3.code ASC LIMIT 10) AND (EXISTS (SELECT t3.id AS t3__id FROM ticket_1523__user t3 WHERE (t3.id = t.id))) ORDER BY t.code ASC');
    }
}

class Ticket_1523_User extends Doctrine_Record
{
	public function setTableDefinition()
	{
    	$this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'primary' => true));
    	$this->hasColumn('code', 'string', 64, array('type' => 'string', 'length' => '64', 'notnull' => true));
	}
	public function setUp()
	{
		$this->hasMany('Ticket_1523_Group', array(
			'local' => 'id',
			'foreign' => 'tmp_id',
		));
	}
}

class Ticket_1523_Group extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'primary' => true, ));
		$this->hasColumn('tmp_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'notnull' => true));
	}
}