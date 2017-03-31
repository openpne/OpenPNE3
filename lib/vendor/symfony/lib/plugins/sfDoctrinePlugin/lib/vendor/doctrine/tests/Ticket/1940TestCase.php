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
 * Doctrine_Ticket_1940_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1940_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1940_User';
        parent::prepareTables();
    }

    public function testTest()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);

        $user = new Ticket_1940_User();
        $user->fromArray(array('username' => 'jwage', 'password' => 'changeme', 'email_address' => 'jonwage@gmail.com'));

        $userArray = $user->toArray();
        $this->assertEqual($userArray['username'], 'jwage-modified');
        $this->assertEqual($userArray['password'], md5('changeme'));
        $this->assertEqual($userArray['email_address'], 'jonwage@gmail.com-modified-modified');

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, false);
    }
}

class Ticket_1940_User extends Doctrine_Record
{
	public function setTableDefinition()
	{
		$this->hasColumn('username', 'string', 255);
		$this->hasColumn('password', 'string', 255);
		$this->hasColumn('email_address', 'string', 255);

		$this->hasMutator('password', 'customSetPassword');
		$this->hasAccessor('username', 'customGetUsername');
	}

    public function getEmailAddress()
    {
        return $this->_get('email_address') . '-modified';
    }

    public function setEmailAddress($emailAddress)
    {
        $this->_set('email_address', $emailAddress . '-modified');
    }

	public function customGetUsername()
	{
		return $this->_get('username').'-modified';
	}

	public function customSetPassword($value)
	{
		return $this->_set('password', md5($value));
	}
}