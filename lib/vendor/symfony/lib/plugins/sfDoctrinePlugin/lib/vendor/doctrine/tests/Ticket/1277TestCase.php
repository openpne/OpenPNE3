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
 * Doctrine_Ticket_1277_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1277_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables = array("T1277_User");
        parent::prepareTables();
    }

    public function prepareData() 
    {
	     $user1 = new T1277_User();
	     $user1->username = "User1";
	     $user1->email = null;
	     $user1->save();
	     
	     $user2 = new T1277_User();
	     $user2->username = "User2";
	     $user2->email = "some@email";
	     $user2->save();
	     
    }

    /**
     * Tests that:
     * 1) a record in PROXY state is switched to CLEAN state when he is queried again with all props.
     */
    public function testTicket()
    {
        $this->conn->getTable('T1277_User')->clear(); // clear identity map
        
        $q = new Doctrine_Query();
        $u = $q->select('u.id')->from('T1277_User u')->where('u.id=1')->fetchOne();

        $this->assertEqual(1, $u->id);
        $this->assertEqual(Doctrine_Record::STATE_PROXY, $u->state());        
        
        // In some other part of code I will query this table again and start making modifications to found records: 
        $q = new Doctrine_Query();
        $users = $q->select('u.*')->from('T1277_User u')->execute();
        
        $this->assertEqual(2, count($users));

        foreach ($users as $u) {
            $this->assertEqual(Doctrine_Record::STATE_CLEAN, $u->state());
            
            $u->username = 'new username' . $u->id;
            $u->email = 'some' . $u->id . '@email';
            
            $this->assertEqual("new username" . $u->id, $u->username);
            $this->assertEqual("some" . $u->id . "@email", $u->email);
        }
    }
    
    /**
     * Tests that:
     * 1) a record in PROXY state is still in PROXY state when he is queries again but not with all props
     * 2) a record in PROXY state is switched to DIRTY state and all uninitialized props loaded
     *    if one of the uninitialized properties is accessed and some other (already initialized)
     *    properties have been modified before.
     */
    public function testTicket2()
    {
        $this->conn->getTable('T1277_User')->clear(); // clear identity map
        
        $q = new Doctrine_Query();
        $u = $q->select('u.id')->from('T1277_User u')->where('u.id=1')->fetchOne();

        $this->assertEqual(1, $u->id);
        $this->assertEqual(Doctrine_Record::STATE_PROXY, $u->state());        
        
        // In some other part of code I will query this table again and start making modifications to found records: 
        $q = new Doctrine_Query();
        $users = $q->select('u.id, u.username')->from('T1277_User u')->execute();
        
        $this->assertEqual(2, count($users));

        foreach ($users as $u) {
            $this->assertEqual(Doctrine_Record::STATE_PROXY, $u->state());
            
            $u->username = 'new username' . $u->id; // modify
            $u->email = 'some' . $u->id . '@email'; // triggers load() to fill uninitialized props
            
            $this->assertEqual("new username" . $u->id, $u->username);
            $this->assertEqual("some" . $u->id . "@email", $u->email);
            
            $this->assertEqual(Doctrine_Record::STATE_DIRTY, $u->state());
        }
    }
    
    /**
     * Tests that:
     * 1) a record in PROXY state is still in PROXY state when he is queries again but not with all props
     * 2) a record in PROXY state is switched to CLEAN state and all uninitialized props loaded
     *    if one of the uninitialized properties is accessed.
     */
    public function testTicket3()
    {
        $this->conn->getTable('T1277_User')->clear(); // clear identity map
        
        $q = new Doctrine_Query();
        $u = $q->select('u.id')->from('T1277_User u')->where('u.id=1')->fetchOne();

        $this->assertEqual(1, $u->id);
        $this->assertEqual(Doctrine_Record::STATE_PROXY, $u->state());        
        
        // In some other part of code I will query this table again and start making modifications to found records: 
        $q = new Doctrine_Query();
        $users = $q->select('u.id, u.username')->from('T1277_User u')->execute();

        $this->assertEqual(2, count($users));

        foreach ($users as $u) {
            $this->assertEqual(Doctrine_Record::STATE_PROXY, $u->state());
            
            if ($u->id == 1) {
                $this->assertEqual("User1", $u->username);
                $u->email; // triggers load()
            } else {
                $this->assertEqual("User2", $u->username);
                $this->assertEqual("some@email", $u->email);
            }
            
            $this->assertEqual(Doctrine_Record::STATE_CLEAN, $u->state());
        }
    }
    
    /**
     * Fails due to the PROXY concept being flawed by design.
     *
     * Tests that:
     * 1) a record in PROXY state is switched to DIRTY state when he is queried again with all props,
     *    but has been modified before.
     */
    /*public function testTicket4()
    {
        $this->conn->getTable('T1277_User')->clear(); // clear identity map
        
        $q = new Doctrine_Query();
        $u = $q->select('u.id, u.username')->from('T1277_User u')->where('u.id=1')->fetchOne();

        $this->assertEqual(1, $u->id);
        $this->assertEqual(Doctrine_Record::STATE_PROXY, $u->state());
        
        $u->username = "superman";

        // In some other part of code I will query this table again and start making modifications to found records: 
        $q = new Doctrine_Query();
        $users = $q->select('u.*')->from('T1277_User u')->execute();
        
        $this->assertEqual(2, count($users));

        foreach ($users as $u) {
            if ($u->id == 1) {
                $this->assertEqual(Doctrine_Record::STATE_DIRTY, $u->state());
                $this->assertEqual("superman", $u->username);
            } else {
                $this->assertEqual(Doctrine_Record::STATE_CLEAN, $u->state());
                $this->assertEqual("User2", $u->username);
                $this->assertEqual("some@email", $u->email);
            }
        }
    }*/

}

// This is the User table where I have 2 records:
//      ID  USERNAME    EMAIL
//      #1  User1       NULL
//      #2  User2       some@email
class T1277_User extends Doctrine_Record
{
    public function setTableDefinition ()
    {
        $this->setTableName("t1277_users");

        $this->hasColumns (array(

            'id' => array(
                    'type'          => 'integer',
                    'length'        => 4,
                    'notnull'       => true,
                    'autoincrement' => true,
                    'primary'       => true
            ),

            'username' => array(
                    'type'          => 'string',
                    'length'        => 50
            ),

            'email' => array(
                    'type'          => 'string',
                    'length'        => 50,
                    'default'       => null,
            ),
        ));
    }
}

