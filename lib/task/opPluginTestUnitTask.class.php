<?php

/**
 * opPlugin:test-unit command.
 * 
 * @package    OpenPNE
 * @subpackage task
 * @auther     Hiromi Hishida <info@77-web.com>
 */
class opPluginTestUnitTask extends opPluginTestBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'opPlugin';
    $this->name             = 'test-unit';

    $this->addArguments(array(
      new sfCommandArgument('target', null, sfCommandArgument::REQUIRED, 'A name of a plugin to launch tests'),
    ));

    $this->briefDescription = 'Launches all unit tests in a plugin';
    $this->detailedDescription = <<<EOF
The [opPlugin:test-all|INFO] task launches all unit tests in a plugin
Call it with:

  [php symfony opPlugin:test-unit opSamplePlugin|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    $baseDir = sfConfig::get('sf_plugins_dir').DIRECTORY_SEPARATOR.$arguments['target'].DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR.'unit';

    return $this->launchTestsInDir($baseDir);
  }
}