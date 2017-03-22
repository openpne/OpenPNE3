<?php

class Doctrine_Template_TestBehavior extends Doctrine_Template
{
    public function setTableDefinition()
    {
        $this->hasColumn('test', 'string', 255);
    }
}