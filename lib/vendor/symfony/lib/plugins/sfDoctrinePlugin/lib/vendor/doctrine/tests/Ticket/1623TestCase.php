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
 * Doctrine_Ticket_1623_TestCase
 *
 * @package     Doctrine
 * @author      floriank
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$ 
 */
class Doctrine_Ticket_1623_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1623_User';
        $this->tables[] = 'Ticket_1623_UserReference';
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        $firstUser = null;
        $oldUser = null;
        
        for ($i = 1; $i <= 20; $i++) {
            $userI = $user = new Ticket_1623_User();
            $userI->name = "test$i";
            for ($j = 1; $j <= 20; $j++) {
                $userJ = new Ticket_1623_User();
                $userJ->name = "test$i-$j";
                $userI->children[] = $userJ;
                $userJ->save();
            }
            $userI->save();
            $floriankChilds[] = $userI;
        }

        $user = new Ticket_1623_User();
        $user->name = "floriank";
        foreach ($floriankChilds as $child) {
            $user->children[] = $child;
        }
        $user->save();
    }

    public function testPerformance()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        
        $newChild = new Ticket_1623_User();
        $newChild->name = 'myChild';
        $newChild->save();
        
        $user = Doctrine_Core::getTable('Ticket_1623_User')->findOneByName('floriank');
        $user->children[] = $newChild;
        
        $start = microtime(true);
        $user->save();
        $end = microtime(true);
        $diff = $end - $start;
        //assuming save() should not take longer than one second
        $this->assertTrue($diff < 1);
    }
    
    public function testImplicitSave()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_CASCADE_SAVES, false);

        $newChild = new Ticket_1623_User();
        $newChild->name = 'myGrandGrandChild';
        
        $user = Doctrine_Core::getTable('Ticket_1623_User')->findOneByName('floriank');
        $user->children[0]->children[0]->children[] = $newChild;
        
        $user->save();
        
        $user = Doctrine_Core::getTable('Ticket_1623_User')->findByName('myGrandGrandChild');
        //as of Doctrine's default behaviour $newChild should have 
        //been implicitly saved with $user->save()  
        $this->assertEqual($user->count(), 0);

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_NONE);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_CASCADE_SAVES, true);
    }
}
    
class Ticket_1623_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 30);
    }

    public function setUp()
    {
        $this->hasMany('Ticket_1623_User as parents', 
                                                array('local'    => 'parentId',
                                                'refClass' => 'Ticket_1623_UserReference', 
                                                'foreign'  => 'childId',
                                                'refClassRelationAlias' => 'childrenLinks'
                                                ));
                                                
        $this->hasMany('Ticket_1623_User as children', 
                                                 array('local'    => 'childId',
                                                 'foreign'  => 'parentId',
                                                 'refClass' => 'Ticket_1623_UserReference',
                                                 'refClassRelationAlias' => 'parentLinks'
                                                 ));
    }
    
    protected function validate()
    {
        // lets get some silly load in the validation: 
        // we do not want any child or parent to have the name 'caesar'
        $unwantedName = false; 
        foreach ($this->children as $child) {
            if ($child->name == 'caesar') {
                $unwantedName = true;
            }
        }
        
        foreach ($this->children as $child) {
            if ($child->name == 'caesar') {
                $unwantedName = true;
            }
        }
        
        if ($unwantedName) {
            $this->errorStack()->add('children', 'no child should have the name \'caesar\'');
        }
    }
}

class Ticket_1623_UserReference extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('parent_id as parentId', 'integer', null, array('primary' => true));
        $this->hasColumn('child_id as childId', 'integer', null, array('primary' => true));
    }
}