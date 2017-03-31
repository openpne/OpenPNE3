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
 * Doctrine_Ticket_1653_TestCase
 *
 * @package     Doctrine
 * @author      floriank
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$ 
 */
class Doctrine_Ticket_1653_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1653_User';
        $this->tables[] = 'Ticket_1653_Email';
        parent::prepareTables();
    }
    
    public function prepareData()
    {

    }

    public function testValidate()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        
        $user = new Ticket_1653_User();
        $mail = new Ticket_1653_Email();
        
        $user->id = 1;
        $user->name = "floriank";
        $user->emails[] = $mail;
        
        //explicit call of isValid() should return false since $mail->address is null

        $this->assertFalse($user->isValid(true));

        //reset validation to default for further testcases
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_NONE);
    }

    public function testModified()
    {
        $user = new Ticket_1653_User();
        $mail = new Ticket_1653_Email();
        $mail->address = 'test';
        $user->emails[] = $mail;

        // Should return true since one of its relationships is modified
        $this->assertTrue($user->isModified(true));

        $user = new Ticket_1653_User();
        $this->assertFalse($user->isModified(true));
        $user->name = 'floriank';
        $this->assertTrue($user->isModified(true));
    }
}

class Ticket_1653_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }
    
    public function setUp()
    {
        $this->hasMany('Ticket_1653_Email as emails', array('local' => 'id',
                                                  'foreign' => 'user_id',
                                                  'cascade' => array('delete')));
    }
    
    protected function validate()
    {
        if ($this->name == "test") {
            $this->getErrorStack()->add("badName", "No testnames allowed!");
            return false;
        }
    }
}

class Ticket_1653_Email extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer');
        $this->hasColumn('address', 'string', 255, array('notnull' => true));
    }
    
    public function setUp()
    {
        $this->hasOne('Ticket_1653_User as user', array('local' => 'user_id',
                                                  'foreign' => 'id',
                                                  'cascade' => array('delete')));
    }
}