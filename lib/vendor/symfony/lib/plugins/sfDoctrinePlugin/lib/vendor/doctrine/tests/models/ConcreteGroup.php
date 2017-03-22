<?php
class ConcreteGroup extends Doctrine_Record
{
    public function setUp()
    {
        $this->actAs('GroupTemplate');
    }
}
