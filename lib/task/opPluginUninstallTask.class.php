<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opPluginUninstallTask extends sfPluginUninstallTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The plugin name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('channel', 'c', sfCommandOption::PARAMETER_REQUIRED, 'The PEAR channel name', null),
      new sfCommandOption('install_deps', 'd', sfCommandOption::PARAMETER_NONE, 'Whether to force installation of dependencies', null),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', null),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
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

  public function getPluginManager()
  {
    // Remove E_STRICT and E_DEPRECATED from error_reporting
    error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

    $oldPluginManager = parent::getPluginManager();
    $pluginManager = new opPluginManager($this->dispatcher, $oldPluginManager->getEnvironment());

    return $pluginManager;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (empty($options['channel']))
    {
      $options['channel'] = opPluginManager::getDefaultPluginChannelServerName();
    }

    return parent::execute($arguments, $options);
  }
}
