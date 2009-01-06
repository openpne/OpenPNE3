<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
