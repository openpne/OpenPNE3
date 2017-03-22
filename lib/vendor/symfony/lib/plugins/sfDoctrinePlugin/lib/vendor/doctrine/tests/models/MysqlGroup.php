<?php
class MysqlGroup extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', null);
    }
    
    public function setUp()
    {
        $this->hasMany('MysqlUser', array(
            'local' => 'group_id', 
            'foreign' => 'user_id',
            'refClass' => 'MysqlGroupMember'
        ));
    }
}
