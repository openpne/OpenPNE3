<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opColorConfig
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opColorConfig extends opConfig
{
  public static function get($name, $default = '#000000', $app = null)
  {
    if (is_null($app))
    {
      $app = sfConfig::get('sf_app');
    }
    $configName = 'op_'.$app.'_color_config_'.$name;
    $result = sfConfig::get($configName, $default);

    sfContext::getInstance()->getConfiguration()->loadHelpers('Escaping');
    return sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $result);
  }

  public function offsetExists($offset)
  {
    return(is_null(self::get($offset)));
  }

  public function offsetGet($offset)
  {
    return self::get($offset);
  }
}
