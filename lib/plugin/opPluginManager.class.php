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

  public function __construct(sfEventDispatcher $dispatcher, sfPearEnvironment $environment = null)
  {
    if (!$environment)
    {
      $tz = @date_default_timezone_get();

      $environment = new sfPearEnvironment($dispatcher, array(
        'plugin_dir' => sfConfig::get('sf_plugins_dir'),
        'cache_dir' => sfConfig::get('sf_cache_dir').'/.pear',
        'web_dir' => sfConfig::get('sf_web_dir'),
        'rest_base_class' => 'opPearRest',
      ));

      date_default_timezone_set($tz);

      try
      {
        $environment->registerChannel(self::OPENPNE_PLUGIN_CHANNEL, true);
      }
      catch (sfPluginException $e) {}
    }

    parent::__construct($dispatcher, $environment);
  }

  public function getChannel()
  {
    return $this->getEnvironment()->getRegistry()->getChannel(self::OPENPNE_PLUGIN_CHANNEL);
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
        'c' => opPluginManager::OPENPNE_PLUGIN_CHANNEL,
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

  /**
   * registerSymfonyPackage
   *
   * Copied from sfSymfonyPluginManager::registerSymfonyPackage() and changed symfony version
   * that is reported to PEAR installer to 1.9.9 because plugin installer tries downloading the
   * newest stable version of symfony every-time even if the project has the own symfony.
   * OpenPNE is distributed with the own symfony so this behavior is just cumber.
   * For bypassing from downloading symfony, OpenPNE cheats about its symfony version.
   * The last symfony 1.x is 1.4.x. Probably 1.9.9 will be never released.
   */
  protected function registerSymfonyPackage()
  {
    $symfony = new PEAR_PackageFile_v2_rw();
    $symfony->setPackage('symfony');
    $symfony->setChannel('pear.symfony-project.com');
    $symfony->setConfig($this->environment->getConfig());
    $symfony->setPackageType('php');
    $symfony->setAPIVersion('1.1.0');
    $symfony->setAPIStability('stable');
    $symfony->setReleaseVersion('1.9.9');
    $symfony->setReleaseStability('stable');
    $symfony->setDate(date('Y-m-d'));
    $symfony->setDescription('symfony');
    $symfony->setSummary('symfony');
    $symfony->setLicense('MIT License');
    $symfony->clearContents();
    $symfony->resetFilelist();
    $symfony->addMaintainer('lead', 'fabpot', 'Fabien Potencier', 'fabien.potencier@symfony-project.com');
    $symfony->setNotes('-');
    $symfony->setPearinstallerDep('1.4.3');
    $symfony->setPhpDep('5.1.0');

    $this->environment->getRegistry()->deletePackage('symfony', 'pear.symfony-project.com');
    if (!$this->environment->getRegistry()->addPackage2($symfony))
    {
      throw new sfPluginException('Unable to register the symfony package');
    }
  }
}
