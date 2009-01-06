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
