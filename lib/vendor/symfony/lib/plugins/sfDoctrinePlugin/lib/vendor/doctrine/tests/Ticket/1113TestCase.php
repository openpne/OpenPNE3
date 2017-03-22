<?php
class Doctrine_Ticket_1113_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareData() 
    { }
    public function prepareTables() 
    {
        $this->tables = array('VIH_Model_Course', 'VIH_Model_Course_Period', 'VIH_Model_Course_SubjectGroup', 'VIH_Model_Subject', 'VIH_Model_Course_SubjectGroup_Subject', 'VIH_Model_Course_Registration', 'VIH_Model_Course_Registration_Subject');
        
        parent::prepareTables();
    }

    public function testSubjectsCanBeRetrievedWhenReopeningTheRegistrationEvenThoughNoSubjectsWasSavedInitally()
    {
        $course1 = new VIH_Model_Course();
        $course1->navn = 'Course 1';
        
        $period1 = new VIH_Model_Course_Period();
        $period1->name = 'Period 1';
        $period1->Course = $course1;
        $period1->save();
        
        $group1 = new VIH_Model_Course_SubjectGroup();
        $group1->name = 'SubjectGroup 1';
        $group1->Period = $period1;
        
        $subject1 = new VIH_Model_Subject();
        $subject1->identifier = 'Subject 1';
        
        $subject2 = new VIH_Model_Subject();
        $subject2->identifier = 'Subject 2';
        
        $group1->Subjects[] = $subject1;
        $group1->Subjects[] = $subject2;
        
        $group1->save();
                
        $group1->Subjects[] = $subject1;
        $group1->Subjects[] = $subject2;
        $group1->save();
        
        $course1->SubjectGroups[] = $group1;
        
        $course1->save();
                
        // saved without Subjects
        try {
            $registrar = new VIH_Model_Course_Registration();
            $registrar->Course = $course1;
            // $registrar->Subjects; // if this is uncommented the test will pass
            $registrar->save();
        } catch (Doctrine_Record_Exception $e) {
            $this->fail($e->getMessage());
        }

        $reopend = Doctrine_Core::getTable('VIH_Model_Course_Registration')->findOneById($registrar->id);

        try {
            $reopend->Subjects[] = $subject1;
        } catch (Doctrine_Record_Exception $e) {
            $this->fail($e->getMessage());
        }

        $reopend->save();
      
        try {
            $subject = $reopend->Subjects[0];
            $this->assertTrue(is_object($subject));
            $this->assertEqual('VIH_Model_Subject', get_class($reopend->Subjects[0]));
        } catch (Doctrine_Record_Exception $e) {
            $this->fail($e->getMessage());
        }
        
    }
}

class VIH_Model_Subject extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('identifier', 'string', 255);
        $this->hasColumn('navn', 'string', 255);
        $this->hasColumn('active', 'boolean');
    }

    public function setUp()
    {
        $this->hasMany(
            'VIH_Model_Course_SubjectGroup as SubjectGroups', 
            array(
                'refClass' => 'VIH_Model_Course_SubjectGroup_Subject',
                'local'    => 'subject_id',
                'foreign'  => 'subject_group_id'
            )
        );

        $this->hasMany(
            'VIH_Model_Course_Registration as Registrations',
            array(
                'refClass' => 'VIH_Model_Course_Registration_Subject',
                'local'    => 'subject_id',
                'foreign'  => 'registration_id'
            )
        );
    }
}

class VIH_Model_Course extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('langtkursus');
        $this->hasColumn('navn', 'string', 255);
    }

    public function setUp()
    {
        $this->hasMany('VIH_Model_Course_Period as Periods', array('local' => 'id',
                                                                   'foreign' => 'course_id'));
        $this->hasMany('VIH_Model_Course_SubjectGroup as SubjectGroups', array('local' => 'id',
                                                                               'foreign' => 'course_id'));
    }
}

class VIH_Model_Course_Period extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('course_id', 'integer');
        $this->hasColumn('date_start', 'date');
        $this->hasColumn('date_end', 'date');
    }

    public function setUp()
    {
        $this->hasOne('VIH_Model_Course as Course', array('local' => 'course_id', 'foreign' => 'id'));
    }

}

class VIH_Model_Course_Registration extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('langtkursus_tilmelding');
        $this->hasColumn('vaerelse', 'integer');
        $this->hasColumn('kursus_id', 'integer');
        $this->hasColumn('adresse_id', 'integer');
        $this->hasColumn('kontakt_adresse_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('VIH_Model_Course as Course', array('local'   => 'kursus_id',
                                                          'foreign' => 'id'));

        $this->hasMany('VIH_Model_Subject as Subjects', array('refClass' => 'VIH_Model_Course_Registration_Subject',
                                                             'local'    => 'registration_id',
                                                             'foreign'  => 'subject_id'));
    }
}

class VIH_Model_Course_SubjectGroup extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', 255);
        $this->hasColumn('period_id', 'integer');
        $this->hasColumn('course_id', 'integer');
    }

    public function setUp()
    {
        $this->hasOne('VIH_Model_Course_Period as Period', array('local'   => 'period_id',
                                                                 'foreign' => 'id'));

        $this->hasOne('VIH_Model_Course as Course', array('local'   => 'course_id',
                                                          'foreign' => 'id'));

        $this->hasMany('VIH_Model_Subject as Subjects', array('refClass' => 'VIH_Model_Course_SubjectGroup_Subject',
                                                             'local'    => 'subject_group_id',
                                                             'foreign'  => 'subject_id'));
    }
}

class VIH_Model_Course_SubjectGroup_Subject extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('subject_group_id', 'integer', null, array('primary' => true));
        $this->hasColumn('subject_id', 'integer', null, array('primary' => true));
    }
}

class VIH_Model_Course_Registration_Subject extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('registration_id', 'integer', null, array('primary' => true));
        $this->hasColumn('subject_id', 'integer', null, array('primary' => true));
    }
}