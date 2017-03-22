<?php
class MyUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
    }
    
    public function setUp()
    {
		  $this->hasMany('MyOneThing', array(
            'local' => 'id', 'foreign' => 'user_id'
          ));

		  $this->hasMany('MyOtherThing', array(
            'local' => 'id', 'foreign' => 'user_id'
          ));
    }
}