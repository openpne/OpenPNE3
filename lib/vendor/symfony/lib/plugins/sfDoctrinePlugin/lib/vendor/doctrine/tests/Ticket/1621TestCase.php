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
 * Doctrine_Ticket_1621_TestCase
 *
 * @package     Doctrine
 * @author      floriank
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$ 
 */
class Doctrine_Ticket_1621_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1621_User';
        $this->tables[] = 'Ticket_1621_UserReference';
        $this->tables[] = 'Ticket_1621_UserReferenceFriends';
        $this->tables[] = 'Ticket_1621_EmailAdresses';
        $this->tables[] = 'Ticket_1621_Group';
        $this->tables[] = 'Ticket_1621_GroupUser';
        parent::prepareTables();
    }
    
    public function prepareData()
    {

    }

    public function testRelationAliases() {
        // this should go to $this->prepareData(), but i need 
        // it to fail in a test()-method
        try {
            $group = new Ticket_1621_Group();
            $group->name = 'group1';
            $group->save();
            
            $group2 = new Ticket_1621_Group();
            $group2->name = 'group2';
            $group2->save();
            
            $user = new Ticket_1621_User();
            $user->name = "floriank";
            $user->groups[] = $group;
            $user->emailAddresses[0]->address = "floriank@localhost";
            $user->save();
            
            $user2 = new Ticket_1621_User();
            $user2->name = "test2";
            $user2->emailAddresses[0]->address = "test2@localhost";
            $user2->save();
            
            $user3 = new Ticket_1621_User();
            $user3->name = "test3";
            $user3->emailAddresses[0]->address = "test3@localhost";
            $user3->save();
    
            $user4 = new Ticket_1621_User();
            $user4->name = "test";
            $user4->groups[] = $group2;
            $user4->children[] = $user2;
            $user4->parents[] = $user3;
            $user4->emailAddresses[0]->address = "test@localhost";
            $user4->save();
        } catch (Exception $e) {
            $this->fail($e);
        }
        
        
        
        //here is the testcode
        try {
            $user = Doctrine_Core::getTable('Ticket_1621_User')->findOneByName('floriank');
            $newChild = Doctrine_Core::getTable('Ticket_1621_User')->findOneByName('test');
            $newFriend = Doctrine_Core::getTable('Ticket_1621_User')->findOneByName('test2');
            $newGroup = Doctrine_Core::getTable('Ticket_1621_Group')->findOneByName('group2');
            
            $user->children[] = $newChild;
            $user->groups[] = $newGroup;
            $user->friends[] = $newFriend;
    
            $user->save();
            
            $this->assertEqual(count($user->children), 1);
        } catch (Exception $e) {
            $this->fail($e);
        }
    }
}
    
class Ticket_1621_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 30);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1621_User as parents', 
                                                array('local'    => 'parentId',
                                                'refClass' => 'Ticket_1621_UserReference', 
                                                'foreign'  => 'childId',
                                                'refClassRelationAlias' => 'childrenLinks'
                                                ));

        $this->hasMany('Ticket_1621_User as children', 
                                                 array('local'    => 'childId',
                                                 'foreign'  => 'parentId',
                                                 'refClass' => 'Ticket_1621_UserReference',
                                                 'refClassRelationAlias' => 'parentLinks'
                                                 ));
                                                 
        $this->hasMany('Ticket_1621_User as friends', 
                                                 array('local'    => 'leftId',
                                                 'foreign'  => 'rightId',
                                                 'equal' => 'true', 
                                                 'refClass' => 'Ticket_1621_UserReferenceFriends',
                                                 'refClassRelationAlias' => 'friendLinks'
                                                 ));
                                                 
        $this->hasMany('Ticket_1621_EmailAdresses as emailAddresses', array('local' => 'id', 'foreign' => 'userId'));

        $this->hasMany('Ticket_1621_Group as groups', array('local' => 'userId',    
                                     'foreign' => 'groupId',     
                                     'refClass' => 'Ticket_1621_GroupUser')); 
    }
}

class Ticket_1621_UserReference extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('parent_id as parentId', 'integer', null, array('primary' => true));
        $this->hasColumn('child_id as childId', 'integer', null, array('primary' => true));
    }
}

class Ticket_1621_UserReferenceFriends extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('left_id as leftId', 'integer', null, array('primary' => true));
        $this->hasColumn('right_id as rightId', 'integer', null, array('primary' => true));
    }
}

class Ticket_1621_EmailAdresses extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id as userId', 'integer', null);
        $this->hasColumn('address', 'string', 30);
        
        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }
    
    public function setUp()
    {
        $this->hasOne('Ticket_1621_User as user', array('local' => 'userId', 'foreign' => 'id')); 
    }
}

class Ticket_1621_Group extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 30);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1621_User as users', array('local' => 'groupId',    
                                     'foreign' => 'userId',     
                                     'refClass' => 'Ticket_1621_GroupUser')); 

        $this->setTableName('my_group');
    }
}

class Ticket_1621_GroupUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id as userId', 'integer', null, array('primary' => true));
        $this->hasColumn('group_id as groupId', 'integer', null, array('primary' => true));
    }
}