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
 * Doctrine_Migration_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Migration_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables[] = 'MigrationPhonenumber';
        $this->tables[] = 'MigrationUser';
        $this->tables[] = 'MigrationProfile';
        parent::prepareTables();
    }

    public function testMigration()
    {
        $migration = new Doctrine_Migration('migration_classes');
        $this->assertFalse($migration->hasMigrated());
        $migration->setCurrentVersion(3);
        $migration->migrate(0);
        $this->assertEqual($migration->getCurrentVersion(), 0);
        $this->assertEqual($migration->getLatestVersion(), 11);
        $this->assertEqual($migration->getNextVersion(), 12);
        $current = $migration->getCurrentVersion();
        $migration->setCurrentVersion(100);
        $this->assertEqual($migration->getCurrentVersion(), 100);
        $migration->setCurrentVersion($current);

        $migration->migrate(3);
        $this->assertTrue($migration->hasMigrated());
        $this->assertEqual($migration->getCurrentVersion(), 3);
        $this->assertTrue($this->conn->import->tableExists('migration_phonenumber'));
        $this->assertTrue($this->conn->import->tableExists('migration_user'));
        $this->assertTrue($this->conn->import->tableExists('migration_profile'));
        $migration->migrate(4);
        $this->assertFalse($this->conn->import->tableExists('migration_profile'));

        $migration->migrate(0);
        $this->assertEqual($migration->getCurrentVersion(), 0);
        $this->assertTrue($migration->getMigrationClass(1) instanceof AddPhonenumber);
        $this->assertTrue($migration->getMigrationClass(2) instanceof AddUser);
        $this->assertTrue($migration->getMigrationClass(3) instanceof AddProfile);
        $this->assertTrue($migration->getMigrationClass(4) instanceof DropProfile);
        $this->assertFalse($this->conn->import->tableExists('migration_phonenumber'));
        $this->assertFalse($this->conn->import->tableExists('migration_user'));
        $this->assertFalse($this->conn->import->tableExists('migration_profile'));
        $this->assertEqual(array(
          1 => 'AddPhonenumber',
          2 => 'AddUser',
          3 => 'AddProfile',
          4 => 'DropProfile',
          5 => 'Test5',
          6 => 'Test6',
          7 => 'Test7',
          8 => 'Test8',
          9 => 'Test9',
          10 => 'Test10',
          11 => 'Test11',
        ), $migration->getMigrationClasses());
    }

    public function testMigrateClearsErrors()
    {
        $migration = new Doctrine_Migration('migration_classes');
        $migration->setCurrentVersion(3);
        try {
            $migration->migrate(3);
        } catch (Doctrine_Migration_Exception $e) {
            $this->assertTrue($migration->hasErrors());
            $this->assertEqual(1, $migration->getNumErrors());
        }

        try {
            $migration->migrate(3);
        } catch (Doctrine_Migration_Exception $e) {
            $this->assertTrue($migration->hasErrors());
            $this->assertEqual(1, $migration->getNumErrors());
        }

        $migration->clearErrors();
        $this->assertFalse($migration->hasErrors());
        $this->assertEqual(0, $migration->getNumErrors());
    }

    public function testMigrationClassNameInflected()
    {
        $tests = array('test-class-Name',
                       'test_class_name',
                       'test:class:name',
                       'test(class)name',
                       'test*class*name',
                       'test class name',
                       'test&class&name');

        $builder = new Doctrine_Migration_Builder();

        foreach ($tests as $test) {
            $code = $builder->generateMigrationClass($test);
            $this->assertTrue($code);
        }
    }
}

class MigrationPhonenumber extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('user_id', 'integer');
        $this->hasColumn('phonenumber', 'string', 255);
    }
}

class MigrationUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('username', 'string', 255);
        $this->hasColumn('password', 'string', 255);
    }
}

class MigrationProfile extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
    }
}
