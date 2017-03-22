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
 * Doctrine_Ticket_1652_TestCase
 *
 * @package     Doctrine
 * @author      floriank
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.1
 * @version     $Revision$
 */
class Doctrine_Ticket_1652_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'Ticket_1652_User';
        parent::prepareTables();
    }

    public function prepareData()
    {
            $user = new Ticket_1652_User();
            $user->id = 1;
            $user->name = "floriank";
            $user->save();
    }

    public function testValidate() {
        $doctrine = new ReflectionClass('Doctrine_Core');
        if ($doctrine->hasConstant('VALIDATE_USER')) {
            Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_USER);
        } else {
            //I only want my overridden Record->validate()-methods for validation
            Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE,
                                                    Doctrine_Core::VALIDATE_ALL &
                                                    ~Doctrine_Core::VALIDATE_LENGTHS &
                                                    ~Doctrine_Core::VALIDATE_CONSTRAINTS &
                                                    ~Doctrine_Core::VALIDATE_TYPES);
        }

        $user = Doctrine_Core::getTable('Ticket_1652_User')->findOneById(1);
        $user->name = "test";
        if ($user->isValid()) {
            try {
                $user->save();
            } catch (Doctrine_Validator_Exception $dve) {
                // ignore
            }
        }

        $user = Doctrine_Core::getTable('Ticket_1652_User')->findOneById(1);

        $this->assertNotEqual($user->name, 'test');
        //reset validation to default for further testcases
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_NONE);
    }
}

class Ticket_1652_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('name', 'string', 30);
    }

    protected function validate() {
        if ($this->name == "test") {
            $this->getErrorStack()->add("badName", "No testnames allowed!");
            return false;
        }
    }
}
