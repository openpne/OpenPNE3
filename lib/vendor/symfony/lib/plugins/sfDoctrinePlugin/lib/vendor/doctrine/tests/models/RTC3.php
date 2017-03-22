<?php
class RTC3 extends Doctrine_Record {
    public function setTableDefinition() { 
        $this->hasColumn('name', 'string', 200);
    }
    public function setUp() {
        $this->hasMany('M2MTest as RTC3', array('local' => 'c1_id', 'foreign' => 'c2_id', 'refClass' => 'JC2'));
        $this->hasMany('M2MTest as RTC4', array('local' => 'c1_id', 'foreign' => 'c2_id', 'refClass' => 'JC1'));
    }
}

