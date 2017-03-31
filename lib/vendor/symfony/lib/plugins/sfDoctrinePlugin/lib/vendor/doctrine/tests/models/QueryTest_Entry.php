<?php
class QueryTest_Entry extends Doctrine_Record
{
    /**
     * Table structure.
     */
    public function setTableDefinition()
    {        
        $this->hasColumn('id', 'integer', 4, array('primary', 'autoincrement', 'notnull'));
        $this->hasColumn('authorId', 'integer', 4,
                array('notnull'));
        $this->hasColumn('date', 'integer', 4,
                array('notnull'));
    }

    /**
     * Runtime definition of the relationships to other entities.
     */
    public function setUp()
    {
        $this->hasOne('QueryTest_User as author', array(
            'local' => 'authorId', 'foreign' => 'id'
        ));
    }
}
