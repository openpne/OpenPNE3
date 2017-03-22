<?php
class I18nRelationTest extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->hasColumn('title', 'string', 200);
        $this->hasColumn('author_id', 'integer', 4);
    }
    public function setUp()
    {
        $this->hasOne('I18nAuthorTest', array('local' => 'author_id',
                                    'foreign' => 'id'));
        $this->actAs('I18n', array('fields' => array('author_id', 'title')));
    }
}

class I18nAuthorTest extends Doctrine_Record 
{
    public function setTableDefinition()
    {
        $this->hasColumn('id', 'integer', 4, array('primary' => true, 'autoincrement' => true));
    }
    public function setUp()
    {
        $this->hasMany('I18nRelationTest', array('local' => 'id',
                                    'foreign' => 'author_id'));
    }
}