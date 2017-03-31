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
 * Doctrine_Ticket_DC24_TestCase
 *
 * @package     Doctrine
 * @author      Tomasz Jędrzejewski <http://www.zyxist.com/>
 * @author      Jacek Jędrzejewski <http://www.jacek.jedrzejewski.name/>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$
 */
class Doctrine_Ticket_DC24_TestCase extends Doctrine_UnitTestCase 
{
	
	public function prepareTables()
	{
        $this->tables = array('ticket_DC24_master', 'ticket_DC24_servant');
        parent::prepareTables();
	} // end prepareTables();
	
	public function prepareData()
	{
		$servant = new Ticket_DC24_Servant;
		$servant->bar = 6;
		$servant->save();
		
		$servantId = $servant->identifier();
		
		$master = new Ticket_DC24_Master;
		$master->foo = 6;
		$master->servant_id = $servantId['id'];
		$master->save();
	} // end prepareData();
	
	public function testTest()
	{
		try
		{
			$master = Doctrine_Query::create()
				->select('m.*, s.bar AS joe')
				->from('Ticket_DC24_Master m')
				->innerJoin('m.Ticket_DC24_Servant s')
				->where('m.id = 1')
				->fetchOne();
                
			$master->foo = 5;
			$master->save();
            
            $master2 = Doctrine_Query::create()
				->select('m.*')
				->from('Ticket_DC24_Master m')
				->where('m.id = 1')
				->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);
            $this->assertEqual($master2['servant_id'], 1);
		}
		catch(Exception $e)
		{
			$this->fail($e->getMessage());
		}
	} // end testTest();

} // end Doctrine_Ticket_DC24_TestCase;

class Ticket_DC24_Master extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('foo', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
        $this->hasColumn('servant_id', 'integer', 4, array(
             'type' => 'integer',
             //'notnull' => true,
             'length' => '4',
             ));
    } // end setTableDefinition();

    public function setUp()
    {
        $this->hasOne('Ticket_DC24_Servant', array(
             'local' => 'servant_id',
             'foreign' => 'id'));
    } // end setUp();
} // end Ticket_DC24_Master;

class Ticket_DC24_Servant extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('bar', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => '4',
             ));
    } // end setTableDefinition();
	
	public function setUp()
	{
		$this->hasMany('Ticket_DC24_Master as Masters', array(
             'local' => 'id',
             'foreign' => 'servant_id'));
	} // end setUp();
} // end Ticket_DC24_Servant;