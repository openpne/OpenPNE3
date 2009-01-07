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
  public static function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);

    $result = self::doSelectOne($c);
    return $result;
  }

  public static function get($name, $default = null)
  {
    $config = self::retrieveByName($name);
    return ($config) ? $config->getValue() : $default;
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
}
