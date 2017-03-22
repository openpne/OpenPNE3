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

class DC521TestModel extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('dc521_test_model');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('data', 'string', null, array(
             'type' => 'string',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}

class DC521IdOnlyTestModel extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('dc521_idonly_test_model');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}

/**
 * Doctrine_Ticket_DC521_TestCase
 *
 * @package     Doctrine
 * @author      Gergely Kis <gergely.kis@mattakis.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.2
 * @version     $Revision$
 */
class Doctrine_Ticket_DC521_TestCase extends Doctrine_UnitTestCase 
{
    public function init()
    {
        Doctrine_Manager::connection('pgsql://test:test@localhost/doctrine', 'Pgsql');
    	   $this->driverName = 'Pgsql';
    	   parent::init();
        $this->prepareTables();
    }
    
    public function setUp()
    {
    	   $this->driverName = 'Pgsql';
        Doctrine_Manager::connection('pgsql://test:test@localhost/doctrine', 'Pgsql');
    	   parent::setUp();
    }
    
    public function testEmptyPgsqlAutoIncrementField()
    {
        $m = new DC521TestModel();
        $m->save();
        $this->assertEqual($m->id, 1);
    }

    public function testIdOnlyPgsqlAutoIncrementField()
    {
        $m = new DC521IdOnlyTestModel();
        $m->save();
        $this->assertEqual($m->id, 1);
    }
    
    public function testIdOnlyPgsqlAutoIncrementFieldWithDefinedValue()
    {
        $m = new DC521IdOnlyTestModel();
        $m->id = 9999;
        $m->save();
        $this->assertEqual($m->id, 9999);
    }
    
    public function testPgsqlAutoIncrementFieldWithDefinedValue()
    {
        $m = new DC521TestModel();
        $m->id = 9999;
        $m->save();
        $this->assertEqual($m->id, 9999);
    }
    
    public function testPgsqlAutoIncrementFieldWithDefinedValueAndData()
    {
        $m = new DC521TestModel();
        $this->assertEqual($m->id, null);
        $m->id = 111111;
        $m->data = "testdata";
        $m->save();
        $this->assertEqual($m->id, 111111);
    }
    
    public function prepareTables() 
    {
        $this->tables = array('DC521TestModel', 'DC521IdOnlyTestModel');
        parent::prepareTables();
    }
    
    public function tearDown()
    {
        Doctrine_Manager::resetInstance();
    	   $this->driverName = null;
    	   parent::tearDown();
    }
