<?php
class MyOtherThing extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('name', 'string');
        $this->hasColumn('user_id', 'integer');
    }
    public function setUp() {
		$this->hasMany('MyUserOtherThing', array(
            'local' => 'id', 'foreign' => 'other_thing_id'
        ));
        
        $this->hasOne('MyUser', array(
            'local' => 'user_id', 'foreign' => 'id'
        ));
    }
}
