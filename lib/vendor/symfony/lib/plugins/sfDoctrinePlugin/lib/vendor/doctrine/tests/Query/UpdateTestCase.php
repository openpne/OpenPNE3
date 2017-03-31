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
 * Doctrine_Query_Delete_TestCase
 * This test case is used for testing DQL UPDATE queries
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Query_Update_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareData() 
    { }
    public function prepareTables() 
    {
        $this->tables = array('Entity', 'User', 'EnumTest');
        parent::prepareTables();
    }
    public function testUpdateAllWithColumnAggregationInheritance() 
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery("UPDATE User u SET u.name = 'someone'");

        $this->assertEqual($q->getSqlQuery(), "UPDATE entity SET name = 'someone' WHERE (type = 0)");

        $q = new Doctrine_Query();

        $q->update('User u')->set('u.name', "'someone'");

        $this->assertEqual($q->getSqlQuery(), "UPDATE entity SET name = 'someone' WHERE (type = 0)");
    }

    public function testUpdateWorksWithMultipleColumns() 
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery("UPDATE User u SET u.name = 'someone', u.email_id = 5");

        $this->assertEqual($q->getSqlQuery(), "UPDATE entity SET name = 'someone', email_id = 5 WHERE (type = 0)");

        $q = new Doctrine_Query();

        $q->update('User u')->set('u.name', "'someone'")->set('u.email_id', 5);

        $this->assertEqual($q->getSqlQuery(), "UPDATE entity SET name = 'someone', email_id = 5 WHERE (type = 0)");
    }
    
    public function testUpdateSupportsConditions() 
    {
        $q = new Doctrine_Query();

        $q->parseDqlQuery("UPDATE User u SET u.name = 'someone' WHERE u.id = 5");

        $this->assertEqual($q->getSqlQuery(), "UPDATE entity SET name = 'someone' WHERE (id = 5 AND (type = 0))");
    }
    public function testUpdateSupportsColumnReferencing()
    {
        $q = new Doctrine_Query();

        $q->update('User u')->set('u.id', 'u.id + 1');

        $this->assertEqual($q->getSqlQuery(), "UPDATE entity SET id = id + 1 WHERE (type = 0)");
    }
    public function testUpdateSupportsComplexExpressions()
    {
        $q = new Doctrine_Query();
        $q->update('User u')->set('u.name', "CONCAT(?, CONCAT(':', SUBSTRING(u.name, LOCATE(':', u.name)+1, LENGTH(u.name) - LOCATE(':', u.name)+1)))", array('gblanco'))
              ->where('u.id IN (SELECT u2.id FROM User u2 WHERE u2.name = ?) AND u.email_id = ?', array('guilhermeblanco', 5));
        $this->assertEqual($q->getSqlQuery(), "UPDATE entity SET name = CONCAT(?, CONCAT(':', SUBSTRING(name, LOCATE(':', name)+1, LENGTH(name) - LOCATE(':', name)+1))) WHERE (id IN (SELECT e2.id AS e2__id FROM entity e2 WHERE (e2.name = ? AND (e2.type = 0))) AND email_id = ?) AND (type = 0)");
    }
    public function testUpdateSupportsNullSetting()
    {
        $user = new User();
        $user->name = 'jon';
        $user->loginname = 'jwage';
        $user->password = 'changeme';
        $user->save();

        $id = $user->id;
        $user->free();

        $q = Doctrine_Query::create()
                ->update('User u')
                ->set('u.name', 'NULL')
                ->where('u.id = ?', $id);

        $this->assertEqual($q->getSqlQuery(), 'UPDATE entity SET name = NULL WHERE (id = ? AND (type = 0))');

        $q->execute();

        $user = Doctrine_Query::create()
                    ->from('User u')
                    ->where('u.id = ?', $id)
                    ->fetchOne();

        $this->assertEqual($user->name, '');
    }
    public function testEnumAndAnotherColumnUpdate()
    {
        $enumTest = new EnumTest();
        $enumTest->status = 'open';
        $enumTest->text = 'test';
        $enumTest->save();

        $id = $enumTest->id;
        $enumTest->free();

        $q = Doctrine_Query::create()
                ->update('EnumTest t')
                ->set('status', '?', 'closed')
                ->set('text', '?', 'test2')
                ->where('t.id = ?', $id);
        $q->execute();

        $this->assertEqual($q->getSqlQuery(), 'UPDATE enum_test SET status = ?, text = ? WHERE (id = ?)');

        $enumTest = Doctrine_Query::create()
                        ->from('EnumTest t')
                        ->where('t.id = ?', $id)
                        ->fetchOne();

        $this->assertEqual($enumTest->status, 'closed');
        $this->assertEqual($enumTest->text, 'test2');
    }
}