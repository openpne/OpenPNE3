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
 * Doctrine_Ticket923_TestCase
 *
 * @package     Doctrine
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_923_TestCase extends Doctrine_UnitTestCase {

    public function prepareData()
    {
        $d = new T923_Diagnostic();
        $d->id_type = 101;
        $d->id = 26;
        $d->diagnostic_id = 75444;
        $d->diag_timestamp = '2008-03-27 12:00:00';
        $d->operator_id = 1001;
        $d->save();

        $d = new T923_Diagnostic();
        $d->id_type = 101;
        $d->id = 27;
        $d->diagnostic_id = 75445;
        $d->diag_timestamp = '2008-03-27 13:00:00';
        $d->operator_id = 1001;
        $d->save();

        $d = new T923_Diagnostic();
        $d->id_type = 101;
        $d->id = 28;
        $d->diagnostic_id = 75445;
        $d->diag_timestamp = '2008-03-27 14:00:00';
        $d->operator_id = 1001;
        $d->save();
    }

    public function prepareTables() {
        $this->tables[] = 'T923_Diagnostic';
        parent::prepareTables();
    }

    public function testTicket()
    {
        try {
          $q = new Doctrine_Query();
          $result = $q->select('d.*')
          ->from('T923_Diagnostic d')
          ->where('d.diag_timestamp >= ? AND d.diag_timestamp <= ?', array('2008-03-27 00:00:00', '2008-03-27 23:00:00'))
          ->addWhere('d.id_type = ?', array('101'))
          ->orderBy('d.diag_timestamp')
          ->limit(20)
          ->offset(0)
          ->execute();
          
          $this->assertEqual($result->count(), 3);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

class T923_Diagnostic extends Doctrine_Record
{
  public function setTableDefinition()
  {
      $this->setTableName('diagnostics');
      $this->hasColumn('id_type', 'integer', 4);
      $this->hasColumn('id', 'integer', 4);
      $this->hasColumn('diagnostic_id', 'integer', 4);
      $this->hasColumn('operator_id', 'integer', 4);
      $this->hasColumn('diag_timestamp', 'timestamp', null);
  }

  public function setUp()
  {
  }
}