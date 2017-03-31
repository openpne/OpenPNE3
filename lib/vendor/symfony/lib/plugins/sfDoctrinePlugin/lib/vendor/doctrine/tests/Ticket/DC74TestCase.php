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
 * Doctrine_Ticket_DC74_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC74_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC74_Test';
        parent::prepareTables();
    }
    
    public function prepareData()
    {
    	$r = new Ticket_DC74_Test();
    	$r->test1 = 'test1';
    	$r->test2 = 'test2';
    	$r->save();
    	
    	// following clear should be done automatically, as noted in DC73 ticket
    	$r->getTable()->clear();
    }

    public function testTest()
    {
		// we are selecting "id" and "test1" fields and ommiting "test2"
    	$r1 = Doctrine_Query::create()
    		->select('id, test1')
    		->from('Ticket_DC74_Test')	
			->fetchOne();
			
    	// so we have object in PROXY state
    	$this->assertEqual(Doctrine_Record::STATE_PROXY, $r1->state());

		// now we are modifing one of loaded properties "test1"
    	$r1->test1 = 'testx';
    	
    	// so record is in DIRTY state
    	$this->assertEqual(Doctrine_Record::STATE_DIRTY, $r1->state());
    	
    	// when accessing to not loaded field "test2" no additional loading 
    	// currently such loading is performed is executed only in PROXY state
    	$this->assertEqual('test2', $r1->test2);
    }
}

class Ticket_DC74_Test extends Doctrine_Record
{
    public function setTableDefinition()
    {
		$this->hasColumn('id', 'integer', 4, array('primary', 'notnull', 'autoincrement'));
        $this->hasColumn('test1', 'string', 255);
        $this->hasColumn('test2', 'string', 255);
    }
}