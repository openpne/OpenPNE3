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
 * Doctrine_Ticket_1793_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1793_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData()
    {
        $order1 = new Ticket_1793_Order;
        $order1->status = 'new';
        $order1->save();

        /* The enum column can be changed if the value isn't equal to that of one of the column aggregation's keyValue's: */
        $order2 = new Ticket_1793_Order;
        $order2->status = 'shipped'; // 'shipped' isn't one of the column aggregation keyValue's
        $order2->save();

        // Same as $order2
        $order3 = new Ticket_1793_Order;
        $order3->status = 'shipped';
        $order3->save();

        $order4 = new Ticket_1793_Order;
        $order4->status = 'new';
        $order4->save();
    }

    public function prepareTables()
    {
        $this->tables = array('Ticket_1793_Order', 'Ticket_1793_OrdersNew', 'Ticket_1793_OrdersCompleted');
        parent::prepareTables();
    }

    public function testTicket()
    {
        // Doesn't work:
        $order1 = Doctrine_Core::getTable('Ticket_1793_Order')->find(1);
        //echo $order1->status; // 'new'
        $order1->status = 'completed';
        $order1->save();
        $this->assertEqual($order1->status, 'completed');

        // Works because previous status was not one of the column aggregation's keyValue's
        $order2 = Doctrine_Core::getTable('Ticket_1793_Order')->find(2);
        //echo $order2->status; // 'shipping'
        $order2->status = 'new';
        $order2->save();
        $this->assertEqual($order2->status, 'new');

        // This works because it reuses $order2 from above:
        //echo $order2->status; // 'new'
        $order2->status = 'completed';
        $order2->save();
        $this->assertEqual($order2->status, 'completed');

        // Works because previous status was not one of the column aggregation's keyValue's
        $order3 = Doctrine_Core::getTable('Ticket_1793_Order')->find(3);
        //echo $order2->status; // 'shipping'
        $order3->status = 'new';
        $order3->save();
        $this->assertEqual($order3->status, 'new');

        // Now this doesn't work because it's re-finding order #3 instead of re-using $order3.
        $order3 = Doctrine_Core::getTable('Ticket_1793_Order')->find(3);
        //echo $order3->status; // 'new'
        $order3->status = 'completed';
        $order3->save();
        $this->assertEqual($order3->status, 'completed');

        /* Changing the table name to Ticket_1793_OrdersNew still fails. */
        $order4 = Doctrine_Core::getTable('Ticket_1793_OrdersNew')->find(4);
        //echo $order4->status; // 'new'
        $order4->status = 'completed';
        $order4->save();
        $this->assertEqual($order4->status, 'completed');

        // This works.
        $o1 = Doctrine_Query::create()
            ->update('Ticket_1793_Order o')
            ->set('o.status', '?', 'completed')
            ->where('o.id = ?', 1)
            ->execute();
        $order1 = Doctrine_Core::getTable('Ticket_1793_Order')->find(1);
        $this->assertEqual($order1->status, 'completed');
    }

}

class Ticket_1793_Order extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('ticket_1793_orders');
    $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'unsigned' => '1', 'primary' => true, 'autoincrement' => true, 'length' => '4'));
    $this->hasColumn('status', 'enum', null, array('type' => 'enum', 'values' => array(0 => 'new', 1 => 'completed', 2 => 'shipped')));

    $this->setSubClasses(array('Ticket_1793_OrdersNew' => array('status' => 'new'), 'Ticket_1793_OrdersCompleted' => array('status' => 'completed')));
  }

}

class Ticket_1793_OrdersCompleted extends Ticket_1793_Order
{

}

class Ticket_1793_OrdersNew extends Ticket_1793_Order
{

}