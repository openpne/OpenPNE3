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
 * Doctrine_Record_FromArray_TestCase
 *
 * @package     Doctrine
 * @author      Stephen Ostrow <sostrow@sowebdesigns.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Record_FromArray_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        # Create an existing group
        $group = new Group();
        $group->name = 'Group One';
        $group->save();
        $this->previous_group = $group['id'];
    }

    public function testFromArrayRecord()
    {
        $user = new User();
        $userArray = $user->toArray();

        # add a Phonenumber
        $userArray['Phonenumber'][0]['phonenumber'] = '555 321';
        
        # add an Email address
        $userArray['Email']['address'] = 'johndow@mail.com';
        
        # add group
        $userArray['Group'][0]['name'] = 'New Group'; # This is a n-m relationship
        # add a group which exists
        $userArray['Group'][1]['_identifier'] = $this->previous_group; # This is a n-m relationship where the group was made in prepareData
          
        $user->fromArray($userArray);
        
        $this->assertEqual($user->Phonenumber->count(), 1);
        $this->assertEqual($user->Phonenumber[0]->phonenumber, '555 321');
        $this->assertEqual($user->Group[0]->name, 'New Group');
        $this->assertEqual($user->Group[1]->name, 'Group One');
        
        try {
          $user->save();
        } catch (Exception $e ) {
          $this->fail("Failed saving with " . $e->getMessage());
        }
    }

    public function testFromArrayAfterSaveRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Email, u.Phonenumber, u.Group')->fetchOne();
        $this->assertEqual($user->Phonenumber->count(), 1);
        $this->assertEqual($user->Phonenumber[0]->phonenumber, '555 321');
        $this->assertEqual($user->Email->address, 'johndow@mail.com');
        $this->assertEqual($user->Group[0]->name, 'New Group');
        $this->assertEqual($user->Group[1]->name, 'Group One');
    }
}