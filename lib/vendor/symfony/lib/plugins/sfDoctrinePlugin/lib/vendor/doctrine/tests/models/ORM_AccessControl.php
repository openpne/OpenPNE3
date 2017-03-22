<?php
class ORM_AccessControl extends Doctrine_Record 
{
    public function setTableDefinition() 
    {
        $this->hasColumn('name', 'string', 255);
    }
    public function setUp() 
    {
        $this->hasMany('ORM_AccessGroup as accessGroups', array(
            'local' => 'accessControlID', 'foreign' => 'accessGroupID', 'refClass' => 'ORM_AccessControlsGroups'
        ));
    }
}
