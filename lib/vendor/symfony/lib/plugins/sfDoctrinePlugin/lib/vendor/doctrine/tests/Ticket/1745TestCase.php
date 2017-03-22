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
 * Doctrine_Ticket_1745_TestCase
 *
 * @package     Doctrine
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_1745_TestCase extends Doctrine_UnitTestCase {

    public function prepareTables() {
        $this->tables = array('locality');
        
        parent::prepareTables();
    }

    public function prepareData()
    {
        $locality = new Locality();
        $locality->postal_code = '1920';
        $locality->city = 'Martigny';
        $locality->save();
        
        $locality = new Locality();
        $locality->postal_code = '1965';
        $locality->city = 'Savièse';
        $locality->save();
        
        $locality = new Locality();
        $locality->postal_code = '2300';
        $locality->city = 'Neuchâtel';
        $locality->save();
    }
    
    public function testSearchable()
    {
      $query = Doctrine_Query::create()
          ->from('Locality l');
      $query = Doctrine_Core::getTable('Locality')->search('martigny', $query);
      $results = $query->fetchArray();
      $this->assertEqual($results[0]['city'], 'Martigny');
      
      $query = Doctrine_Query::create()
          ->from('Locality l');
      $query = Doctrine_Core::getTable('Locality')->search('saviese', $query);
      $results = $query->fetchArray();
      $this->assertEqual($results[0]['city'], 'Savièse');
      
      $query = Doctrine_Query::create()
          ->from('Locality l');
      $query = Doctrine_Core::getTable('Locality')->search('neuchatel', $query);
      $results = $query->fetchArray();
      $this->assertEqual($results[0]['city'], 'Neuchâtel');
    }
}

class Locality extends Doctrine_Record
{
    public function setTableDefinition()
    {
      $this->setTableName('locality');
      $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'primary' => true, 'autoincrement' => true, 'length' => '4'));
      $this->hasColumn('postal_code', 'string', 6, array('type' => 'string', 'notnull' => true, 'length' => '6'));
      $this->hasColumn('city', 'string', 120, array('type' => 'string', 'notnull' => true, 'length' => '120'));
    }

    public function setUp()
    {
      $searchable0 = new Doctrine_Template_Searchable(array('fields' => array(0 => 'city')));
      $this->actAs($searchable0);
    }
}