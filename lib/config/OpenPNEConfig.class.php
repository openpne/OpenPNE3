<?php

/**
 * OpenPNEConfig stores all configuration information for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.net>
 */
class OpenPNEConfig extends sfConfig
{
  private $prefix = 'openpne_';

  /**
   * Retrieves a config parameter.
   *
   * @param  string $name    A config parameter name
   * @param  mixed  $default A default config parameter value
   * @return string A config parameter value
   */
  public static function get($name, $default = null)
  {
    $config_name = 'openpne_' . $name;

    if (isset(parent::$config[$config_name])) {
      return parent::get($config_name);
    }

    $config = SnsConfigPeer::retrieveByName($name);
    if ($config->getId()) {
      self::set($name, $config->getValue());
      return $config->getValue();
    }

    $yaml = self::loadConfigYaml();
    if (isset($yaml[$name]['default'])) {
      return $yaml[$name]['default'];
    }

    return $default;
  }

  public static function loadConfigYaml()
  {
    $sf_data_dir = sfConfig::get('sf_data_dir');
    return sfYaml::load($sf_data_dir . DIRECTORY_SEPARATOR . 'sns_config.yml');
  }

}
