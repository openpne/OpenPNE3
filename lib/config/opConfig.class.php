<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opConfig is a wrapper class to handle SnsConfig
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opConfig extends sfConfig
{
  /**
   * Retrieves a config parameter.
   *
   * @param  string $name    A config parameter name
   * @param  mixed  $default A default config parameter value
   *
   * @return mixed A config parameter value
   */
  public static function get($name, $default = null)
  {
    $setting = parent::$config['openpne_sns_config'];
    $result = null;

    if (isset($setting[$name]))
    {
      $result = SnsConfigPeer::get($name, $default);
      if (is_null($result))
      {
        $result = $setting[$name]['default'];
      }
    }

    return $result;
  }
}
