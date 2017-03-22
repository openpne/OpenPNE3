<?php
class ResourceType extends Doctrine_Record {
    public function setUp() {
        $this->hasMany('Resource as ResourceAlias', array('local'    => 'type_id',
                                                          'foreign'  => 'resource_id',
                                                          'refClass' => 'ResourceReference'));
    }
    public function setTableDefinition() {
        $this->hasColumn('type', 'string',100);
    }
}

