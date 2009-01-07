<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * OpenPNEConfig stores all configuration information for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
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
    $config_name = 'openpne_' . $type . '_config';
    $result = null;

    if (isset(parent::$config[$config_name][$name]))
    {
      if ($type == 'sns')
      {
        $obj = SnsConfigPeer::retrieveByName($name);
        if ($obj)
        {
          $result = $obj->getValue();
        }
      }

      if (is_null($result))
      {
        $config = parent::get($config_name);
        $result = $config[$name]['default'];
      }
    }

    return $result;
  }

  public static function loadConfigYaml($type = 'sns')
  {
    $sf_data_dir = sfConfig::get('sf_data_dir');
    return sfYaml::load($sf_data_dir . DIRECTORY_SEPARATOR . $type . '_config.yml');
  }
}
