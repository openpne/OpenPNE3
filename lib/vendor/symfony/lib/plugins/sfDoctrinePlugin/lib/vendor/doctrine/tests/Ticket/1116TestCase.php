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
 * Doctrine_Ticket_1116_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1116_TestCase extends Doctrine_UnitTestCase 
{
	public function setUp()
	{
		//switch to a real db to trigger the Exception
		$this->dbh = new Doctrine_Adapter_Mock('mysql');
		//$this->dbh = new PDO("mysql:host=localhost;dbname=testing", 'root', 'password');

		$this->conn = Doctrine_Manager::getInstance()->openConnection($this->dbh);
		$this->conn->export->exportClasses(array('Ticket_1116_User'));
	}


	public function testTicket()
	{
	    Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);
		$q = new Doctrine_Query();
		$q->select('s.*')
		  ->from('Ticket_1116_User s')
		  ->where('s.username = ?', array('test'));

		// to see the error switch dbh to a real db, the next line will trigger the error
		$test = $q->fetchOne();  //will only fail with "real" mysql 
		$this->assertFalse($test);

		$sql    = $q->getSqlQuery(); // just getSql()?!?! and it works ? the params are ok after this call  
		$params = $q->getFlattenedParams();
		$this->assertEqual(count($params), 1); // now we have array('test',null) very strange ..... 

		$this->assertEqual($sql, "SELECT u.id AS u__id, u.username AS u__username, u.deleted_at AS u__deleted_at FROM user u WHERE (u.username = ? AND (u.deleted_at IS NULL))");
		$this->assertEqual($params, array('test'));

		//now also this works! (always works witch mock only fails with mysql)
		$test = $q->fetchOne();
		$this->assertFalse($test);
		Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
	}
}


class Ticket_1116_User extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->setTableName('user');
		$this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('username', 'string', 255);
	}


	public function setUp()
	{
		parent::setUp();
		$softdelete0 = new Doctrine_Template_SoftDelete();
		$this->actAs($softdelete0);
	}
}