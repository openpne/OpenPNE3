<?php
class Forum_Category extends Doctrine_Record {
    public function setTableDefinition() {
        $this->hasColumn('root_category_id', 'integer', 10);
        $this->hasColumn('parent_category_id', 'integer', 10);
        $this->hasColumn('name', 'string', 50);
        $this->hasColumn('description', 'string', 99999);
    }
    public function setUp() {
        $this->hasMany('Forum_Category as Subcategory', array(
            'local' => 'id',
            'foreign' => 'parent_category_id'
        ));
        
        $this->hasOne('Forum_Category as Parent', array(
            'local' => 'parent_category_id',
            'foreign' => 'id'
        ));

        $this->hasOne('Forum_Category as Rootcategory', array(
            'local' => 'root_category_id',
            'foreign' => 'id'
        ));
    }
}
