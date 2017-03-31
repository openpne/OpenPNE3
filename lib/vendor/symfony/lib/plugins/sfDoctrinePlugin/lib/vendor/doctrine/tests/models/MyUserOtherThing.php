<?php
class MyUserOtherThing extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('user_id', 'integer');
        $this->hasColumn('other_thing_id', 'integer');
    }
    
    
    public function setUp()
    {
        $this->hasOne('MyUser', array(
            'local' => 'user_id', 'foreign' => 'id'
        ));
        
        $this->hasOne('MyOtherThing', array(
            'local' => 'other_thing_id', 'foreign' => 'id'
        ));
    }
}
