<?php
class RTC2 extends Doctrine_Record {
    public function setTableDefinition() { 
        $this->hasColumn('name', 'string', 200);
    }
    public function setUp() {
        $this->hasMany('M2MTest as RTC2', array('local' => 'c1_id', 'foreign' => 'c2_id', 'refClass' => 'JC1'));
    }
}

