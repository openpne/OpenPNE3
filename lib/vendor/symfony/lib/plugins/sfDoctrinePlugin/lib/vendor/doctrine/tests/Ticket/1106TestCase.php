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
 * Doctrine_Ticket_1106_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1106_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        parent::prepareTables();
    }
    
    public function prepareData()
    {
        $user = new User();
        $user->name = 'John';
        $user->Group[0]->name = 'Original Group';
        $user->save();
        
        $this->user_id = $user['id'];
    }
    
    public function testAfterOriginalSave()
    {
        $user = Doctrine_Query::create()->from('User u, u.Group')->fetchOne();
        $this->assertEqual($user->name, 'John');
        $this->assertEqual($user->Group[0]->name, 'Original Group');
    }
    
    public function testModifyRelatedRecord()
    {
        $user = Doctrine_Query::create()->from('User u, u.Group')->fetchOne();
        
        // Modify Record
        $user->name = 'Stephen';
        $user->Group[0]->name = 'New Group';
        
        // Test After change and before save
        $this->assertEqual($user->name, 'Stephen');
        $this->assertEqual($user->Group[0]->name, 'New Group');
        
        $user->save();
        
        // Test after save
        $this->assertEqual($user->name, 'Stephen');
        $this->assertEqual($user->Group[0]->name, 'New Group');
    }
    
    public function testQueryAfterSave()
    {
        $user = Doctrine_Core::getTable('User')->find($this->user_id);
        $this->assertEqual($user->name, 'Stephen');
        $this->assertEqual($user->Group[0]->name, 'New Group');
    }

}
