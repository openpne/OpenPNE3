<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opApplicationConfiguration represents a configuration for OpenPNE application.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
abstract class opApplicationConfiguration extends sfApplicationConfiguration
{
  static protected $zendLoaded = false;

  protected
   $globEnablePluginList = array(),
   $globEnablePluginControllerList = array();

  protected $appRoutings = array();

  public function initialize()
  {
    mb_internal_encoding('UTF-8');

    require sfConfig::get('sf_data_dir').'/version.php';

    $this->dispatcher->connect('task.cache.clear', array($this, 'clearPluginCache'));
    $this->dispatcher->connect('task.cache.clear', array($this, 'clearWebCache'));
    $this->dispatcher->connect('template.filter_parameters', array($this, 'filterTemplateParameters'));

    $this->dispatcher->connect('op_confirmation.list', array(__CLASS__, 'getCoreConfirmList'));
    $this->dispatcher->connect('op_confirmation.decision', array(__CLASS__, 'processCoreConfirm'));

    $this->dispatcher->connect('op_activity.template.filter_body', array('ActivityDataTable', 'filterBody'));

    $this->setConfigHandlers();
  }

  public function setup()
  {
    require_once dirname(__FILE__).'/../config/opSecurityConfigHandler.class.php';

    $DS = DIRECTORY_SEPARATOR;
    $OpenPNE2Path = sfConfig::get('sf_lib_dir').$DS.'vendor'.$DS;  // ##PROJECT_LIB_DIR##/vendor/
    set_include_path($OpenPNE2Path.PATH_SEPARATOR.get_include_path());
    $result = parent::setup();

    if (0 !== strpos(sfConfig::get('sf_task_name'), 'sfDoctrineBuild'))
    {
      $configCache = $this->getConfigCache();
      $file = $configCache->checkConfig('data/config/plugin.yml', true);
      if ($file)
      {
        include($file);
      }

      require_once dirname(__FILE__).'/../plugin/opPluginManager.class.php';
      $pluginActivations = opPluginManager::getPluginActivationList();
      $pluginActivations = array_merge(array_fill_keys($this->getPlugins(), true), $pluginActivations);
      foreach ($pluginActivations as $key => $value)
      {
        if (!in_array($key, $this->getPlugins()))
        {
          unset($pluginActivations[$key]);
        }
      }

      $pluginActivations = $this->filterSkinPlugins($pluginActivations);
      $this->enablePlugins(array_keys($pluginActivations, true));
      $this->disablePlugins(array_keys($pluginActivations, false));
      unset($this->cache['getPluginPaths']);  // it should be rewrited

      $this->plugins = array_unique($this->plugins);
    }
    return $result;
  }

  public function filterSkinPlugins($pluginList)
  {
    $skinPlugins = array();

    foreach ($pluginList as $pluginName => $activation)
    {
      if (0 === strpos($pluginName, 'opSkin'))
      {
        $skinPlugins[$pluginName] = $activation;
      }
    }

    if (1 !== count(array_keys($skinPlugins, true)))
    {
      $skinPlugins = array_fill_keys(array_keys($skinPlugins), false);
      $skinPlugins['opSkinBasicPlugin'] = true;
    }

    return array_merge($pluginList, $skinPlugins);
  }

  public function getDisabledPlugins()
  {
    return array_diff($this->getAllPlugins(), $this->getPlugins());
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

  public function clearWebCache($params = array())
  {
    $dir = sfConfig::get('sf_web_dir').'/cache/';

    if (is_dir($dir))
    {
      $filesystem = new sfFilesystem();
      @$filesystem->remove(sfFinder::type('any')->in($dir));
    }
  }

  /**
   * @see sfApplicationConfiguration
   */
  public function getConfigCache()
  {
    if (is_null($this->configCache))
    {
      require_once sfConfig::get('sf_lib_dir').'/config/opConfigCache.class.php';
      $this->configCache = new opConfigCache($this);
    }

    return $this->configCache;
  }

  /**
   * Listens to the template.filter_parameters event.
   *
   * @param  sfEvent $event       An sfEvent instance
   * @param  array   $parameters  An array of template parameters to filter
   *
   * @return array   The filtered parameters array
   */
  public function filterTemplateParameters(sfEvent $event, $parameters)
  {
    $parameters['op_config']  = new opConfig();
    sfOutputEscaper::markClassAsSafe('opConfig');

    $parameters['op_color']  = new opColorConfig();

    $table = Doctrine::getTable('SnsTerm');
    $application = sfConfig::get('sf_app');
    if($application == 'pc_backend')
    {
        $application = 'pc_frontend';
    }
    $table->configure(sfContext::getInstance()->getUser()->getCulture(), $application);
    $parameters['op_term'] = $table;
    sfOutputEscaper::markClassAsSafe('SnsTermTable');

    return $parameters;
  }

  public static function getCoreConfirmList(sfEvent $event)
  {
    $list = array(
      'friend_confirm'              => array('MemberRelationshipTable', 'friendConfirmList'),
      'community_confirm'           => array('CommunityMemberTable', 'joinConfirmList'),
      'community_admin_request'     => array('CommunityTable', 'adminConfirmList'),
      'community_sub_admin_request' => array('CommunityTable', 'subAdminConfirmList'),
    );

    return self::processConfirmationEvent($list, $event);
  }

  public static function processCoreConfirm(sfEvent $event)
  {
    $list = array(
      'friend_confirm'              => array('MemberRelationshipTable', 'processFriendConfirm'),
      'community_confirm'           => array('CommunityMemberTable', 'processJoinConfirm'),
      'community_admin_request'     => array('CommunityTable', 'processAdminConfirm'),
      'community_sub_admin_request' => array('CommunityTable', 'processSubAdminConfirm'),
    );

    return self::processConfirmationEvent($list, $event);
  }

  protected static function processConfirmationEvent(array $list, sfEvent $event)
  {
    if (isset($list[$event['category']]) && is_callable($list[$event['category']]))
    {
      return call_user_func($list[$event['category']], $event);
    }

    return false;
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
    $dirs = array_merge($dirs, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/i18n'));
    $dirs = array_merge($dirs, parent::getI18NDirs($moduleName));
    $dirs = array_merge($dirs, array(sfConfig::get('sf_root_dir').'/i18n'));

    return $dirs;
  }

  /**
   * Gets the i18n directories to use globally.
   *
   * @return array An array of i18n directories
   */
  public function getI18NGlobalDirs()
  {
    $dirs = array();

    $dirs = array_merge($dirs, array(sfConfig::get('sf_root_dir').'/i18n'));
    $dirs = array_merge($dirs, parent::getI18NGlobalDirs());

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
    $globalConfigPath = basename(dirname($configPath)).'/'.basename($configPath);

    $files = array();

    if ($libDirs = glob(sfConfig::get('sf_lib_dir').'/config/'.$configPath)) {
      $files = array_merge($files, $libDirs); // library configurations
    }

    $files = array_merge($files, $this->globEnablePlugin($configPath, false));
    $files = array_merge($files, $this->globEnablePlugin($globalConfigPath, false));
    $files = array_merge($files, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/'.$globalConfigPath, false));
    $files = array_merge($files, $this->globEnablePlugin('/apps/'.sfConfig::get('sf_app').'/'.$configPath, false));

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

  public function globPlugins($pattern, $force = true, $isControllerPath = false)
  {
    if ('/' !== $pattern[0])
    {
      $pattern = '/'.$pattern;
    }

    $method = 'getAllPluginPaths';
    if (!$force)
    {
      $method = 'getPluginPaths';
    }

    $dirs = array();
    $pluginPaths = $this->$method();

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

  public function globEnablePlugin($pattern, $isControllerPath = false)
  {
    if ($this->isDebug())
    {
      return $this->globPlugins($pattern, false, $isControllerPath);
    }

    $cacheKey = md5(serialize($pattern));
    $cacheHead = substr($cacheKey, 0, 2);

    $cacheDir = sfConfig::get('sf_cache_dir').DIRECTORY_SEPARATOR;

    $cacheFile = $cacheDir.'glob_enable_plugin_path'.DIRECTORY_SEPARATOR.$cacheHead.'.php';
    $cacheProperty = 'globEnablePluginList';
    if ($isControllerPath)
    {
      $cacheFile = $cacheDir.'glob_enable_plugin_path_controller'.DIRECTORY_SEPARATOR.$cacheHead.'.php';
      $cacheProperty = 'globEnablePluginControllerList';
    }

    $_prop =& $this->$cacheProperty;

    if (empty($_prop[$cacheHead]))
    {
      if (is_readable($cacheFile))
      {
        $_prop[$cacheHead] = include $cacheFile;
      }
    }

    if (isset($_prop[$cacheHead][$cacheKey]))
    {
      return $_prop[$cacheHead][$cacheKey];
    }

    $_prop[$cacheHead][$cacheKey] = $this->globPlugins($pattern, false, $isControllerPath);

    opToolkit::writeCacheFile($cacheFile, "<?php\nreturn ".var_export($_prop[$cacheHead], true).';');

    return $_prop[$cacheHead][$cacheKey];
  }

  public function getGlobalTemplateDir($templateFile)
  {
    foreach ($this->getGlobalTemplateDirs() as $dir)
    {
      if (is_readable($dir.'/'.$templateFile))
      {
        return $dir;
      }
    }

    return null;
  }

  public function getGlobalTemplateDirs()
  {
    $dirs = array();
    $dirs[] = sfConfig::get('sf_root_dir').'/templates';
    $dirs   = array_merge($dirs, $this->getPluginSubPaths('/templates'));
    return $dirs;
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

    require_once 'Zend/Loader/Autoloader.php';
    Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

    self::$zendLoaded = true;
  }

  static public function unregisterZend()
  {
    if (!self::$zendLoaded)
    {
      return true;
    }

    Zend_Loader_Autoloader::resetInstance();
    spl_autoload_unregister(array('Zend_Loader_Autoloader', 'autoload'));

    self::$zendLoaded = false;
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

  public function setCacheDir($cacheDir)
  {
    $newCacheDir = $cacheDir.DIRECTORY_SEPARATOR;
    if (is_callable('posix_getuid'))
    {
      $userinfo = posix_getpwuid(posix_getuid());
      $newCacheDir .= $userinfo['name'];
    }
    else
    {
      $newCacheDir .= php_sapi_name();
    }

    parent::setCacheDir($newCacheDir);
  }

  public function generateAppUrl($application, $parameters = array(), $absolute = false)
  {
    if (is_array($parameters) && isset($parameters['sf_route']))
    {
      $route = $parameters['sf_route'];
      unset($parameters['sf_route']);
    }
    else
    {
      list($route, $parameters) = sfContext::getInstance()->getController()
        ->convertUrlStringToParameters($parameters);
    }

    return $this->getAppRouting($application)->generate($route, $parameters, $absolute);
  }

  protected function getAppRouting($application)
  {
    if (isset($this->appRoutings[$application]))
    {
      return $this->appRoutings[$application];
    }

    $context = sfContext::getInstance();
    $configuration = $context->getConfiguration();

    $config = new opRoutingConfigHandler();
    $currentApp = sfConfig::get('sf_app');

    sfConfig::set('sf_app', $application);
    $configuration->setAppDir(sfConfig::get('sf_apps_dir').DIRECTORY_SEPARATOR.$application);

    $settings = sfDefineEnvironmentConfigHandler::getConfiguration($configuration->getConfigPaths('config/settings.yml'));
    $isNoScriptName = !empty($settings['.settings']['no_script_name']);

    $options = $context->getRouting()->getOptions();
    if ($options['context']['is_secure'])
    {
      $sslBaseUrls = sfConfig::get('op_ssl_base_url');
      $url = $sslBaseUrls[$application];
      $isDefault = 'https://example.com' === $url;
    }
    else
    {
      $url = sfConfig::get('op_base_url');
      $isDefault = 'http://example.com' === $url;
    }

    if (!$isDefault)
    {
      $parts = parse_url($url);

      $parts['path'] = isset($parts['path']) ? $parts['path'] : '';
      $options['context']['prefix'] =
        $this->getAppScriptName($application, sfConfig::get('sf_environment'), $parts['path'], $isNoScriptName);

      if (isset($parts['host']))
      {
        $options['context']['host'] = $parts['host'];
        if (isset($parts['port']))
        {
          $options['context']['host'] .= ':'.$parts['port'];
        }
      }
    }
    else
    {
      $path = preg_replace('#/[^/]+\.php$#', '', $options['context']['prefix']);
      $options['context']['prefix'] = $this->getAppScriptName($application, sfConfig::get('sf_environment'), $path, $isNoScriptName);
    }

    $routing = new sfPatternRouting($context->getEventDispatcher(), null, $options);
    $routing->setRoutes($config->evaluate($configuration->getConfigPaths('config/routing.yml')));
    $context->getEventDispatcher()->notify(new sfEvent($routing, 'routing.load_configuration'));

    sfConfig::set('sf_app', $currentApp);
    $configuration->setAppDir(sfConfig::get('sf_apps_dir').DIRECTORY_SEPARATOR.$currentApp);

    $this->appRoutings[$application] = $routing;

    return $this->appRoutings[$application];
  }

  protected function getAppScriptName($application, $env, $prefix, $isNoScriptName = false)
  {
    if ($isNoScriptName)
    {
      return $prefix;
    }

    if ('/' === $prefix)
    {
      $prefix = '';
    }

    $name = $prefix.'/'.$application;
    if ($env !== 'prod')
    {
      $name .= '_'.$env;
    }
    $name .= '.php';

    return $name;
  }
}
