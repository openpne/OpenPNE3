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
 * Doctrine_Ticket_1296_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1296_TestCase extends Doctrine_UnitTestCase
{
    public function prepareData() {
        $org = new NewTicket_Organization();
        $org->name = 'Inc.';
        $org->save();
    }
    
    public function prepareTables()
    {
        $this->tables = array(
                'NewTicket_Organization',
                'NewTicket_Role'
                );
        parent::prepareTables();
    }
    
    public function testAddDuplicateOrganisation ()
    {
        $this->assertEqual(0, $this->conn->transaction->getTransactionLevel());
        $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
        try {
            $this->conn->beginTransaction();
        } catch (Exception $e) {
            $this->fail("Transaction failed to start.");
        }
        
        $this->assertEqual(1, $this->conn->transaction->getTransactionLevel());
        $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
        
        $org = new NewTicket_Organization();
        $org->name = 'Inc.';
        try {
            $org->save();
            $this->fail("Unique violation not reported.");
        } catch (Exception $e) {
            $this->assertEqual(1, $this->conn->transaction->getTransactionLevel());
            $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
            $this->conn->rollback();
        }
        
        $this->assertEqual(0, $this->conn->transaction->getTransactionLevel());
        $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
        
        try {
            $this->assertEqual(0, $this->conn->transaction->getTransactionLevel());
            $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
            $this->conn->commit();
            $this->fail();
        } catch (Exception $e) {
            // Commit failed, there is no active transaction!
            $this->pass();
        }
        $this->assertEqual(0, $this->conn->transaction->getTransactionLevel());
        $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
    }

    public function testAddRole ()
    {
        $this->assertEqual(0, $this->conn->transaction->getTransactionLevel());
        $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
        
        try {
            $this->conn->beginTransaction();
        } catch (Exception $e) {
            $this->fail("Transaction failed to start.");
        }
        
        $this->assertEqual(1, $this->conn->transaction->getTransactionLevel());
        $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
        
        $r = new NewTicket_Role();
        $r->name = 'foo';
        try {
            $r->save();
            $this->assertEqual(1, $this->conn->transaction->getTransactionLevel());
            $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
            $this->assertTrue(is_numeric($r->id));
        } catch (Exception $e) {
            $this->fail();
            $this->conn->rollback();
        }
        try {
            $this->assertEqual(1, $this->conn->transaction->getTransactionLevel());
            $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
            $this->conn->commit();
            $this->assertEqual(0, $this->conn->transaction->getTransactionLevel());
            $this->assertEqual(0, $this->conn->transaction->getInternalTransactionLevel());
        } catch (Exception $e) {
            $this->fail();
            $this->conn->rollback();
        }
    }
}
        
class NewTicket_Organization extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array(
                'autoincrement' => true,
                'notnull' => true,
                'primary' => true
                ));
        $this->hasColumn('name', 'string', 255, array(
                'notnull' => true,
                'unique' => true
                ));
    }
}

class NewTicket_Role extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('id', 'integer', 4, array(
                'autoincrement' => true,
                'notnull' => true,
                'primary' => true
                ));
        $this->hasColumn('name', 'string', 30, array(
                'notnull' => true,
                'unique' => true
                ));
    }
}

class NewTicket_User {
    public function addOrganization ($name)
    {

    }
}

    