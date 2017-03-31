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
 * Doctrine_Ticket_7745_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_7745_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    {
        $this->tables[] = 'RecordTest1';
        $this->tables[] = 'RecordTest2';
        parent::prepareTables();
    }

    public function testDqlCallbacks()
    {
        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

        $table = Doctrine_Core::getTable('RecordTest2');
        $table->addRecordListener(new RecordTest2Listener());

        $test2 = new RecordTest2();
        $test2->name = 'test';

        $test1 = new RecordTest1();
        $test1->name = 'test';
        $test1->RecordTest2 = $test2;
        $test1->save();
        
        $id = $test2->id;
        $test2->free();

        $test2 = Doctrine_Core::getTable('RecordTest2')
            ->createQuery('a')
            ->select('a.id')
            ->where('a.id = ?', $id)
            ->fetchOne();

        $test2->load();

        $this->assertTrue($test2->RecordTest1 instanceof Doctrine_Collection);

        Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
    }
}

class RecordTest1 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
        $this->hasColumn('record_test2_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('RecordTest2', array(
            'local' => 'record_test2_id',
            'foreign' => 'id'
        ));
    }
}

class RecordTest2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
    }

    public function setUp()
    {
        $this->hasMany('RecordTest1', array(
            'local' => 'id',
            'foreign' => 'record_test2_id'
        ));
    }
}

class RecordTest2Listener extends Doctrine_Record_Listener
{
    public function preDqlSelect(Doctrine_Event $event)
    {
        $params = $event->getParams();
        $alias = $params['alias'];

        $event->getQuery()->leftJoin($alias . '.RecordTest1');
    }
}