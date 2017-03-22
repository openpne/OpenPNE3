<?php
/**
 * Fixture for Doctrine_Task_TestCase
 * 
 * @author Dan Bettles <danbettles@yahoo.co.uk>
 */

class Doctrine_Task_TestCase_TestTask005 extends Doctrine_Task
{
    public $taskName = 'another invalid task name';

    public function execute() {}
}