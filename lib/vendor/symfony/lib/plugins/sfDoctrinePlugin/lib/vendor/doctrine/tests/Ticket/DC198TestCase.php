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
 * Doctrine_Ticket_DC198_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_DC198_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        $u = new Ticket_DC198_User();
        $u->name = 'test';
        $u->email->address = 'foo@bar.com';
        $u->save();

        parent::prepareData();
    }

    public function prepareTables()
    {
        $this->tables[] = 'Ticket_DC198_User';
        $this->tables[] = 'Ticket_DC198_Email';
        
        parent::prepareTables();
    }

    public function testRemoveEmail()
    {
        $u = Doctrine_Query::create()->from('Ticket_DC198_User')->fetchOne();
        $u->name = 'test2';
        $u->email->delete();
        $u->refreshRelated('email');

        // The email should be empty
        $uArray = $u->toArray();

        $this->assertFalse(isset($uArray['email']));

        // If I fetch the email I shouldn't find any
        $e = Doctrine_Query::create()->from('Ticket_DC198_Email')->fetchOne();
        $this->assertFalse($e);
    }

}

class Ticket_DC198_Email extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('address', 'string', 150, array(
             'type' => 'string',
             'length' => '150',
             ));
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC198_User', array(
             'local' => 'user_id',
             'foreign' => 'id'));
    }
}

class Ticket_DC198_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 150, array(
             'type' => 'string',
             'length' => '150',
             ));
    }

    public function setUp()
    {
        $this->hasOne('Ticket_DC198_Email as email', array(
             'local' => 'id',
             'foreign' => 'user_id'));
    }
}