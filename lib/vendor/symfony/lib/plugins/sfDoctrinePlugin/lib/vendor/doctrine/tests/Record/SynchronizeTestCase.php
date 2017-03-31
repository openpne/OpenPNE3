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
 * Doctrine_Record_State_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Record_Synchronize_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        $user = new User();
        $user->name = 'John';
        $user->Email->address = 'john@mail.com';
        $user->Phonenumber[0]->phonenumber = '555 123';
        $user->Phonenumber[1]->phonenumber = '555 448';
        $user->save();
        
        # Create an existing group
        $group = new Group();
        $group->name = 'Group One';
        $group->save();
        $this->previous_group = $group['id'];
    }

    public function testSynchronizeRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Email, u.Phonenumber')->fetchOne();
        $userArray = $user->toArray(true);
        $this->assertEqual($user->Phonenumber->count(), 2);
        $this->assertEqual($user->Phonenumber[0]->phonenumber, '555 123');

        // modify a Phonenumber
        $userArray['Phonenumber'][0]['phonenumber'] = '555 321';

        // delete a Phonenumber
        array_pop($userArray['Phonenumber']);
        
        // add group
        $userArray['Group'][]['name'] = 'New Group'; # This is a n-m relationship
        // add a group which exists
        $userArray['Group'][1]['_identifier'] = $this->previous_group; # This is a n-m relationship where the group was made in prepareData

        $user->synchronizeWithArray($userArray);
        $this->assertEqual($user->Phonenumber->count(), 1);
        $this->assertEqual($user->Phonenumber[0]->phonenumber, '555 321');
        $this->assertEqual($user->Group[0]->name, 'New Group');
        $this->assertEqual($user->Group[1]->name, 'Group One');

        // change Email
        $userArray['Email']['address'] = 'johndow@mail.com';
        try {
          $user->synchronizeWithArray($userArray);
        } catch (Exception $e) {
          $this->fail($e->getMessage());
        }

        $this->assertEqual($user->Email->address, 'johndow@mail.com');

        try {
          $user->save();
        } catch (Exception $e ) {
          $this->fail("Failed saving with " . $e->getMessage());
        }
    }

    public function testSynchronizeAfterSaveRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Group g, u.Email e, u.Phonenumber p')->fetchOne();
        $this->assertEqual($user->Phonenumber->count(), 1);
        $this->assertEqual($user->Phonenumber[0]->phonenumber, '555 321');
        $this->assertEqual($user->Email->address, 'johndow@mail.com');
        $this->assertEqual($user->Group[0]->name, 'New Group');
        $this->assertEqual($user->Group[1]->name, 'Group One');
    }

    public function testSynchronizeAddRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Email, u.Phonenumber')->fetchOne();
        $userArray = $user->toArray(true);
        $userArray['Phonenumber'][] = array('phonenumber' => '333 238');

        $user->synchronizeWithArray($userArray);
        
        $this->assertEqual($user->Phonenumber->count(), 2);
        $this->assertEqual($user->Phonenumber[1]->phonenumber, '333 238');
        $user->save();
    }

    public function testSynchronizeAfterAddRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Email, u.Phonenumber')->fetchOne();
        
        $this->assertEqual($user->Phonenumber->count(), 2);
        $this->assertEqual($user->Phonenumber[1]->phonenumber, '333 238');
    }

    public function testSynchronizeRemoveRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Email, u.Phonenumber')->fetchOne();
        $userArray = $user->toArray(true);
        unset($userArray['Phonenumber']);
        unset($userArray['Email']);
        unset($userArray['email_id']);

        $user->synchronizeWithArray($userArray);
        $this->assertEqual($user->Phonenumber->count(), 0);
        $this->assertTrue(!isset($user->Email));
        $user->save();
    }

    public function testSynchronizeAfterRemoveRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Email, u.Phonenumber')->fetchOne();
        $this->assertEqual($user->Phonenumber->count(), 0);
        $this->assertTrue(!isset($user->Email));
    }
}
