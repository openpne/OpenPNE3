<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opPlugin allows you to touch OpenPNE plugin.
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opPlugin
{
  private static $instances = array();

  protected
    $name,
    $isActive = true,
    $version,
    $summary;

  private function __construct($pluginName, sfEventDispatcher $dispatcher)
  {
    $this->name = $pluginName;

    $config = sfConfig::get('op_plugin_activation', array());
    if (isset($config[$pluginName]))
    {
      $this->isActive = $config[$pluginName];
    }

    $info = $this->getPackageInfo();
    if ($info)
    {
      $this->version = (string)$info->version->release;
      $this->summary = (string)$info->summary;
    }
    else
    {
      $manager = new opPluginManager($dispatcher);
      $package = $manager->getEnvironment()->getRegistry()->getPackage($pluginName, opPluginManager::OPENPNE_PLUGIN_CHANNEL);
      if ($package)
      {
        $this->version = $package->getVersion();
        $this->summary = $package->getSummary();
      }
    }
  }

  public static function getInstance($pluginName, sfEventDispatcher $dispatcher = null)
  {
    if (is_null($dispatcher))
    {
      $dispatcher = sfContext::getInstance()->getEventDispatcher();
    }

    if (empty(self::$instances[$pluginName]))
    {
      self::$instances[$pluginName] = new opPlugin($pluginName, $dispatcher);
    }

    return self::$instances[$pluginName];
  }

  public function getName()
  {
    return $this->name;
  }

  public function getIsActive()
  {
    return $this->isActive;
  }

  public function getVersion()
  {
    return $this->version;
  }

  public function getSummary()
  {
    return $this->summary;
  }

  public function hasBackend()
  {
    $path = '/apps/pc_backend/modules/'.$this->getName().'/actions';
    return (bool)sfContext::getInstance()->getConfiguration()->globEnablePlugin($path);
  }

  protected function getPackageInfo()
  {
    $xmlPath = sfConfig::get('sf_plugins_dir').'/'.$this->getName().'/package.xml';
    if (!is_readable($xmlPath))
    {
      return false;
    }
    $content = file_get_contents($xmlPath);

    return opToolkit::loadXmlString($content, array(
      'return' => 'SimpleXMLElement',
    ));
  }

  public function setIsActive($isActive)
  {
    $file = sfConfig::get('sf_data_dir').'/config/plugin.yml';
    $config = array('activation' => array());

    if (file_exists($file))
    {
      $config = array_merge($config, sfYaml::load($file));
    }

    $config['activation'][$this->getName()] = $isActive;

    file_put_contents($file, sfYaml::dump($config, 4));
    chmod($file, 0777);
  }
}
