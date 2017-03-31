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
 * Doctrine_Ticket_1325_TestCase
 *
 * @package     Doctrine
 * @author      Andrea Baron <andrea@bhweb.it>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1325_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1325_TableName_NoAlias';
        $this->tables[] = 'Ticket_1325_TableName_Aliased';
        parent::prepareTables();
    }

    public function testShouldInsertWithoutAlias()
    {
        $elem = new Ticket_1325_TableName_NoAlias();
        $elem->id = 1;
        $elem->save();
        
        $res = Doctrine_Query::create()
            ->from('Ticket_1325_TableName_NoAlias')
            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $now = time();
        $time = strtotime($res['event_date']);
        $this->assertTrue(($now + 5 >= $time) && ($time >= $now));
    }

    public function testShouldInsertWithAlias()
    {
        $elem = new Ticket_1325_TableName_Aliased();
        $elem->id = 1;
        $elem->save();
        
        $res = Doctrine_Query::create()
            ->from('Ticket_1325_TableName_Aliased')
            ->fetchOne(array(), Doctrine_Core::HYDRATE_ARRAY);

        $now = time();
        $time = strtotime($res['eventDate']);
        $this->assertTrue(strtotime($res['eventDate']) > 0);
    }
}

class Ticket_1325_TableName_NoAlias extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    }

    public function setUp()
    {
        $this->actAs(new Doctrine_Template_Timestampable(array('created' => array('name' => 'event_date', 'type' => 'timestamp'), 'updated' => array('disabled' => true))));
    }
}

class Ticket_1325_TableName_Aliased extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    }

    public function setUp()
    {
        $this->actAs(new Doctrine_Template_Timestampable(array('created' => array('name' => 'event_date', 'alias' => 'eventDate', 'type' => 'timestamp'), 'updated' => array('disabled' => true))));
    }
}