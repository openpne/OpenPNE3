<?php
/**
 * Doctrine_Ticket_SegmentationFault_TestCase
 *
 * @package     Doctrine
 * @author      Tiago Ribeiro <tiago.ribeiro@gmail.com>
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @category    Object Relational Mapping
 * @link        www.doctrine-project.org
 * @since       1.0
 * @version     $Revision$
 *
 */

class Doctrine_Ticket_876_TestCase extends Doctrine_UnitTestCase
{
    public function prepareTables()
    {
        $this->tables = array('Profile', 'Person', 'sfGuardUser');

        parent::prepareTables();
    }

    public function prepareData() 
    {
    }

    public function newPerson($name)
    {
      // Creating sfGuardUser
      $guardUser = new sfGuardUser();

      $guardUser->set('name', $name);

      $guardUser->save();

      // Creating the Person
      $person = new Person();

      $person->set('name', $name);
      $person->set('sf_guard_user_id', $guardUser['id']);

      $person->save();

      return $person;
    }

    public function newProfile($name, $person)
    {
      $profile = new Profile();

      $profile->set('name', $name);
      $profile->set('person_id', $person['id']);

      $profile->save();

      return $profile;
    }

    public function testBug() 
    {
      $person = $this->newPerson('Fixe');
      $profile = $this->newProfile('Work', $person);

      $guardUser = $person->get('sfGuardUser');
      $id = $guardUser->get('id');
      
      $guardUser->free();
      
      $query = new Doctrine_Query();

      $query->select('s.*, p.*, ps.*');
      $query->from('sfGuardUser s');
      $query->innerJoin('s.Person p');
      $query->leftJoin('p.Profiles ps');
      $query->where('s.id = ?', $id);

      $user = $query->fetchOne();
      $array = $user->toArray(true);
      
      $this->assertEqual($array['id'], 1);
      $this->assertEqual($array['name'], 'Fixe');
      $this->assertTrue(isset($array['Person']['Profiles'][0]));
    }
}

class Person extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('person');
    $this->hasColumn('id', 'integer', 11, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 255);
    $this->hasColumn('sf_guard_user_id', 'integer', 4);
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('Profile as Profiles', array('local' => 'id',
                                                'foreign' => 'person_id'));
    $this->hasOne('sfGuardUser', array('local' => 'sf_guard_user_id',
                                       'foreign' => 'id',
                                       'onDelete' => 'CASCADE'));
  }
}

class Profile extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('profile');
    $this->hasColumn('id', 'integer', 11, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 150);
    $this->hasColumn('person_id', 'integer', 11);
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Person', array('local' => 'person_id',
                                'foreign' => 'id'));
  }
}

class sfGuardUser extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('sf_guard_user');
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 128, array('notnull' => true, 'unique' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Person', array('local' => 'id',
                                  'foreign' => 'sf_guard_user_id'));
  }
}