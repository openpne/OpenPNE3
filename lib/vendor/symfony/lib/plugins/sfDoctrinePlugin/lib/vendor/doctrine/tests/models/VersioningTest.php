<?php
class VersioningTest extends Doctrine_Record 
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
        $this->hasColumn('version', 'integer');
    }
    public function setUp()
    {
        $this->actAs('Versionable');
    }
}

class VersioningTest2 extends Doctrine_Record 
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
        $this->hasColumn('version', 'integer');
    }
    public function setUp()
    {
        $this->actAs('Versionable', array('auditLog' => false));
    }
}

class VersioningTest3 extends Doctrine_Record 
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string');
        $this->hasColumn('version', 'integer');
    }
    public function setUp()
    {
    	  
        $this->actAs('Versionable', array('tableName' =>  'tbl_prefix_comments_version',
                                          'className' =>  'VersioningTestClass'));

    }
}