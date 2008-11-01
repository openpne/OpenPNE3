<?php

class openpneInstallTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'install';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [openpne:install|INFO] task does things.
Call it with:

  [php symfony openpne:install|INFO]
EOF;
    // add arguments here, like the following:
    //$this->addArgument('application', sfCommandArgument::REQUIRED, 'The application name');
    // add options here, like the following:
    //$this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev');
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add code here
  }
}