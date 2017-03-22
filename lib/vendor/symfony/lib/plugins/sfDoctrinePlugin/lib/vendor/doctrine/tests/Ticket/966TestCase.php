<?php

/**
 * @author Donald Ball
 */
class Doctrine_Ticket_966_TestCase extends Doctrine_UnitTestCase
{

  public function prepareTables()
  {
    $this->tables = array('Semester', 'Course', 'Weekday', 'CourseWeekday');
    parent::prepareTables();
  }

  public function prepareData()
  {
    $semester = new Semester();
    $semester['name'] = 'Semester';
    $semester->save();

    foreach (array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') as $name)
    {
      $weekday = new Weekday();
      $weekday['name'] = $name;
      $weekday->save();
    }

    for ($i=0; $i<3; $i++)
    {
      $course = new Course();
      $course['name'] = 'Course ' . $i;
      $course['Semester'] = $semester;
      $course->save();
      for ($w = 3; $w <6; $w++)
      {
        $cw = new CourseWeekday();
        $cw['Course'] = $course;
        $cw['weekday_id'] = $w;
        $cw->save();
      }
    }
  }

  public function testArrayHydration()
  {
    $query = Doctrine_Query::create()
      ->from('Semester s')
      ->leftJoin('s.Courses c')
      ->leftJoin('c.Weekdays cw');

    $semesters = $query->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
    $semester = $semesters[0];    
    
    $this->assertAllWeekdaysArePopulated($semester);
  }

  public function testObjectHydration()
  {
    $query = Doctrine_Query::create()
      ->from('Semester s')
      ->leftJoin('s.Courses c')
      ->leftJoin('c.Weekdays cw');

    $semester = $query->execute()->getFirst();
    
    $weekdayOids = array();
    foreach ($semester->Courses as $course) {
        foreach ($course->Weekdays as $weekday) {
            if ( ! in_array($weekday->getOid(), $weekdayOids)) {
                $weekdayOids[] = $weekday->getOid();
            }
            $this->assertTrue(is_numeric($weekday->id));
            $this->assertTrue(is_string($weekday->name));
        }
    }
    // should be only 3 weekday objects in total
    $this->assertEqual(3, count($weekdayOids));
    
    $queryCountBefore = $this->conn->count();
    $this->assertAllWeekdaysArePopulated($semester);
    $this->assertEqual($queryCountBefore, $this->conn->count());
  }

  public function testLazyObjectHydration()
  {
      // clear identity maps to make sure we're starting with a clean plate
    $this->conn->getTable('Course')->clear();
    $this->conn->getTable('Weekday')->clear();
    $this->conn->getTable('Semester')->clear();
    $query = Doctrine_Query::create()->from('Semester s');

    $semester = $query->execute()->getFirst();
    $queryCountBefore = $this->conn->count();
    $this->assertAllWeekdaysArePopulated($semester);
    // expecting 4 additional queries: 1 to fetch the courses for the only semester and
    // 1 for each weekday collection for each of the three courses.
    $this->assertEqual($queryCountBefore + 4, $this->conn->count());
  }

  private function assertAllWeekdaysArePopulated($semester)
  {
    foreach ($semester['Courses'] as $course)
    {
      foreach ($course['Weekdays'] as $weekday)
      {
            $this->assertTrue(is_numeric($weekday['id']));
            $this->assertTrue(is_string($weekday['name']));
      }
    }
  }

}

class Semester extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('semester');
    $this->hasColumn('id', 'integer', 4, array('primary'=>'true', 'autoincrement'=>'true'));
    $this->hasColumn('name', 'string', 255, array('notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasMany('Course as Courses', array('local'=>'id', 'foreign'=>'semester_id'));
  }

}

class Weekday extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('weekday');
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('name', 'string', 9, array('notnull' => true, 'unique' => true));
  }

  public function setUp()
  {
      // need to make the many-many bidirectional in order for the lazy-loading test to work.
      // lazy-loading the weekdays ($course['Weekdays']) doesnt work when the relation is
      // set up unidirectional. this is true for all many-many relations.
      $this->hasMany('Course as courses', 
        array('refClass'=>'CourseWeekday', 'local'=>'weekday_id', 'foreign'=>'course_id'));
  }
}

class Course extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('course');
    $this->hasColumn('id', 'integer', 4, array('primary'=>'true', 'autoincrement'=>'true'));
    $this->hasColumn('semester_id', 'integer', 4, array('notnull'=>true));
    $this->hasColumn('name', 'string', 255, array('notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Semester', array('local' => 'semester_id',
                                    'foreign' => 'id',
                                    'onDelete' => 'CASCADE'));
    $this->hasMany('Weekday as Weekdays', 
      array('refClass'=>'CourseWeekday', 'local'=>'course_id', 'foreign'=>'weekday_id'));
  }

}

class CourseWeekday extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('course_weekday');
    # Poor form to have an id on a join table, but that's what we were doing
    $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    $this->hasColumn('course_id', 'integer', 4, array('notnull' => true));
    $this->hasColumn('weekday_id', 'integer', 4, array('notnull' => true));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne('Course', array('local'=>'course_id', 'foreign'=>'id', 'onDelete'=>'CASCADE'));
    $this->hasOne('Weekday', array('local'=>'weekday_id', 'foreign'=>'id', 'onDelete'=>'CASCADE'));
  }

}
