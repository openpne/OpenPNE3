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
  /**
   * Retrieves a config parameter.
   *
   * @param  string $name    A config parameter name
   * @param  string $type    A type name
   * @param  mixed  $default A default config parameter value
   * @return string A config parameter value
   */
  public static function get($name, $type = 'sns', $default = null)
  {
    $config_name = 'openpne_' . $type . '_' . $name;

    if (isset(parent::$config[$config_name])) {
      return parent::get($config_name);
    }

    $config = SnsConfigPeer::retrieveByName($name);
    if ($config) {
      self::set($config_name, $config->getValue());
      return $config->getValue();
    }

    $yaml = self::loadConfigYaml($type);
    if (isset($yaml[$name]['default']) && is_null($default)) {
      $default = $yaml[$name]['default'];
    }

    if (!is_null($default)) {
      self::set($config_name, $default);
    }
    return $default;
  }

  public static function loadConfigYaml($type = 'sns')
  {
    $sf_data_dir = sfConfig::get('sf_data_dir');
    return sfYaml::load($sf_data_dir . DIRECTORY_SEPARATOR . $type . '_config.yml');
  }
}
