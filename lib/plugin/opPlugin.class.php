<?php

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

  private function __construct($pluginName)
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
  }

  public static function getInstance($pluginName)
  {
    if (empty(self::$instances[$pluginName]))
    {
      self::$instances[$pluginName] = new opPlugin($pluginName);
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

  protected function getPackageInfo()
  {
    $xmlPath = sfConfig::get('sf_plugins_dir').'/'.$this->getName().'/package.xml';
    if (!is_readable($xmlPath))
    {
      return false;
    }
    return simplexml_load_file($xmlPath);
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
