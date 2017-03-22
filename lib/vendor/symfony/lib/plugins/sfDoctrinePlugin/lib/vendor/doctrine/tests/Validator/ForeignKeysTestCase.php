<?php

class Doctrine_Validator_ForeignKeys_TestCase extends Doctrine_UnitTestCase 
{
    public function prepareTables()
    { 
        $this->tables = array('TestPerson', 'TestAddress');
        
        parent::prepareTables();
    }

    public function testForeignKeyIsValidIfLocalRelationIsSet()
    {
        $person = new TestPerson();
        $address = new TestAddress();
        
        $address->Person = $person;
        
        $table = $address->getTable();
        $errors = $table->validateField('person_id', $address->person_id, $address);
        
        $this->assertEqual(0, $errors->count());
    }
    
    public function testForeignKeyIsValidIfForeignRelationIsSet()
    {
        $person = new TestPerson();
        $person->Addresses[0] = new TestAddress();
        
        $address = $person->Addresses[0];
        $table = $address->getTable();
        $errors = $table->validateField('person_id', $address->person_id, $address);
        
        $this->assertEqual(0, $errors->count());
    }

    public function testSynchronizedForeignKeyIsValidIfLocalRelationIsSet()
    {
        $person = new TestPerson();
        $person->synchronizeWithArray(array('Addresses' => array(array())));

        $address = $person->Addresses[0];
        $table = $address->getTable();
        
        $errors = $table->validateField('person_id', $address->person_id, $address);
        $this->assertEqual(0, $errors->count());
    }

    public function testSynchronizedForeignKeyIsValidIfForeignRelationIsSet()
    {
        $address = new TestAddress();
        $address->synchronizeWithArray(array('Person' => array()));

        $table = $address->getTable();
        $errors = $table->validateField('person_id', $address->person_id, $address);
        $this->assertEqual(0, $errors->count());
    }
}

class TestPerson extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->hasColumn('first_name', 'string');
        $this->hasColumn('last_name', 'string');
        $this->hasColumn('favorite_color_id', 'integer');
    }
    
    public function setUp()
    {
        $this->hasMany('TestAddress as Addresses', array('local' => 'id', 'foreign' => 'person_id'));
    }
}

class TestAddress extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', null, array('primary' => true, 'notnull'=> true, 'autoincrement' => true));
        $this->hasColumn('person_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('street', 'string');
        $this->hasColumn('city', 'string');
        $this->hasColumn('state', 'string');
        $this->hasColumn('zip', 'string');
    }
    
    public function setUp()
    {
        $this->hasOne('TestPerson as Person', array('local' => 'person_id', 'foreign' => 'id'));
    }
}