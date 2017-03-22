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
 * Doctrine_Ticket_832_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_832_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $manager = Doctrine_Manager::getInstance();
        $manager->setImpl('Ticket_832_UserTemplate', 'Ticket_832_User')
                ->setImpl('Ticket_832_EmailTemplate', 'Ticket_832_Email');

        $this->tables[] = 'Ticket_832_User';
        $this->tables[] = 'Ticket_832_Email';
        parent::prepareTables();
    }

    public function testTest()
    {
        try {
            $user = new Ticket_832_User();
            $user->name = 'test';
            $user->save();
            $this->assertEqual($user->name, 'test');
            $this->assertTrue($user->id > 0);
            $this->pass();
        } catch (Doctrine_Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class Ticket_832_UserTemplate extends Doctrine_Template
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
    }
    public function setUp()
    {
        $this->hasMany('Ticket_832_EmailTemplate as Email', array('local' => 'id', 'foreign' => 'user_id'));
    }
}

class Ticket_832_EmailTemplate extends Doctrine_Template
{
    public function setTableDefinition()
    {
        $this->hasColumn('address', 'string');
        $this->hasColumn('user_id', 'integer');
    }
    public function setUp()
    {
        $this->hasOne('Ticket_832_UserTemplate as User', array('local' => 'user_id', 'foreign' => 'id'));
    }
}

class Ticket_832_User extends Doctrine_Record
{
    public function setUp()
    {
        $this->actAs('Ticket_832_UserTemplate');
    }
}

class Ticket_832_Email extends Doctrine_Record
{
    public function setUp()
    {
        $this->actAs('Ticket_832_EmailTemplate');
    }
}