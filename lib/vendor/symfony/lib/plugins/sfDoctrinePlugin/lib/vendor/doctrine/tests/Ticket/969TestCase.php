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
 * Doctrine_Ticket_969_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_969_TestCase extends Doctrine_UnitTestCase {

    public function prepareData()
    {
      $d = new T1();
      $d->t1_id = 1;
      $d->t2_id = 1;
      $d->save();
      
      $d = new T2();
      $d->t2_id = 1;
      $d->hello_id = 10;
      $d->save();
      
      for ($i = 0; $i < 10; $i++)
      {
        $t3 = new T3();
        $t3->hello_id = 10;
        $t3->save();
      }
    }

    public function prepareTables() {
      $this->tables = array();
      $this->tables[] = 'T1';
      $this->tables[] = 'T2';
      $this->tables[] = 'T3';
      
      parent::prepareTables();
    }

    public function testTicket()
    {
      $q = new Doctrine_Query;
      $result = $q->select('a.*, b.*, c.*')
                ->from('T1 a')
                ->leftJoin('a.T2 b')
                ->leftJoin('b.T3 c')
                ->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY)
                ->fetchOne();
      
      // there are 10 rows in T3, and they all have hello_id = 10, so we should have 10 rows here
      $this->assertEqual(10, count($result["T2"]["T3"]));
      
      // now with object hydration.
      $q = new Doctrine_Query;
      $result = $q->select('a.*, b.*, c.*')
                ->from('T1 a')
                ->leftJoin('a.T2 b')
                ->leftJoin('b.T3 c')
                ->fetchOne();
      
      // test that no additional queries are executed when accessing the relations (lazy-loading).
      $queryCountBefore = $this->conn->count();
      // there are 10 rows in T3, and they all have hello_id = 10, so we should have 10 rows here
      $this->assertEqual(10, count($result["T2"]["T3"]));
      $this->assertEqual($queryCountBefore, $this->conn->count());
    }
}

class T1 extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('t1');
    $this->hasColumn('t1_id', 'integer', 3, array('autoincrement' => true, 'unsigned' => true, 'primary' => true, 'notnull' => true));
    $this->hasColumn('t2_id', 'integer', 3);
  }

  public function setUp()
  {
    parent :: setUp();
    $this->hasOne('T2', array('local' => 't2_id', 'foreign' => 't2_id'));
  }
}

class T2 extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('t2');
    $this->hasColumn('t2_id', 'integer', 3, array('autoincrement' => true, 'unsigned' => true, 'primary' => true, 'notnull' => true));
    $this->hasColumn('hello_id', 'integer', 3);
  }

  public function setUp()
  {
    parent :: setUp();
    $this->hasMany('T3', array('local' => 'hello_id', 'foreign' => 'hello_id'));
  }
}

class T3 extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('t3');
    $this->hasColumn('t3_id', 'integer', 3, array('autoincrement' => true, 'unsigned' => true, 'primary' => true, 'notnull' => true));
    $this->hasColumn('hello_id', 'integer', 3);
  }

  public function setUp()
  {
    parent :: setUp();
  }
}


















