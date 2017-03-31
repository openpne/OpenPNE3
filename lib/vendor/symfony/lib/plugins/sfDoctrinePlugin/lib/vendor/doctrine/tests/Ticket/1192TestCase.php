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
 * Doctrine_Ticket_1192_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1192_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'Ticket_1192_CPK';
        parent::prepareTables();
    }

    public function prepareData()
    {
        $test1 = new Ticket_1192_CPK();
        $test1->user_id = 1;
        $test1->name = 'Test 1';
        $test1->save();

        $test2 = new Ticket_1192_CPK();
        $test2->user_id = 2;
        $test2->name = 'Test 2';
        $test2->save();

        $test3 = new Ticket_1192_CPK();
        $test3->user_id = 2;
        $test3->name = 'Test 3';
        $test3->save();
    }

    public function testTest()
    {
        $q = Doctrine_Query::create()
                ->from('Ticket_1192_CPK t')
                ->groupBy('t.user_id');

        $this->assertEqual($q->getCountSqlQuery(), 'SELECT COUNT(*) AS num_results FROM ticket_1192__c_p_k t GROUP BY t.user_id');
        $count = $q->count();
        $this->assertEqual($count, 2);
        $this->assertEqual($count, $q->execute()->count());
    }
}

class Ticket_1192_CPK extends Doctrine_Record
{
    public function setTableDefinition()
    {
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('user_id', 'integer', 4, array('primary' => true));
    $this->hasColumn('name', 'string', 255);
    }
}