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
 * Doctrine_Ticket_1706_TestCase
 *
 * @package     Doctrine
 * @author      David Abdemoulaie <doctrine@hobodave.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1706_TestCase extends Doctrine_UnitTestCase 
{
    public function testCachedResultsAreSpecificToDsn()
    {
        $cacheDriver = new Doctrine_Cache_Array();

        $conn1 = Doctrine_Manager::connection('sqlite::memory:', 'conn_1');
        $conn1->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $cacheDriver);

        $conn2 = Doctrine_Manager::connection('sqlite::memory:', 'conn_2');
        $conn2->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, $cacheDriver);
        $this->assertNotEqual($conn1, $conn2);

        $manager = Doctrine_Manager::getInstance();
        $manager->setCurrentConnection('conn_1');
        $this->assertEqual($conn1, Doctrine_Manager::connection());

        Doctrine_Core::createTablesFromArray(array('Ticket_1706_User'));

        $user = new Ticket_1706_User();
        $user->name = 'Allen';
        $user->save();

        $manager->setCurrentConnection('conn_2');
        $this->assertEqual($conn2, Doctrine_Manager::connection());

        Doctrine_Core::createTablesFromArray(array('Ticket_1706_User'));

        $user = new Ticket_1706_User();
        $user->name = 'Bob';
        $user->save();
        
        $manager->setCurrentConnection('conn_1');
        $u1 = Doctrine_Query::create()
            ->from('Ticket_1706_User u')
            ->useResultCache()
            ->execute();

        $this->assertEqual(1, count($u1));
        $this->assertEqual('Allen', $u1[0]->name);

        $manager->setCurrentConnection('conn_2');
        $u2 = Doctrine_Query::create()
            ->from('Ticket_1706_User u')
            ->useResultCache()
            ->execute();

        $this->assertEqual(1, count($u2));
        $this->assertEqual('Bob', $u2[0]->name);
    }
}

class Ticket_1706_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
        $this->hasColumn('password', 'string');
    }
}
