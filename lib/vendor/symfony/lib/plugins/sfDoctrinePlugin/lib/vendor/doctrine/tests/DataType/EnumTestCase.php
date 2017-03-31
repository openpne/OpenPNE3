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
 * Doctrine_DataType_Enum_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_DataType_Enum_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareData() 
    { }
    public function prepareTables() 
    {
        $this->tables = array("EnumTest", "EnumTest2", "EnumTest3");
        parent::prepareTables();
    }

    public function testParameterConversion() 
    {
        $test = new EnumTest();
        $test->status = 'open';
        $this->assertEqual($test->status, 'open');
        $test->save();
        
        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->query("FROM EnumTest WHERE EnumTest.status = 'open'");
            $this->assertEqual(count($ret), 1);
        } catch (Exception $e) {
          $this->fail();
        }
    }
    
    public function testUpdate()
    {
        $test = new EnumTest2();
        $test->status = 'open';
        $this->assertEqual($test->status, 'open');
        $test->save();

        $test_update = Doctrine_Core::getTable('EnumTest2')->find(1);
        $test_update->status = 'verified';
        $this->assertEqual($test_update->status, 'verified');
        $test_update->save();
    }

    public function testDqlUpdate()
    {
        $query = new Doctrine_Query($this->connection);
        $query->update('EnumTest2 u')
            ->set('u.status', '?', 'verified');

        $this->assertEqual($query->getSqlQuery(), 'UPDATE enum_test2 SET status = ?');   

        $query->execute();

        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->query("FROM EnumTest2 WHERE EnumTest2.status = 'verified'");
            $this->assertEqual(count($ret), 1);
        } catch (Exception $e) {
            $this->fail();
        }
    }
    
    public function testParameterConversionInCount() 
    {
        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->parseDqlQuery("FROM EnumTest WHERE EnumTest.status = 'open'")
              ->count();
            $this->assertEqual($ret, 1);
        } catch (Exception $e) {
            $this->fail();
        }

        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->parseDqlQuery("FROM EnumTest WHERE EnumTest.status = ?")
              ->count(array('open'));
            $this->assertEqual($ret, 1);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    public function testInAndNotIn() 
    {
        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->query("FROM EnumTest WHERE EnumTest.status IN ('open')");
            $this->assertEqual(count($ret), 1);
        } catch (Exception $e) {
            $this->fail();
        }

        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->query("FROM EnumTest WHERE EnumTest.status NOT IN ('verified', 'closed')");
            $this->assertEqual(count($ret), 1);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testExpressionComposition() 
    {
        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->query("FROM EnumTest e WHERE e.id > 0 AND (e.status != 'closed' OR e.status = 'verified')");
            $this->assertEqual(count($ret), 1);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testNotEqual() 
    {
        try {
            $query = new Doctrine_Query($this->connection);
            $ret = $query->query("FROM EnumTest WHERE EnumTest.status != 'closed'");
            $this->assertEqual(count($ret), 1);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testEnumType() 
    {

        $enum = new EnumTest();
        $enum->status = 'open';
        $this->assertEqual($enum->status, 'open');
        $enum->save();
        $this->assertEqual($enum->status, 'open');
        $enum->refresh();
        $this->assertEqual($enum->status, 'open');

        $enum->status = 'closed';

        $this->assertEqual($enum->status, 'closed');

        $enum->save();
        $this->assertEqual($enum->status, 'closed');
        $this->assertTrue(is_numeric($enum->id));
        $enum->refresh();
        $this->assertEqual($enum->status, 'closed');
    }

    public function testEnumTypeWithCaseConversion() 
    {
        $this->conn->setAttribute(PDO::ATTR_CASE, PDO::CASE_UPPER);

        $enum = new EnumTest();

        $enum->status = 'open';
        $this->assertEqual($enum->status, 'open');

        $enum->save();
        $this->assertEqual($enum->status, 'open');

        $enum->refresh();
        $this->assertEqual($enum->status, 'open');      
        
        $enum->status = 'closed';

        $this->assertEqual($enum->status, 'closed');

        $enum->save();
        $this->assertEqual($enum->status, 'closed');

        $enum->refresh();
        $this->assertEqual($enum->status, 'closed');

        $this->conn->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
    }

    public function testFailingRefresh() 
    {
        $enum = $this->connection->getTable('EnumTest')->find(1);

        $this->conn->exec('DELETE FROM enum_test WHERE id = 1');

        try {
            $enum->refresh();

            $this->fail();
        } catch(Doctrine_Record_Exception $e) {
            $this->pass();
        }
    }

    public function testEnumFetchArray()
    {
        $q = new Doctrine_Query();
        $q->select('e.*')
          ->from('EnumTest e')
          ->limit(1);
        $ret = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

        if (is_numeric($ret[0]['status'])) {
            $this->fail();
        }
    }

    public function testLiteralEnumValueConversionSupportsJoins()
    {
        $q = new Doctrine_Query($this->connection);
        $q->addSelect('e.*')
          ->addSelect('e3.*')
          ->from('EnumTest e')
          ->leftJoin('e.Enum3 e3')
          ->where("e.status = 'verified'")
          ->execute();

        $this->assertEqual($q->getSqlQuery(), "SELECT e.id AS e__id, e.status AS e__status, e.text AS e__text, e2.text AS e2__text FROM enum_test e LEFT JOIN enum_test3 e2 ON e.text = e2.text WHERE (e.status = 'verified')");
    }

    public function testInvalidValueErrors()
    {
        $orig = Doctrine_Manager::getInstance()->getAttribute(Doctrine_Core::ATTR_VALIDATE);
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
        try {
            $test = new EnumTest();
            $test->status = 'opeerertn';
            $test->save();
            $this->fail();
        } catch (Exception $e) {
            $this->pass();
        }
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_VALIDATE, $orig);
    }
}