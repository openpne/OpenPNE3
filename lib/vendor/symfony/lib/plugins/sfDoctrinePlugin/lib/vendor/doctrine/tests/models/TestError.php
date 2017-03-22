<?php
class TestError extends Doctrine_Record {
    public function setUp() {
        $this->hasMany('Description', array('local'   => 'file_md5',
                                            'foreign' => 'file_md5'));
    }
    public function setTableDefinition() {
        $this->hasColumn('message', 'string',200);
        $this->hasColumn('code', 'integer',11);
        $this->hasColumn('file_md5', 'string',32, 'primary');
    }
}

