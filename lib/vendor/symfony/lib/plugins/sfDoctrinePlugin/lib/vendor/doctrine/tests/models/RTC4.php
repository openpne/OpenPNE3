<?php
class RTC4 extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('oid', 'integer', 11, array('autoincrement', 'primary'));  
        $this->hasColumn('name', 'string', 20);
    }
    public function setUp() {
        $this->hasMany('M2MTest2', array('local' => 'c1_id', 'foreign' => 'c2_id', 'refClass' => 'JC3'));
    }
}
