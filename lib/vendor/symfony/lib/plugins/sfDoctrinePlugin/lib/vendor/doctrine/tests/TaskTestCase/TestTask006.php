<?php
/**
 * Fixture for Doctrine_Task_TestCase
 * 
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */

class Doctrine_Task_TestCase_TestTask006 extends Doctrine_Task
{
    public function __construct()
    {
        $this->setTaskName('invalid_task_name');
    }

    public function execute() {}
}