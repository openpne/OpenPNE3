<?php
class Tag extends Doctrine_Record {
    public function setUp() {
        $this->hasMany('Photo', array(
            'local' => 'tag_id',
            'foreign' => 'photo_id',
            'refClass' => 'Phototag'
        ));
    }
    public function setTableDefinition() {
        $this->hasColumn('tag', 'string', 100);
    }
}
