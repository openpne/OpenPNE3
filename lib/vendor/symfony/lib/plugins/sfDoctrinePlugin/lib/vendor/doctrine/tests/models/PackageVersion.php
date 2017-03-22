<?php
class PackageVersion extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('package_id', 'integer');
        $this->hasColumn('description', 'string', 255);
    }
    public function setUp()
    {
        $this->hasOne('Package', array('local' => 'package_id', 'foreign' => 'id'));
        $this->hasMany('PackageVersionNotes as Note', array(
            'local' => 'id', 'foreign' => 'package_version_id'
        ));
    }
}
