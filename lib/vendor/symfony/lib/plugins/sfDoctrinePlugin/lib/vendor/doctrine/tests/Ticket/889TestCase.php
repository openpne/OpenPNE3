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
 * Doctrine_Ticket_889_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_889_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = "Ticket_889";
        $this->tables[] = "Ticket_889_Relationship";
        parent::prepareTables();
    }

	public function prepareData() 
    { }

	public function testManyTreeRelationWithSelfRelation_Children() {

        $component = new Ticket_889();

        try {
            $rel = $component->getTable()->getRelation('Children');

            $this->pass();
        } catch(Doctrine_Exception $e) {

            $this->fail();
        }
        $this->assertEqual(get_class($rel), 'Doctrine_Relation_Nest');

        $this->assertTrue($component->Children instanceof Doctrine_Collection);
        $this->assertTrue($component->Children[0] instanceof Ticket_889);
    }

	public function testManyTreeRelationWithSelfRelation_Parents() {

        $component = new Ticket_889();

        try {
            $rel = $component->getTable()->getRelation('Parents');

            $this->pass();
        } catch(Doctrine_Exception $e) {

            $this->fail();
        }
        $this->assertEqual(get_class($rel), 'Doctrine_Relation_Nest');

        $this->assertTrue($component->Parents instanceof Doctrine_Collection);
        $this->assertTrue($component->Parents[0] instanceof Ticket_889);
    }

	public function testInitData() 
    {
        $test = new Ticket_889();
		$test->table_name = 'Feature';
		$test->save();
		
		$test3 = new Ticket_889();
		$test3->table_name = 'Application';
		$test3->save();

		$test2 = new Ticket_889();
		$test2->table_name = 'Module';
		$test2->Children[0] = $test;
		$test2->Parents[0] = $test3;
		$test2->save();
    }
}

class Ticket_889 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        // set Table Name
        $this->setTableName('Ticket_889');

        // set table type
        $this->option('type', 'INNODB');

        // set character set
    	$this->option('charset', 'utf8');

        // id
        $this->hasColumn(
    			'id', 	
    			'integer', 	
    			10, 	
    			array(	'primary' => true,
    					'unsigned' => true, 	
    					'autoincrement' => true	
    			)
    	);

        // table_name
        $this->hasColumn(	
    			'table_name', 
    			'string', 
    			100, 
    			array(	'notnull' => true, 	
    					'notblank' =>true, 
    					'unique' => true 
    			)
    	);

    }

    public function setUp()
    {
        // Ticket_889_Relationship child_id
       	$this->hasMany(
			'Ticket_889 as Parents', 
			array(	'local' => 'child_id',
					'foreign' => 'parent_id', 
					'refClass' => 'Ticket_889_Relationship'	
			) 
		);

        // Ticket_889_Relationship parent_id
        $this->hasMany(
			'Ticket_889 as Children', 
			array(	'local' => 'parent_id',
					'foreign' => 'child_id', 
					'refClass' => 'Ticket_889_Relationship'
			) 
		);

       }
}

class Ticket_889_Relationship extends Doctrine_Record
{
    public function setTableDefinition()
    {
        // set Table Name
        $this->setTableName('Ticket_889_Relationship');

        // set table type
        $this->option('type', 'INNODB');

           // set character set
    		$this->option('charset', 'utf8');

        // parent_id
        $this->hasColumn(
    		'parent_id', 
    		'integer', 
    		10,
    		array( 	'primary' => true, 
    				'unsigned' => true 
    		)
    	);

        // child_id
        $this->hasColumn(
    		'child_id', 
    		'integer', 
    		10, 
    		array( 	'primary' => true, 
    				'unsigned' => true 
    		)
    	);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_889 as Parent', array('local'     => 'parent_id',
                                                    'foreign'   => 'id',
                                                    'onDelete'  => 'CASCADE'));

        $this->hasOne('Ticket_889 as Child', array('local'     => 'child_id',
                                                   'foreign'   => 'id',
                                                   'onDelete'  => 'CASCADE'));
    }
}