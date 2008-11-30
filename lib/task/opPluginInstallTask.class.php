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
    $this->briefDescription = 'Installs an OpenPNE plugin';
    $this->detailedDescription = <<<EOF
The [plugin:install|INFO] task installs an OpenPNE plugin:
Call it with:

  [./symfony opPlugin:install opSamplePlugin|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $environment = $this->getPluginManager()->getEnvironment();

    // register OpenPNE plugin channel
    $environment->registerChannel('plugins.openpne.jp', true);

    return parent::execute($arguments, $options);
  }

  public function getPluginManager()
  {
    if (is_null($this->pluginManager))
    {
      $environment = new sfPearEnvironment($this->dispatcher, array(
        'plugin_dir' => sfConfig::get('sf_plugins_dir'),
        'cache_dir'  => sfConfig::get('sf_cache_dir').'/.pear',
        'web_dir'    => sfConfig::get('sf_web_dir'),
        'rest_base_class' => 'opPearRest',
      ));

      $this->pluginManager = new sfSymfonyPluginManager($this->dispatcher, $environment);
    }

    return $this->pluginManager;
  }
}
