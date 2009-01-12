<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'sns_config' table.
 *
 * 
 *
 * @package lib.model
 */ 
class SnsConfigPeer extends BaseSnsConfigPeer
{
  protected static $configs;

  public static function retrieveByName($name)
  {
    $configs = self::getConfigs();

    return (isset($configs[$name])) ? $configs[$name] : null;
  }

  public static function get($name, $default = null)
  {
    return (!is_null($config = self::retrieveByName($name))) ? $config->getValue() : $default;
  }

  public static function set($name, $value)
  {
    $config = self::retrieveByName($name);
    if (!$config)
    {
      $config = new SnsConfig();
      $config->setName($name);
    }
    $config->setValue($value);
    return $config->save();
  }

  protected static function getConfigs()
  {
    if (is_null(self::$configs))
    {
      self::$configs = array();
      foreach (self::doSelect(new Criteria()) as $config)
      {
        self::$configs[$config->getName()] = $config;
      }
    }

    return self::$configs;
  }
}
