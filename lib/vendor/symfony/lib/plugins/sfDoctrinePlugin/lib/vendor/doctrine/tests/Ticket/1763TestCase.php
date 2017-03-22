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
 * Doctrine_Ticket_1763_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1763_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1763_User';
        parent::prepareTables();
    }

    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        $user = new Ticket_1763_User();
        $valid = $user->isValid();
        $this->assertFalse($valid);
        $string = $user->getErrorStackAsString();
        $this->_validateErrorString($string);

        try {
            $user->save();
            $this->fail();
        } catch (Exception $e) {
            $this->pass();
            $this->_validateErrorString($e->getMessage());
        }

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_NONE);
    }

    protected function _validateErrorString($string)
    {
        $this->assertTrue(strstr($string, 'Validation failed in class Ticket_1763_User'));
        $this->assertTrue(strstr($string, '3 fields had validation errors:'));
        $this->assertTrue(strstr($string, '* 1 validator failed on email_address (notnull)'));
        $this->assertTrue(strstr($string, '* 1 validator failed on username (notnull)'));
        $this->assertTrue(strstr($string, '* 1 validator failed on ip_address (notnull)'));
    }
}

class Ticket_1763_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('email_address', 'string', 255, array('unique'  => true,
                                                               'notnull' => true,
                                                               'email'   => true));
        $this->hasColumn('username', 'string', 255, array('unique'   => true,
                                                          'notnull'  => true));
        $this->hasColumn('password', 'string', 255);
        $this->hasColumn('ip_address','string', 255, array('notnull' => true, 'ip' => true));
    }
}