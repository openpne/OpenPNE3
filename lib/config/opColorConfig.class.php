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
  protected static $defaultColors = array(
      'core_color_1'  => '#FFFFFF',
      'core_color_2'  => '#0D6DDF',
      'core_color_3'  => '#DDDDDD',
      'core_color_4'  => '#EEEEFF',
      'core_color_5'  => '#7DDADF',
      'core_color_6'  => '#E0EAEF',
      'core_color_7'  => '#FFFFFF',
      'core_color_8'  => '#C49FFF',
      'core_color_9'  => '#DCD1EF',
      'core_color_10' => '#FFFFFF',
      'core_color_11' => '#0D6DDF',
      'core_color_12' => '#B3CEEF',
      'core_color_13' => '#BFA4EF',
      'core_color_14' => '#000000',
      'core_color_15' => '#0000FF',
      'core_color_23' => '#FFFFFF',
      'core_color_17' => '#800080',
      'core_color_18' => '#EEEEEE',
      'core_color_19' => '#999966',
      'core_color_20' => '#0C5F0F',
      'core_color_21' => '#C49FFF',
      'core_color_22' => '#FF0000',
      'core_color_24' => '#000000',
      'core_color_25' => '#000000',
      'core_color_26' => '#000000',
      'core_color_27' => '#DDDDDD',
      'core_color_28' => '#000000',
    );

  public static function get($name, $default = '#000000', $app = null)
  {
    if (is_null($app))
    {
      $app = sfConfig::get('sf_app');
    }
    $configName = 'op_'.$app.'_color_config_'.$name;
    $result = sfConfig::get($configName, opConfig::get($app.'_'.$name, self::$defaultColors[$name]));

    sfContext::getInstance()->getConfiguration()->loadHelpers('Escaping');
    return sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), $result);
  }

  public static function set($name, $value, $app = null)
  {
    if (is_null($app))
    {
      $app = sfConfig::get('sf_app');
    }
    Doctrine::getTable('SnsConfig')->set($app.'_'.$name, $value);
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
