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
 * Doctrine_Ticket_1562_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1562_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1562_User';
        parent::prepareTables();
    }

    public function testTest()
    {
        $table = Doctrine_Core::getTable('Ticket_1562_User');
        $this->assertEqual(get_class($table), 'Ticket_1562_UserTable');
        $user = new Ticket_1562_User();
        $this->assertEqual($user->getTestAccessor(), 'Ticket_1562_Template::getTestAccessor');
        $this->assertEqual($table->getTestAccessor(), 'Ticket_1562_Template::getTestAccessorTableProxy');

        try {
            $user->invalidMethod();
            $this->fail();
        } catch (Exception $e) {
            $this->pass();
        }

        try {
            $table->getTestAccessor2();
        } catch (Exception $e) {
            if ($e->getMessage() == 'Test') {
                $this->pass();
            } else {
                $this->fail('Exception thrown should have had a message of test');
            }
        }
    }
}

class Ticket_1562_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('Ticket_1562_Template');
    }
}

class Ticket_1562_UserTable extends Doctrine_Table
{
    
}

class Ticket_1562_Template extends Doctrine_Template
{
    public function getTestAccessor()
    {
        return __METHOD__;
    }

    public function getTestAccessorTableProxy()
    {
        return __METHOD__;
    }

    public function getTestAccessor2TableProxy()
    {
        throw new Doctrine_Exception('Test');
    }
}