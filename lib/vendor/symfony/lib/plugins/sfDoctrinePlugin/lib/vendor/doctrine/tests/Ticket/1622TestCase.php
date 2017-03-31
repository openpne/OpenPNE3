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
 * Doctrine_Ticket_1622_TestCase
 *
 * @package     Doctrine
 * @author      floriank
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$ 
 */
class Doctrine_Ticket_1622_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1622_User';
        $this->tables[] = 'Ticket_1622_UserReference';
        parent::prepareTables();
    }
    
    public function prepareData()
    {
            $user = new Ticket_1622_User();
            $user->name = "floriank";
            $user->save();
            
            $user2 = new Ticket_1622_User();
            $user2->name = "test";
            $user2->parents[] = $user;
            $user2->save();
    }

    public function testUnlink() {
        $user = Doctrine_Core::getTable('Ticket_1622_User')->findOneByName('floriank');
        $child = Doctrine_Core::getTable('Ticket_1622_User')->findOneByName('test');
        
        $user->unlink('children', $child->id);
        
        $this->assertTrue($user->hasReference('children'));
        $this->assertTrue($user->hasRelation('children'));
        $this->assertEqual(count($user->children), 0);
        
        $user->save();

        $user->refresh();
        $user = Doctrine_Core::getTable('Ticket_1622_User')->findOneByName('floriank');
        $this->assertEqual(count($user->children), 0);
    }
}
    
class Ticket_1622_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 30);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1622_User as parents', 
                                                array('local'    => 'parent_id',
                                                'refClass' => 'Ticket_1622_UserReference', 
                                                'foreign'  => 'child_id',
                                                'refClassRelationAlias' => 'childrenLinks'
                                                ));
                                                
        $this->hasMany('Ticket_1622_User as children', 
                                                 array('local'    => 'child_id',
                                                 'foreign'  => 'parent_id',
                                                 'refClass' => 'Ticket_1622_UserReference',
                                                 'refClassRelationAlias' => 'parentLinks'
                                                 ));
    }
}

class Ticket_1622_UserReference extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('parent_id', 'integer', null, array('primary' => true));
        $this->hasColumn('child_id', 'integer', null, array('primary' => true));
    }
}
