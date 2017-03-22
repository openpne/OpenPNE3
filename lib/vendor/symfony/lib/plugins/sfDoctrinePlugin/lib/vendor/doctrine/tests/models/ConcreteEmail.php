<?php
class ConcreteEmail extends Doctrine_Record
{
    public function setUp()
    {
        $this->actAs('EmailTemplate');
    }
}
