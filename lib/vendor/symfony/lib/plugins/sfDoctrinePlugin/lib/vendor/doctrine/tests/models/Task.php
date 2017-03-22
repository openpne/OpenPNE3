<?php
class Task extends Doctrine_Record {
   public function setUp() {
      $this->hasMany('Resource as ResourceAlias', array('local'     =>  'task_id',
                                                        'foreign'   =>  'resource_id',
                                                        'refClass'  =>  'Assignment'));
      $this->hasMany('Task as Subtask', array('local' => 'id', 'foreign' => 'parent_id'));
   } 
   public function setTableDefinition() {
      $this->hasColumn('name', 'string',100); 
      $this->hasColumn('parent_id', 'integer'); 
   }
} 
