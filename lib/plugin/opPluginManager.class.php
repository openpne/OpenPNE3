<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

// Remove E_STRICT and E_DEPRECATED from error_reporting
error_reporting(error_reporting() & ~(E_STRICT | E_DEPRECATED));

/**
 * opPluginManager allows you to manage OpenPNE plugins.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPluginManager extends sfSymfonyPluginManager
{
  const OPENPNE_PLUGIN_CHANNEL = 'plugins.openpne.jp';
  const OPENPNE_PLUGIN_LIST_BASE_URL = 'http://plugins.openpne.jp/packages/';

  protected $channel = null;

  public function __construct(sfEventDispatcher $dispatcher, sfPearEnvironment $environment = null, $channel = null)
  {
    if (!$environment)
    {
      $tz = @date_default_timezone_get();

      $environment = new sfPearEnvironment($dispatcher, array(
        'plugin_dir'            => sfConfig::get('sf_plugins_dir'),
        'cache_dir'             => sfConfig::get('sf_cache_dir').'/.pear',
        'web_dir'               => sfConfig::get('sf_web_dir'),
        'rest_base_class'       => 'opPearRest',
        'downloader_base_class' => 'opPluginDownloader',
      ));

      date_default_timezone_set($tz);

      $this->channel = $channel;
      if (!$this->channel)
      {
        $this->channel = self::getDefaultPluginChannelServerName();
      }

      try
      {
        $environment->registerChannel($this->channel, true);
      }
      catch (sfPluginException $e) {}
    }

    parent::__construct($dispatcher, $environment);

    // register OpenPNE for dependencies
    $this->registerOpenPNEPackage();
  }

  public function getChannel()
  {
    return $this->getEnvironment()->getRegistry()->getChannel($this->channel);
  }

  public function getBaseURL()
  {
    return $this->getChannel()->getBaseURL('REST1.1');
  }

  public function retrieveChannelXml($path)
  {
    $rest = $this->getEnvironment()->getRest();
    return $rest->_rest->retrieveXml($this->getBaseURL().$path);
  }

  public function getPluginInfo($plugin)
  {
    $data = $this->retrieveChannelXml('p/'.strtolower($plugin).'/info.xml');
    $result = array_merge(array(
        'n' => $plugin,
        'c' => $this->channel,
        'l' => 'Apache',
        's' => $plugin,
        'd' => $plugin,
    ), (array)$data);

    return $result;
  }

  public function getPluginMaintainer($plugin)
  {
    $data = $this->retrieveChannelXml('p/'.strtolower($plugin).'/maintainers2.xml');
    $result = array();

    foreach ($data->m as $maintainer)
    {
      $info = $this->retrieveChannelXml('m/'.strtolower((string)$maintainer->h).'/info.xml');
      $result[] = array_merge((array)$maintainer, array('n' => (string)$info->n));
    }

    return $result;
  }

  public function isExistsPlugin($plugin)
  {
    return (bool)$this->getPluginInfo($plugin);
  }

  public function ListenToPluginPostUninstall($event)
  {
    parent::ListenToPluginPostUninstall($event);

    $this->uninstallModelFiles($event['plugin']);
  }

  public function uninstallModelFiles($plugin)
  {
    $filesystem = new sfFilesystem();

    $baseDir = sfConfig::get('sf_lib_dir');
    $subpackages = array('model', 'form', 'filter');

    foreach ($subpackages as $subpackage)
    {
      $targetDir = $baseDir.DIRECTORY_SEPARATOR.$subpackage.DIRECTORY_SEPARATOR.'doctrine'.DIRECTORY_SEPARATOR.$plugin;
      if (is_dir($targetDir))
      {
        $filesystem->remove(sfFinder::type('any')->in($targetDir));
        $filesystem->remove($targetDir);
      }
    }
  }

  public static function getPluginActivationList()
  {
    return array_merge(sfConfig::get('op_plugin_activation', array()), self::getPluginListForDatabase());
  }

  public static function getPluginListForDatabase()
  {
    $config =  sfSimpleYamlConfigHandler::getConfiguration(array(sfConfig::get('sf_root_dir').'/config/databases.yml'));

    if (isset($config['all']['master']))
    {
      $connConfig = $config['all']['master']['param'];
    }
    elseif (isset($config['all']['doctrine']))
    {
      $connConfig = $config['all']['doctrine']['param'];
    }
    else
    {
      $connConfig = array_shift($config['all']);
      $connConfig = $connConfig['param'];
    }
    $connConfig = array_merge(array('password' => null), $connConfig);

    $result = array();
    try
    {
      $conn = new PDO($connConfig['dsn'], $connConfig['username'], $connConfig['password']);
      $state = $conn->query('SELECT name, is_enabled FROM plugin');
      if ($state)
      {
        foreach ($state as $row)
        {
          $result[$row['name']] = (bool)$row['is_enabled'];
        }
      }
    }
    catch (PDOException $e)
    {
      // do nothing
    }

    return $result;
  }

  static public function enablePlugin($plugin, $configDir)
  {
    // do nothing.
    // OpenPNE plugin don't want to rewrite config/ProjectConfiguration.class.php
  }

  static public function disablePlugin($plugin, $configDir)
  {
    // do nothing.
    // OpenPNE plugin don't want to rewrite config/ProjectConfiguration.class.php
  }

  static public function getDefaultPluginChannelServerName()
  {
    return sfConfig::get('op_default_plugin_channel_server', self::OPENPNE_PLUGIN_CHANNEL);
  }

  static public function getPluginListBaseUrl()
  {
    return sfConfig::get('op_plugin_list_base_url', self::OPENPNE_PLUGIN_LIST_BASE_URL);
  }

  protected function registerOpenPNEPackage()
  {
    $symfony = new PEAR_PackageFile_v2_rw();
    $symfony->setPackage('openpne');
    $symfony->setChannel('plugins.openpne.jp');
    $symfony->setConfig($this->environment->getConfig());
    $symfony->setPackageType('php');
    $symfony->setAPIVersion(preg_replace('/\d+(\-\w+)?$/', '0', OPENPNE_VERSION));
    $symfony->setAPIStability(false === strpos(OPENPNE_VERSION, 'dev') ? 'stable' : 'beta');
    $symfony->setReleaseVersion(str_replace('-', '', OPENPNE_VERSION));
    $symfony->setReleaseStability(false === strpos(OPENPNE_VERSION, 'dev') ? 'stable' : 'beta');
    $symfony->setDate(date('Y-m-d'));
    $symfony->setDescription('openpne');
    $symfony->setSummary('openpne');
    $symfony->setLicense('Apache License');
    $symfony->clearContents();
    $symfony->resetFilelist();
    $symfony->addMaintainer('lead', 'ebihara', 'Kousuke Ebihara', 'ebihara@php.net');
    $symfony->setNotes('-');
    $symfony->setPearinstallerDep('1.4.3');
    $symfony->setPhpDep('5.2.3');

    $this->environment->getRegistry()->deletePackage('openpne', 'plugins.openpne.jp');
    if (!$this->environment->getRegistry()->addPackage2($symfony))
    {
      throw new sfPluginException('Unable to register the OpenPNE package');
    }
  }
}
