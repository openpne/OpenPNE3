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
 * Doctrine_CascadingDelete_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Record_CascadingDelete_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareData()
    { }
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'ForeignKeyTest';
        $this->tables[] = 'CascadeDelete_HouseOwner';
        $this->tables[] = 'CascadeDelete_House';
        $this->tables[] = 'CascadeDelete_CompositeKeyItem';
        $this->tables[] = 'CascadeDelete_ManyManySideA';
        $this->tables[] = 'CascadeDelete_ManyManySideB';
        $this->tables[] = 'CascadeDelete_ManyManyAToB';
        parent::prepareTables();
    }
    public function testCascadingDeleteEmulation()
    {
        $r = new ForeignKeyTest;
        $r->name = 'Parent';
        $r->Children[0]->name = 'Child 1';
        $this->assertEqual($r->id, null);
        $this->assertEqual($r->Children[0]->id, null);
        $r->save();

        $this->assertEqual($r->id, 1);
        $this->assertEqual($r->Children[0]->id, 2);

        $this->connection->clear();

        $r = $this->connection->query('FROM ForeignKeyTest');
        
        $this->assertEqual($r->count(), 2);
        
        // should delete the first child
        $r[0]->delete();
        
        $this->assertEqual(Doctrine_Record::STATE_TCLEAN, $r[0]->state());
        $this->assertEqual(Doctrine_Record::STATE_TCLEAN, $r[0]->Children[0]->state());

        $this->connection->clear();

        $r = $this->connection->query('FROM ForeignKeyTest');

        $this->assertEqual($r->count(), 0);
    }
    
    public function testCascadingDeleteEmulationWithListenerInvocations()
    {
        $cascadeListener = new CascadeDeleteListener($this);
        $this->conn->getTable('ForeignKeyTest')->addRecordListener($cascadeListener);
        
        $r = new ForeignKeyTest;
        $r->name = 'Parent';
        $r->Children[0]->name = 'Child 1';
        $r->Children[0]->Children[0]->name = 'Child 1 Child 1';
        $r->Children[1]->name = 'Child 2';
        $r->save();

        $this->connection->clear();

        $r = $this->connection->query('FROM ForeignKeyTest');
        
        $this->assertEqual($r->count(), 4);

        // should delete the children recursively
        $r[0]->delete();
        
        // validate listener invocations
        $this->assertTrue($cascadeListener->preDeleteInvoked);
        $this->assertEqual(4, $cascadeListener->preDeleteInvocationCount);
        $this->assertTrue($cascadeListener->postDeleteInvoked);
        $this->assertEqual(4, $cascadeListener->postDeleteInvocationCount);
        $cascadeListener->reset();
        
        $this->connection->clear();

        $r = $this->connection->query('FROM ForeignKeyTest');
        $this->assertEqual($r->count(), 0);
    }
    
    public function testBidirectionalCascadeDeleteDoesNotCauseInfiniteLoop()
    {        
        $house = new CascadeDelete_House();
        $house->bathrooms = 4;
        $owner = new CascadeDelete_HouseOwner();
        $owner->name = 'Bill Clinton';
        $owner->house = $house;
        $house->owner = $owner;
        $owner->save();
        
        $this->assertEqual(Doctrine_Record::STATE_CLEAN, $owner->state());
        $this->assertEqual(Doctrine_Record::STATE_CLEAN, $house->state());
        $this->assertTrue($owner->exists());
        $this->assertTrue($house->exists());
        
        $house->delete();
        
        $this->assertEqual(Doctrine_Record::STATE_TCLEAN, $owner->state());
        $this->assertEqual(Doctrine_Record::STATE_TCLEAN, $house->state());
        $this->assertFalse($owner->exists());
        $this->assertFalse($house->exists());
        
    }
    
    public function testCascadingDeleteInOneToZeroOrOneRelation()
    {
        $owner = new CascadeDelete_HouseOwner();
        $owner->name = 'Jeff Bridges';
        $owner->save();
        try {
            $owner->delete();
            $this->pass();
        } catch (Doctrine_Exception $e) {
            $this->fail("Failed to delete record. Message:" . $e->getMessage());
        }
        
    }
    
    public function testDeletionOfCompositeKeys()
    {
        $compItem = new CascadeDelete_CompositeKeyItem();
        $compItem->id1 = 10;
        $compItem->id2 = 11;
        $compItem->save();
        $compItem->delete();
        
        $this->assertEqual(Doctrine_Record::STATE_TCLEAN, $compItem->state());
        $this->assertFalse($compItem->exists());
    }
    
    public function testCascadeDeleteManyMany()
    {
        $a1 = new CascadeDelete_ManyManySideA();
        $a1->name = 'some';
        $b1 = new CascadeDelete_ManyManySideB();
        $b1->name = 'other';
        $a1->Bs[] = $b1;
        //$b1->As[] = $a1; <- This causes 2 insertions into the AToB table => BUG
        
        $a1->save();
        
        $a1->delete();
        
        $this->assertEqual(Doctrine_Record::STATE_TCLEAN, $a1->state());
        $this->assertFalse($a1->exists());
        $this->assertEqual(Doctrine_Record::STATE_TCLEAN, $b1->state());
        $this->assertFalse($b1->exists());
        
        $a1->refreshRelated('assocsA');
        $this->assertEqual(0, count($a1->assocsA));
        $b1->refreshRelated('assocsB');
        $this->assertEqual(0, count($b1->assocsB));
    }
}

/* This listener is used to verify the correct invocations of listeners during the
   delete procedure, as well as to verify the object states at the defined points. */
class CascadeDeleteListener extends Doctrine_Record_Listener {
    
    private $_test;
    public $preDeleteInvoked = false;
    public $preDeleteInvocationCount = 0;
    public $postDeleteInvoked = false;
    public $postDeleteInvocationCount = 0;
    
    public function __construct($test) {
        $this->_test = $test;
    }
    
    public function preDelete(Doctrine_Event $event) {
        $this->_test->assertEqual(Doctrine_Record::STATE_CLEAN, $event->getInvoker()->state());
        $this->preDeleteInvoked = true;
        $this->preDeleteInvocationCount++;
    }
    
    public function postDelete(Doctrine_Event $event) {
        $this->_test->assertEqual(Doctrine_Record::STATE_TCLEAN, $event->getInvoker()->state());
        $this->postDeleteInvoked = true;
        $this->postDeleteInvocationCount++;
    }
    
    public function reset() {
        $this->preDeleteInvoked = false;
        $this->preDeleteInvocationCount = 0;
        $this->postDeleteInvoked = false;
        $this->postDeleteInvocationCount = 0;
    }
    
}

/* The following is a typical one-to-one cascade => delete scenario. The association
   is bidirectional, as is the cascade. */

class CascadeDelete_HouseOwner extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 50);
    }
    public function setUp() {
        $this->hasOne('CascadeDelete_House as house', array(
                'local' => 'id', 'foreign' => 'owner_id',
                'cascade' => array('delete')));
    }
}

class CascadeDelete_House extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('bathrooms', 'integer', 1);
        $this->hasColumn('owner_id', 'integer', 4);
    }
    public function setUp() {
        $this->hasOne('CascadeDelete_HouseOwner as owner', array(
                'local' => 'owner_id', 'foreign' => 'id',
                'cascade' => array('delete')));
    }
}


/* The following is just a stand-alone class with a composite-key to test the new
   deletion routines with composite keys. Composite foreign keys are currently not
   supported, so we can't test this class in a cascade => delete scenario. */

class CascadeDelete_CompositeKeyItem extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id1', 'integer', 4, array('primary' => true));
        $this->hasColumn('id2', 'integer', 4, array('primary' => true));
    }
}


/* The following is an app-level cascade => delete setup of a many-many association
   Note that such a scenario is very unlikely in the real world and also pretty 
   slow. */

class CascadeDelete_ManyManySideA extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 4);
    }
    public function setUp() {
        $this->hasMany('CascadeDelete_ManyManySideB as Bs', array(
                'local' => 'a_id', 'foreign' => 'b_id',
                'refClass' => 'CascadeDelete_ManyManyAToB',
                'cascade' => array('delete')));
                
        // overrides the doctrine-generated relation to the association class
        // in order to apply the app-level cascade
        $this->hasMany('CascadeDelete_ManyManyAToB as assocsA', array(
                'local' => 'id', 'foreign' => 'a_id',
                'cascade' => array('delete')));
    }
}

class CascadeDelete_ManyManySideB extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 4);
    }
    public function setUp() {
        $this->hasMany('CascadeDelete_ManyManySideA as As', array(
                'local' => 'b_id', 'foreign' => 'a_id',
                'refClass' => 'CascadeDelete_ManyManyAToB',
                'cascade' => array('delete')));
        
        // overrides the doctrine-generated relation to the association class
        // in order to apply the app-level cascade
        $this->hasMany('CascadeDelete_ManyManyAToB as assocsB', array(
                'local' => 'id', 'foreign' => 'b_id',
                'cascade' => array('delete')));
    }
}

class CascadeDelete_ManyManyAToB extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('a_id', 'integer', 4, array('primary' => true));
        $this->hasColumn('b_id', 'integer', 4, array('primary' => true));
    }
}


