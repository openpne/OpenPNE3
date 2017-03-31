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
 * Doctrine_Ticket_DC86_TestCase
 *
 * @package     Doctrine
 * @author      Jacek Dębowczyk <j.debowczyk@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC86_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC86_Test';
        parent::prepareTables();
    }
    
    public function prepareData()
    {
    	$r = new Ticket_DC86_Test();
    	$r->id = 1;
    	$r->date = date('Y-m-d h:i:s', strtotime("- 10 week"));
    	$r->save();

    	$r = new Ticket_DC86_Test();
    	$r->id = 2;
    	$r->date = date('Y-m-d h:i:s', strtotime("- 5 week"));
    	$r->save();

    	$r = new Ticket_DC86_Test();
    	$r->id = 3;
    	$r->date = date('Y-m-d h:i:s', strtotime("+ 1 week"));
    	$r->save();
    }

    public function testTest()
    {
    	$past = Doctrine_Query::create()->from('Ticket_DC86_Test')->addWhere('date < now()')->orderBy('date')->execute();
		$this->assertEqual(2, count($past));
		$this->assertEqual(1, $past[0]->id);
		$this->assertEqual(2, $past[1]->id);

    	$future = Doctrine_Query::create()->from('Ticket_DC86_Test')->addWhere('date > now()')->orderBy('date')->execute();
		$this->assertEqual(1, count($future));
		$this->assertEqual(3, $future[0]->id);
    }
}

class Ticket_DC86_Test extends Doctrine_Record
{
    public function setTableDefinition()
    {
		$this->hasColumn('id', 'integer', 4, array('primary', 'notnull'));
        $this->hasColumn('date', 'timestamp');
    }
}