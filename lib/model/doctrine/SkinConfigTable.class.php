<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class SkinConfigTable extends Doctrine_Table
{
  protected $configs;

  public function retrieveByPluginAndName($plugin, $name)
  {
    $configs = $this->getConfigs();

    return (isset($configs[$plugin][$name])) ? $configs[$plugin][$name] : null;
  }

  public function get($plugin, $name, $default = null)
  {
    return (!is_null($config = $this->retrieveByPluginAndName($plugin, $name))) ? $config->getValue() : $default;
  }

  public function set($plugin, $name, $value)
  {
    $config = $this->retrieveByPluginAndName($plugin, $name);
    if (!$config)
    {
      $config = self::create(array(
        'name'   => $name,
        'plugin' => $plugin,
      ));
    }
    $config->setValue($value);

    $this->configs[$plugin][$name] = $config;
    return $config->save();
  }

  protected function getConfigs()
  {
    if (is_null($this->configs))
    {
      $this->configs = array();

      foreach ($this->createQuery()->execute() as $config)
      {
        $this->configs[$config->plugin][$config->name] = $config;
      }
    }

    return $this->configs;
  }
}
