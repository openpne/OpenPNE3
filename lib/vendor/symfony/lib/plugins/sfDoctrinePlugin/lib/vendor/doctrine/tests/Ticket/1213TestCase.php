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
 * Doctrine_Ticket_1213_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1213_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Doctrine_Ticket_1213_Person';
        $this->tables[] = 'Doctrine_Ticket_1213_Birthday';
        parent::prepareTables();
    }

    public function testTest()
    {
        $guid = md5(microtime());
        $person = new Doctrine_Ticket_1213_Person();
        $person->Name	= "Frank Zappa ".time();
        $person->guid	= $guid;
        $person->Birthday->Bday = '1940-12-21';
        $person->Birthday->person_guid = $guid;
        $person->save();
        $this->assertEqual($person->guid, $guid);
        $this->assertEqual($person->Birthday->person_guid, $guid);
    }
}

class Doctrine_Ticket_1213_Birthday extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('person_guid', 'string', 32, array('primary' => true));
		$this->hasColumn('Bday', 'timestamp');

		$this->index('person_guid', array('fields' => array('person_guid')));
	}
}

class Doctrine_Ticket_1213_Person extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('guid', 'string', 32, array('primary' => true));
		$this->hasColumn('Name', 'string', 100);

		$this->index('guid', array('fields' => array('guid')));
	}

	public function setUp()
	{
		$this->hasOne('Doctrine_Ticket_1213_Birthday as Birthday', array('local'    => 'guid',
		                                                                 'foreign'  => 'person_guid',
		                                                                 'owningSide' => true));
	}
}