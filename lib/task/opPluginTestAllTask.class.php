<?php

/**
 * opPlugin:test-all command.
 * 
 * @package    OpenPNE
 * @subpackage task
 * @auther     Hiromi Hishida <info@77-web.com>
 */
class opPluginTestAllTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'opPlugin';
    $this->name             = 'test-all';

    $this->addArguments(array(
      new sfCommandArgument('target', null, sfCommandArgument::REQUIRED, 'A name of a plugin to launch tests'),
    ));

    $this->briefDescription = 'Launches all tests in a plugin';
    $this->detailedDescription = <<<EOF
The [opPlugin:test-all|INFO] task launches all tests in a plugin
Call it with:

  [php symfony opPlugin:test-all opSamplePlugin|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    unset($arguments['task']);
    
    $unitTest = new opPluginTestUnitTask($this->dispatcher, new sfFormatter());
    try
    {
      $unitTest->run($arguments, $options);
    }
    catch (RuntimeException $e)
    {
      echo $e->getMessage()."\n";
    }
    
    $functionalTest = new opPluginTestFunctionalTask($this->dispatcher, new sfFormatter());
    try
    {
      $functionalTest->run($arguments, $options);
    }
    catch (RuntimeException $e)
    {
      echo $e->getMessage()."\n";
    }
  }
}