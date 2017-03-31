<?php
class Photo extends Doctrine_Record {
    public function setUp() {
        $this->hasMany('Tag', array(
            'local' => 'photo_id',
            'foreign' => 'tag_id',
            'refClass' => 'Phototag'
        ));
    }
    public function setTableDefinition() {
        $this->hasColumn('name', 'string', 100);
    }
}
