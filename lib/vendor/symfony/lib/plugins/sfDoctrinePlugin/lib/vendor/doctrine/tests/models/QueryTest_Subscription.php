<?php
class QueryTest_Subscription extends Doctrine_Record
{
    public function setTableDefinition()
    {   $this->hasColumn('id', 'integer', 4, array('primary', 'autoincrement', 'notnull'));     
        $this->hasColumn('begin', 'date');
        $this->hasColumn('end', 'date');
    }
}
