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
 * Doctrine_Ticket_1436_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1436_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        $user = new User();
        $user->name = 'John';
        $user->save();
        
        # Create existing groups
        $group = new Group();
        $group->name = 'Group One';
        $group->save();
        $this->group_one = $group['id'];
        
        $group = new Group();
        $group->name = 'Group Two';
        $group->save();
        $this->group_two = $group['id'];
        
        $group = new Group();
        $group->name = 'Group Three';
        $group->save();
        $this->group_three = $group['id'];
    }
    
    public function testSynchronizeAddMNLinks()
    {
        $user = Doctrine_Query::create()->from('User u')->fetchOne();
        $userArray = array(
            'Group' => array(
                $this->group_one,
                $this->group_two
            )
        );

        $user->synchronizeWithArray($userArray);

        try {
          $user->save();
        } catch (Exception $e ) {
          $this->fail("Failed saving with " . $e->getMessage());
        }
    }
    public function testSynchronizeAddMNLinksAfterSave()
    {
        $user = Doctrine_Query::create()->from('User u, u.Group g')->fetchOne();
        $this->assertEqual($user->Group[0]->name, 'Group One');
        $this->assertEqual($user->Group[1]->name, 'Group Two');
        $this->assertTrue(!isset($user->Group[2]));
    }
    public function testSynchronizeChangeMNLinks()
    {
        $user = Doctrine_Query::create()->from('User u, u.Group g')->fetchOne();
        $userArray = array(
            'Group' => array(
                $this->group_two,
                $this->group_three
            )
        );
        
        $user->synchronizeWithArray($userArray);
        
        $this->assertTrue(!isset($user->Groups));
        
        try {
          $user->save();
        } catch (Exception $e ) {
          $this->fail("Failed saving with " . $e->getMessage());
        }
        
        $user->refresh();
        $user->loadReference('Group');
        
        $this->assertEqual($user->Group[0]->name, 'Group Two');
        $this->assertEqual($user->Group[1]->name, 'Group Three');
        $this->assertTrue(!isset($user->Group[2]));
    }

    public function testFromArray()
    {
        $user = new User();
        $userArray = array('Group' => array($this->group_two, $this->group_three));
        $user->fromArray($userArray);
        $this->assertEqual($user->Group[0]->name, 'Group Two');
        $this->assertEqual($user->Group[1]->name, 'Group Three');
    }

    public function testSynchronizeMNRecordsDontDeleteAfterUnlink()
    {
        $group = Doctrine_Core::getTable('Group')->find($this->group_one);
        
        $this->assertTrue(!empty($group));
        $this->assertEqual($group->name, 'Group One');
    }
}