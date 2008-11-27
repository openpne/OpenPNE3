<?php

/**
 * sfOpenPNEApplicationConfiguration represents a configuration for OpenPNE application.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class sfOpenPNEApplicationConfiguration extends sfApplicationConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('task.cache.clear', array($this, 'clearPluginCache'));

    $this->getConfigCache()->registerConfigHandler('config/sns_config.yml', 'sfOpenPNESNSConfigHandler', array('application' => $this));
    include($this->getConfigCache()->checkConfig('config/sns_config.yml'));

    $this->getConfigCache()->registerConfigHandler('config/member_config.yml', 'sfOpenPNEMemberConfigHandler', array());
    include($this->getConfigCache()->checkConfig('config/member_config.yml'));
    sfPropelBehavior::registerHooks('activate', array (
      'Peer:doSelectStmt' => array ('opActivateBehavior', 'doSelectStmt'),
      'Peer:doCount' => array ('opActivateBehavior', 'doCount'),
    ));
  }

  public function clearPluginCache($params = array())
  {
    $appConfiguration = $params['app'];
    $environment = $params['env'];
    $subDir = sfConfig::get('sf_cache_dir').'/'.$appConfiguration->getApplication().'/'.$environment.'/plugins';

    if (is_dir($subDir))
    {
      $filesystem = new sfFilesystem();
      $filesystem->remove(sfFinder::type('any')->discard('.sf')->in($subDir));
    }
  }

  /**
   * Gets directories where controller classes are stored for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of directories
   */
  public function getControllerDirs($moduleName)
  {
    $dirs = array();

    $dirs = array_merge($dirs, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/actions', true));
    $dirs = array_merge($dirs, parent::getControllerDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets directories where template files are stored for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of directories
   */
  public function getTemplateDirs($moduleName)
  {
    $dirs = array();

    $dirs = array_merge($dirs, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/templates'));
    $dirs = array_merge($dirs, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/templates'));
    $dirs = array_merge($dirs, parent::getTemplateDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets the decorator directories.
   *
   * @return array  An array of the decorator directories
   */
  public function getDecoratorDirs()
  {
    $dirs = array();

    $dirs = array_merge($dirs, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/templates'));
    $dirs = array_merge($dirs, parent::getDecoratorDirs());

    return $dirs;
  }

  /**
   * Gets the i18n directories to use for a given module.
   *
   * @param string $moduleName The module name
   *
   * @return array An array of i18n directories
   */
  public function getI18NDirs($moduleName)
  {
    $dirs = array();

    $dirs = array_merge($dirs, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/modules/'.$moduleName.'/i18n'));
    $dirs = array_merge($dirs, parent::getI18NDirs($moduleName));

    return $dirs;
  }

  /**
   * Gets the configuration file paths for a given relative configuration path.
   *
   * @param string $configPath The configuration path
   *
   * @return array An array of paths
   */
  public function getConfigPaths($configPath)
  {
    $files = array();

    if ($libDirs = glob(sfConfig::get('sf_lib_dir').'/config/'.$configPath)) {
      $files = array_merge($files, $libDirs); // library configurations
    }

    $files = array_merge($files, $this->globEnablePlugin($configPath));
    $files = array_merge($files, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/'.$configPath));

    $configs = array();
    foreach (array_unique($files) as $file)
    {
      if (is_readable($file))
      {
        $configs[] = $file;
      }
    }

    $configs = array_merge(parent::getConfigPaths($configPath), $configs);
    return $configs;
  }

  public function globEnablePlugin($pattern, $isControllerPath = false)
  {
    $dirs = array();
    $pluginPaths = $this->getPluginPaths();

    foreach ($pluginPaths as $pluginPath)
    {
      if ($pluginDirs = glob($pluginPath.$pattern))
      {
        if ($isControllerPath)
        {
          $dirs = array_merge($dirs, array_combine($pluginDirs, array_fill(0, count($pluginDirs), false)));
        }
        else
        {
          $dirs = array_merge($dirs, $pluginDirs);
        }
      }
    }

    return $dirs;
  }
}
