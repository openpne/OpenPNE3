<?php
class MysqlUser extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('name', 'string', null);
    }
    
    public function setUp()
    {
        $this->hasMany('MysqlGroup', array(
            'local' => 'user_id',
            'foreign' => 'group_id',
            'refClass' => 'MysqlGroupMember'
        ));
    }
}
