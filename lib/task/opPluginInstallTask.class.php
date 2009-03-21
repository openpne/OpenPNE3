<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opPluginInstallTask extends sfPluginInstallTask
{
  protected $pluginManager = null;

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

  protected function execute($arguments = array(), $options = array())
  {
    if ($this->isSelfInstalledPlugins($arguments['name']))
    {
      $str = "\"%s\" is already installed manually, so it will not be reinstalled.\n"
           . "If you want to manage it automatically, delete it manually and retry this command.";
      $this->logBlock(sprintf($str, $arguments['name']), 'INFO');
      return false;
    }

    parent::execute($arguments, $options);
  }

  public function getPluginManager()
  {
    if (is_null($this->pluginManager))
    {
      $this->pluginManager = new opPluginManager($this->dispatcher);
    }

    return $this->pluginManager;
  }

  public function isSelfInstalledPlugins($pluginName)
  {
    if (!$this->isPluginExists($pluginName))
    {
      return false;
    }

    $registry = $this->getPluginManager()->getEnvironment()->getRegistry();
    return !(bool)$registry->getPackage($pluginName, opPluginManager::OPENPNE_PLUGIN_CHANNEL);
  }

  public function isPluginExists($pluginName)
  {
    return in_array($pluginName, array_keys($this->configuration->getAllPluginPaths()));
  }
}
