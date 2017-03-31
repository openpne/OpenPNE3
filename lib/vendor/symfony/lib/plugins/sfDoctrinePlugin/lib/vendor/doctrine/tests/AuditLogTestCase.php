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
 * Doctrine_AuditLog_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_AuditLog_TestCase extends Doctrine_UnitTestCase
{

    public function prepareData()
    { }

    public function prepareTables()
    {
        $this->profiler = new Doctrine_Connection_Profiler();
        $this->conn->addListener($this->profiler);
        $this->tables = array('VersioningTest', 'VersioningTestVersion', 'VersioningTest2');

        parent::prepareTables();
    }

    public function testVersioningListenerListensAllManipulationOperations()
    {
        $entity = new VersioningTest();

        $entity->name = 'zYne';
        $entity->save();
        $this->assertEqual($entity->version, 1);

        $entity->name = 'zYne 2';
        $entity->save();
        $this->assertEqual($entity->name, 'zYne 2');
        $this->assertEqual($entity->version, 2);
        $this->conn->clear();

        $entity = $this->conn->getTable('VersioningTest')->find(1);

        $this->assertEqual($entity->name, 'zYne 2');
        $this->assertEqual($entity->version, 2);

        $entity->revert(1);

        $this->assertEqual($entity->name, 'zYne');
        $this->assertEqual($entity->version, 1);
        $entity->save(); // new Version 3 should be created
    }

    public function testRevertThrowsExceptionForTransientRecords()
    {
        $entity = new VersioningTest();

        try {
            $entity->revert(1);
            $this->fail();
        } catch (Doctrine_Record_Exception $e) {
            $this->pass();
        }
    }

    public function testNoAuditLog()
    {
        $entity = new VersioningTest2();
        $entity->name = 'test';
        $entity->save();
        $this->assertTrue($entity->version, 1);
        $entity->name = 'test2';
        $entity->save();
        $this->assertTrue($entity->version, 2);
    }

    public function testTableName()
    {
        $entity = new VersioningTest3();
        $this->assertEqual($entity->getAuditLog()->getTable()->getTableName(), 'tbl_prefix_comments_version');
        $this->assertEqual($entity->getAuditLog()->getTable()->getComponentName(), 'VersioningTestClass');
    }


    public function testNoAuditLogThrowsExceptions()
    {
        $entity = new VersioningTest2();
        $entity->name = 'test';
        $entity->save();
        $entity->name = 'test2';
        $entity->save();

        try {
            $entity->revert(1);
            $this->fail();
        } catch (Exception $e) {
            $this->pass();
            $this->assertEqual($e->getMessage(), 'Audit log is turned off, no version history is recorded.');
        }
    }
}