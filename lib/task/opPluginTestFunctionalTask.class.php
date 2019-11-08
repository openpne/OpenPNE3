<?php
/**
 * opPlugin:test-functional command.
 * 
 * @package    OpenPNE
 * @subpackage task
 * @auther     Hiromi Hishida <info@77-web.com>
 */
class opPluginTestFunctionalTask extends opPluginTestBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'opPlugin';
    $this->name             = 'test-functional';

    $this->addArguments(array(
      new sfCommandArgument('target', null, sfCommandArgument::REQUIRED, 'A name of a plugin to launch tests'),
    ));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'A name of target application'),
    ));

    $this->briefDescription = 'Launches all functional tests in a plugin';
    $this->detailedDescription = <<<EOF
The [opPlugin:test-all|INFO] task launches all functional tests in a plugin
Call it with:

  [php symfony opPlugin:test-functional opSamplePlugin|INFO]
  [php symfony opPlugin:test-functional opSamplePlugin --application=pc_frontend|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    $baseDir = sfConfig::get('sf_plugins_dir').DIRECTORY_SEPARATOR.$arguments['target'].DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'functional';
    
    if (!empty($options['application']))
    {
      $baseDir .= DIRECTORY_SEPARATOR.$options['application'];
    }

    return $this->launchTestsInDir($baseDir);
  }
}