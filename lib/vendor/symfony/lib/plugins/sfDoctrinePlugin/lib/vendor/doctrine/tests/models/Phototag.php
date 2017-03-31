<?php
class Phototag extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('photo_id', 'integer', 11, array('primary' => true));
        $this->hasColumn('tag_id', 'integer', 11, array('primary' => true));
    }
}
