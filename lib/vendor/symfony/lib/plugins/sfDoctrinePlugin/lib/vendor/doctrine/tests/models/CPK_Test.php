<?php
class CPK_Test extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('name', 'string', 255);
    }
    public function setUp() {
        $this->hasMany('CPK_Test2 as Test', array(
            'local' => 'test1_id',
            'foreign' => 'test2_id',
            'refClass' => 'CPK_Association'
        ));
    }
}
