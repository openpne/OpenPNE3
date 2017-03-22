<?php
class SoftDeleteBCTest extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('soft_delete_bc_test');
    
        $this->hasColumn('name', 'string', null, array('primary' => true));
        $this->hasColumn('something', 'string', '25', array('notnull' => true, 'unique' => true));
    }

    public function setUp()
    {
        $this->actAs('SoftDelete', array('name' => 'deleted', 'type' => 'boolean'));
    }
}