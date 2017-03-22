<?php
class QueryTest_UserRank extends Doctrine_Record
{
    public function setTableDefinition()
    {        
        $this->hasColumn('rankId', 'integer', 4, array('primary' => true));
        $this->hasColumn('userId', 'integer', 4, array('primary' => true));
    }
}
