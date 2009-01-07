<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfOpenPNEApplicationConfiguration represents a configuration for OpenPNE application.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class sfOpenPNEApplicationConfiguration extends sfApplicationConfiguration
{
  static protected $zendLoaded = false;

  public function initialize()
  {
    $this->dispatcher->connect('task.cache.clear', array($this, 'clearPluginCache'));

    $this->setConfigHandlers();
    $this->setBehaviors();
  }

  public function setup()
  {
    $DS = DIRECTORY_SEPARATOR;
    $OpenPNE2Path = sfConfig::get('sf_lib_dir').$DS.'vendor'.$DS;  // ##PROJECT_LIB_DIR##/vendor/
    set_include_path($OpenPNE2Path.PATH_SEPARATOR.get_include_path());

    $result = parent::setup();
    $configCache = $this->getConfigCache();
    $file = $configCache->checkConfig('data/config/plugin.yml', true);
    if ($file)
    {
      include($file);
    }

    if (sfConfig::get('op_plugin_activation'))
    {
      $pluginActivations = array_merge(array_fill_keys($this->getPlugins(), true), sfConfig::get('op_plugin_activation'));
      foreach ($pluginActivations as $key => $value)
      {
        if (!in_array($key, $this->getPlugins()))
        {
          unset($pluginActivations[$key]);
        }
      }
      $this->enablePlugins(array_keys($pluginActivations, true));
      $this->disablePlugins(array_keys($pluginActivations, false));
    }

    include($this->getConfigCache()->checkConfig('config/widget.yml'));

    return $result;
  }

  public function getAllPlugins()
  {
    return array_keys($this->getAllPluginPaths());
  }

  public function getAllOpenPNEPlugins()
  {
    $list = $this->getAllPlugins();
    $result = array();

    foreach ($list as $value)
    {
      if (!strncmp($value, 'op', 2))
      {
        $result[] = $value;
      }
    }

    return $result;
  }

  public function getEnabledAuthPlugin()
  {
    $list = $this->getPlugins();
    $result = array();

    foreach ($list as $value)
    {
      if (!strncmp($value, 'opAuth', 6))
      {
        $result[] = $value;
      }
    }

    return $result;
  }

  public function isPluginExists($pluginName)
  {
    return in_array($pluginName, $this->getAllPlugins());
  }

  public function isEnabledPlugin($pluginName)
  {
    return in_array($pluginName, $this->getPlugins());
  }

  public function isDisabledPlugin($pluginName)
  {
    return (bool)(!$this->isEnabledPlugin($pluginName) && in_array($pluginName, $this->getAllPlugins()));
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

    $dirs = array_merge($dirs, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/i18n'));
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

  protected function setBehaviors()
  {
    sfPropelBehavior::registerHooks('activate', array (
      'Peer:doSelectStmt:doSelectStmt' => array('opActivateBehavior', 'doSelectStmt'),
      'Peer:doCount:doCount'           => array('opActivateBehavior', 'doCount'),
    ));

    sfPropelBehavior::registerMethods('check_privilege_belong', array (
      array ('opCheckPrivilegeBelongBehavior', 'checkPrivilegeBelong'),
      array ('opCheckPrivilegeBelongBehavior', 'isPrivilegeBelong'),
    ));

    sfPropelBehavior::registerMethods('check_privilege_owner', array (
      array ('opCheckPrivilegeOwnerBehavior', 'checkPrivilegeOwner'),
      array ('opCheckPrivilegeOwnerBehavior', 'isPrivilegeOwner'),
    ));
  }

  protected function setConfigHandlers()
  {
    $this->getConfigCache()->registerConfigHandler('config/sns_config.yml', 'opConfigConfigHandler', array('prefix' => 'openpne_sns_'));
    include($this->getConfigCache()->checkConfig('config/sns_config.yml'));

    $this->getConfigCache()->registerConfigHandler('config/member_config.yml', 'opConfigConfigHandler', array('prefix' => 'openpne_member_'));
    include($this->getConfigCache()->checkConfig('config/member_config.yml'));

    $this->getConfigCache()->registerConfigHandler('config/community_config.yml', 'opConfigConfigHandler', array('prefix' => 'openpne_community_'));
    include($this->getConfigCache()->checkConfig('config/community_config.yml'));
  }

  static public function registerZend()
  {
    if (self::$zendLoaded)
    {
      return true;
    }

    $DS = DIRECTORY_SEPARATOR;  // Alias
    $zendPath = sfConfig::get('sf_lib_dir').$DS.'vendor'.$DS;  // ##PROJECT_LIB_DIR##/vendor/

    set_include_path($zendPath.PATH_SEPARATOR.get_include_path());
    require_once 'Zend/Loader.php';
    Zend_Loader::registerAutoLoad();
    self::$zendLoaded = true;
  }

  static public function registerJanRainOpenID()
  {
    $DS = DIRECTORY_SEPARATOR;
    $openidPath = sfConfig::get('sf_lib_dir').$DS.'vendor'.$DS.'php-openid'.$DS;  // ##PROJECT_LIB_DIR##/vendor/php-openid/
    set_include_path($openidPath.PATH_SEPARATOR.get_include_path());

    require_once 'Auth/OpenID/Consumer.php';
    require_once 'Auth/OpenID/FileStore.php';
    require_once 'Auth/OpenID/SReg.php';
  }
}
