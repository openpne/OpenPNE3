<?php
class ForeignKeyTest2 extends Doctrine_Record
{
    public function setTableDefinition() 
    {
        $this->hasColumn('name', 'string', null);
        $this->hasColumn('foreignkey', 'integer');
       
        $this->hasOne('ForeignKeyTest', array(
            'local' => 'foreignKey', 'foreign' => 'id'
        ));
    }
}
