<?php
class M2MTest2 extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('oid', 'integer', 11, array('autoincrement' => true, 'primary' => true));
        $this->hasColumn('name', 'string', 20);
    }
    public function setUp() {
        $this->hasMany('RTC4 as RTC5', array('local' => 'c1_id', 'foreign' => 'c1_id', 'refClass' => 'JC3'));
    }
}

