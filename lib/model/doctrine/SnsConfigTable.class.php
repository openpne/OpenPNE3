<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class SnsConfigTable extends Doctrine_Table
{
  protected $configs;

  public function retrieveByName($name)
  {
    $configs = $this->getConfigs();
    return (isset($configs[$name])) ? $configs[$name] : null;
  }

  public function get($name, $default = null)
  {
    return (!is_null($config = $this->retrieveByName($name))) ? $config->getValue() : $default;
  }

  public function set($name, $value)
  {
    $config = $this->retrieveByName($name);
    if (!$config)
    {
      $config = new SnsConfig();
      $config->setName($name);
    }
    $config->setValue($value);

    $this->configs[$name] = $config;
    return $config->save();
  }

  protected function getConfigs()
  {
    if (is_null($this->configs))
    {
      $this->configs = array();

      foreach ($this->createQuery()->execute() as $config)
      {
        $this->configs[$config->name] = $config;
      }
    }

    return $this->configs;
  }
}
