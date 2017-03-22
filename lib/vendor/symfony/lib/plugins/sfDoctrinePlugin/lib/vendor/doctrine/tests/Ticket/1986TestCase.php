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
 * Doctrine_Ticket_1986_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1986_TestCase extends Doctrine_UnitTestCase 
{
	public function prepareData()
	{
	}

	public function prepareTables()
	{
		$this->tables = array('Testing_Ticket_1986_1','Testing_Ticket_1986_2','Testing_Ticket_1986Link');
		parent::prepareTables();
	}

	public function testTicket()
	{
		// this works
		$t1 = new Testing_Ticket_1986_1();
		$t1->get('others');
		$t1->save();
		try {
			$t1->get('others');
		} catch(Doctrine_Exception $e) {
			$this->fail("after save".$e->getMessage());
		}
		// this not: relation is not accessed before save and is gone afterwards
		$t2 = new Testing_Ticket_1986_1();
		$t2->save();
		try {
			$t2->get('others');
		} catch(Doctrine_Exception $e) {
			$this->fail($e->getMessage());
		}
	}
	 
}

class Testing_Ticket_1986_1 extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('testing_ticket_1986_1');
		$this->hasColumn('name', 'string', 64, array());
	}
	public function setUp()
	{
		$this->hasMany('Testing_Ticket_1986_2 as others', array('refClass' => 'Testing_Ticket_1986Link', 'local' => 'id_1', 'foreign' => 'id_2'));
	}
}

class Testing_Ticket_1986_2 extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('testing_ticket_1986_2');
		$this->hasColumn('value', 'string', 64, array());
	}

	public function setUp()
	{
		$this->hasMany('Testing_Ticket_1986_1', array('refClass' => 'Testing_Ticket_1986Link', 'local' => 'id_2', 'foreign' => 'id_1'));
	}
}

class Testing_Ticket_1986Link extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('testing_ticket_1986_link');
		$this->hasColumn('id_1', 'integer', null, array());
		$this->hasColumn('id_2', 'integer', null, array());
	}
	
	public function setUp() {
		// setup relations
		$this->hasOne('Testing_Ticket_1986_1 as rel1', array('local' => 'id_1', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
		$this->hasOne('Testing_Ticket_1986_2 as rel2', array('local' => 'id_2', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
	}
  
}
	