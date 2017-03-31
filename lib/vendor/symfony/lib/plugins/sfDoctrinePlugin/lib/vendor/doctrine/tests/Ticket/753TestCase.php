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
 * Doctrine_Ticket_753_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_753_TestCase extends Doctrine_UnitTestCase 
{
    public function testTest()
    {
        $origOptions = $this->conn->getAttribute(Doctrine_Core::ATTR_DEFAULT_COLUMN_OPTIONS);
        $this->conn->setAttribute(Doctrine_Core::ATTR_DEFAULT_COLUMN_OPTIONS, array('type' => 'string', 'length' => 255, 'notnull' => true));

        $origIdOptions = $this->conn->getAttribute(Doctrine_Core::ATTR_DEFAULT_IDENTIFIER_OPTIONS);
        $this->conn->setAttribute(Doctrine_Core::ATTR_DEFAULT_IDENTIFIER_OPTIONS, array('name' => '%s_id', 'length' => 25, 'type' => 'string', 'autoincrement' => false));

        $userTable = Doctrine_Core::getTable('Ticket_753_User');

        $definition = $userTable->getDefinitionOf('username');
        $this->assertEqual($definition, array('type' => 'string', 'length' => 255, 'notnull' => true));

        $definition = $userTable->getDefinitionOf('ticket_753__user_id');
        $this->assertEqual($definition, array('type' => 'string', 'length' => 25, 'autoincrement' => false, 'primary' => true, 'notnull' => true));

        $this->conn->setAttribute(Doctrine_Core::ATTR_DEFAULT_COLUMN_OPTIONS, $origOptions);
        $this->conn->setAttribute(Doctrine_Core::ATTR_DEFAULT_IDENTIFIER_OPTIONS, $origIdOptions);
    }
}

class Ticket_753_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username');
        $this->hasColumn('password');
    }
}