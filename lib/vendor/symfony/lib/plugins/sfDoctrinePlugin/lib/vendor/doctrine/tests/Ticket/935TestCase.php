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
 * Doctrine_Ticket935_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_935_TestCase extends Doctrine_UnitTestCase {

    public function prepareData()
    {
        $d = new EnumUpdateBug();
        $d->id = 1;
        $d->save();
    }

    public function prepareTables() {
        $this->tables[] = 'EnumUpdateBug';
        parent::prepareTables();
    }

    public function testTicket()
    {
        try {
          $q = new Doctrine_Query();
          $q->update('EnumUpdateBug')
            ->set('bla_id', '?', 5)
            ->set('separator', '?', 'pipe')
            ->where('id = 1')
            ->execute();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
        
        $q = new Doctrine_Query();
        $row = $q->select('a.*')
                 ->from('EnumUpdateBug a')
                 ->where('a.id = 1')
                 ->fetchOne();
        
        $this->assertEqual($row->bla_id, 5);
    }
}

class EnumUpdateBug extends Doctrine_Record
{
  public function setTableDefinition()
  {
      $this->setTableName('enumupdatebug');
      $this->hasColumn('id', 'integer', 3, array('autoincrement' => true, 'unsigned' => true, 'primary' => true, 'notnull' => true));
      $this->hasColumn('bla_id', 'integer', 2, array('unsigned' => true));
      $this->hasColumn('separator', 'enum', 1, array('values' =>  array(  0 => 'comma',   1 => 'pipe', )));
  }

  public function setUp()
  {
  }
}