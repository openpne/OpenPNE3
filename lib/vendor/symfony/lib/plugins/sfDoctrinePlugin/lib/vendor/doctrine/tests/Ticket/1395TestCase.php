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
 * Doctrine_Ticket_1395_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1395_TestCase extends Doctrine_UnitTestCase
{    
    public function prepareTables()
    {
        $this->tables = array();
        $this->tables[] = 'T1395_MyModel';
        parent::prepareTables();
    }

    public function prepareData()
    {
	    $myModel = new T1395_MyModel();
	    $myModel->dt_created = '2005-10-01';
	    $myModel->id = 0;
	    $myModel->save();
    }

    public function testTicket()
    {
        try {
            $myModel = $this->conn->getTable('T1395_MyModel')->find(0);
            $this->assertTrue(isset($myModel->dt_created));
            $this->assertTrue(isset($myModel->days_old)); // This is a calculated field from within the T1395_Listener::preHydrate
            $this->assertTrue(isset($myModel->dt_created_tx)); // This is a calculated field from within the T1395_Listener::preHydrate
        } catch (Doctrine_Exception $e) {
            $this->fail($e->getMessage());
        }
   }
}

class T1395_MyModel extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'notnull' => true));
        $this->hasColumn('dt_created', 'date');
    }

    public function setUp()
    {
        $this->addListener(new T1395_Listener());
    }

}

class T1395_Listener extends Doctrine_Record_Listener
{
    public function preHydrate(Doctrine_Event $event)
    {
        $data = $event->data;
        
        // Calculate days since creation
        $days = (strtotime('now') - strtotime($data['dt_created'])) / (24 * 60 * 60);
        $data['days_old'] = number_format($days, 2);

        self::addSomeData($data);
        
        $event->data = $data;
    }
    
    public static function addSomeData(&$data)
    {
        $data['dt_created_tx'] = date('M d, Y', strtotime($data['dt_created']));
    }
}