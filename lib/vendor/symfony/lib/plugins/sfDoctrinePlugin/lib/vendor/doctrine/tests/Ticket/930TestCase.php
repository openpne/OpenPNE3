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
 * Doctrine_Ticket_930_TestCase
 *
 * @package     Doctrine
 * @author      David Stendardi <david.stendardi@adenclassifieds.com>
 * @category    Hydration
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 */
class Doctrine_Ticket_930_TestCase extends Doctrine_UnitTestCase {

  /**
   * prepareData
   */

  public function prepareData()
  {
    $oPerson = new T930_Person;
    $oPerson->name = 'David';

    $oCategory = new T930_JobCategory;
    $oCategory->code = '1234';
    $oCategory->Translation['fr']->name = 'Developpement';
    $oCategory->Translation['en']->name = 'Development';
     
    $oPerson->JobPositions[0]->name = 'Webdeveloper';
    $oPerson->JobPositions[0]->Category = $oCategory;
     
    $oPerson->JobPositions[1]->name = 'Webmaster';
    $oPerson->JobPositions[1]->Category = $oCategory;

    $oPerson->save();

  }

  /**
   * prepareTables
   */

  public function prepareTables()
  {
    $this->tables = array();
    $this->tables[] = 'T930_Person';
    $this->tables[] = 'T930_JobPosition';
    $this->tables[] = 'T930_JobCategory';
     
    parent :: prepareTables();
  }


  /**
   * Test the existence expected indexes
   */

  public function testTicket()
  {
      $queryCountBefore = $this->conn->count();
    try {
      $q = new Doctrine_Query();
      $r = $q
      ->select('P.id, J.name, C.code, T.name')
      ->from('T930_Person P')
      ->leftJoin('P.JobPositions J')
      ->leftJoin('J.Category C')
      ->leftJoin('C.Translation T WITH T.lang = ?', 'fr')
      ->fetchArray();
    } catch (Exception $e) {
      $this->fail($e->getMessage());
    }
    $this->assertEqual($queryCountBefore + 1, $this->conn->count());
    $this->assertTrue(isset($r[0]['JobPositions'][0]['Category']['Translation']['fr']['name']));
    $this->assertTrue(isset($r[0]['JobPositions'][1]['Category']['Translation']['fr']['name']));
    $this->assertEqual($queryCountBefore + 1, $this->conn->count());
  }
}

class T930_Person extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('T930_person');
    $this->hasColumn('name', 'string', 200);
  }

  public function setUp()
  {
    parent :: setUp();

    $this->hasMany('T930_JobPosition as JobPositions', array(
      'local' => 'id',
      'foreign' => 'person_id',
      'onDelete' => 'CASCADE'
      ));
  }
}


class T930_JobPosition extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('T930_address');
    $this->hasColumn('name', 'string', 200);
    $this->hasColumn('person_id', 'integer');
    $this->hasColumn('job_category_id', 'integer');
  }

  public function setUp()
  {
    parent :: setUp();
    $this->hasOne('T930_Person as Person', array(
      'local' => 'person_id',
      'foreign' => 'id',
      'onDelete' => 'CASCADE'
      ));

      $this->hasOne('T930_JobCategory as Category', array(
      'local' => 'job_category_id',
      'foreign' => 'id',
      'onDelete' => 'CASCADE'
      ));
  }
}

class T930_JobCategory extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('job_category');
    $this->hasColumn('code', 'integer', 4);
    $this->hasColumn('name', 'string', 200);
  }

  public function setUp()
  {
    parent :: setUp();
    $this->hasMany('T930_JobPosition as Positions', array(
      'local' => 'id',
      'foreign' => 'job_category_id',
      'onDelete' => 'CASCADE'
      ));

      $this->actAs('I18n', array('fields' => array('name')));
  }
}




