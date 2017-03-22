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
 * Doctrine_Ticket_DC169_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC169_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC169_User';
        $this->tables[] = 'Ticket_DC169_Profile';
        parent::prepareTables();
    }

    public function testTest()
    {
        $user = new Ticket_DC169_User();
        $user->username = 'jwage';
        $user->password = 'changeme';
        $user->Profile->name = 'Jonathan H. Wage';
        $user->replace();

        $this->assertTrue($user->id > 0);
        $this->assertTrue(strtotime($user->created_at) > 0);
        $this->assertTrue(strtotime($user->updated_at) > 0);
        $this->assertTrue($user->Profile->id > 0);
        $this->assertEqual($user->id, $user->Profile->user_id);

        $oldDate = $user->updated_at;

        $user->username = 'jonwage';
        sleep(1);
        $user->replace();

        $this->assertTrue($user->updated_at != $oldDate);
    }
}

class Ticket_DC169_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }

    public function setUp()
    {
        $this->actAs('Timestampable');
        
        $this->hasOne('Ticket_DC169_Profile as Profile', array(
            'local' => 'id',
            'foreign' => 'user_id'
        ));
    }
}

class Ticket_DC169_Profile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer');
        $this->hasColumn('name', 'string', 255);
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC169_User as User', array(
            'local' => 'user_id',
            'foreign' => 'id'
        ));
    }
}