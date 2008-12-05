<?php

class opPluginInstallTask extends sfPluginInstallTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('stability', 's', sfCommandOption::PARAMETER_REQUIRED, 'The preferred stability (stable, beta, alpha)', null),
      new sfCommandOption('release', 'r', sfCommandOption::PARAMETER_REQUIRED, 'The preferred version', null),
      new sfCommandOption('channel', 'c', sfCommandOption::PARAMETER_REQUIRED, 'The PEAR channel name', 'plugins.openpne.jp'),
      new sfCommandOption('install_deps', 'd', sfCommandOption::PARAMETER_NONE, 'Whether to force installation of required dependencies', null),
      new sfCommandOption('force-license', null, sfCommandOption::PARAMETER_NONE, 'Whether to force installation even if the license is not MIT like'),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'install';
    $this->briefDescription = 'Installs the OpenPNE plugin';
    $this->detailedDescription = <<<EOF
The [plugin:install|INFO] task installs the OpenPNE plugin:
Call it with:

  [./symfony opPlugin:install opSamplePlugin|INFO]
EOF;
  }

  public function getPluginManager()
  {
    if (is_null($this->pluginManager))
    {
      $this->pluginManager = new opPluginManager($this->dispatcher);
    }

    return $this->pluginManager;
  }
}
