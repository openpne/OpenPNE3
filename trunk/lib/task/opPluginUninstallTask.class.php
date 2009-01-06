<?php

class opPluginUninstallTask extends sfPluginUninstallTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('channel', 'c', sfCommandOption::PARAMETER_REQUIRED, 'The PEAR channel name', 'plugins.openpne.jp'),
      new sfCommandOption('install_deps', 'd', sfCommandOption::PARAMETER_NONE, 'Whether to force installation of dependencies', null),
    ));

    $this->namespace        = 'opPlugin';
    $this->name             = 'uninstall';
    $this->briefDescription = 'Uninstalls the OpenPNE plugin';
    $this->detailedDescription = <<<EOF
The [opPlugin:uninstall|INFO] task uninstalls the OpenPNE plugin.
Call it with:

  [./symfony opPlugin:uninstall opSamplePlugin|INFO]
EOF;
  }
}
