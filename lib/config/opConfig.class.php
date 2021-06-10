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
class opConfig extends sfConfig implements ArrayAccess
{
  protected static function getConfigurationSetting()
  {
    return parent::$config['openpne_sns_config'];
  }

  protected static function getDefaultValue($name)
  {
    $setting = self::getConfigurationSetting();

    if (isset($setting[$name]['Default']))
    {
      return $setting[$name]['Default'];
    }

    return null;
  }

 /**
  * Retrieves a config parameter.
  *
  * sns_config テーブルに該当する値が存在しない場合は、sns_config.yml に設定したデフォルト値が返却される。
  * sns_config テーブルに該当する値が存在せず、且つ sns_config.yml にデフォルト値が設定されていない場合に、引数の $default が返却される。
  *
  * @param  string $name    A config parameter name
  * @param  mixed  $default A default config parameter value
  *
  * @return mixed A config parameter value
  */
  public static function get($name, $default = null)
  {
    $setting = self::getConfigurationSetting();
    $result = Doctrine::getTable('SnsConfig')->get($name, null);

    if (isset($setting[$name]))
    {
      if (is_null($result))
      {
        $result = self::getDefaultValue($name);
      }
    }

    return !is_null($result) ? $result : $default;
  }

 /**
  * Returns if the offset exists
  *
  * @see ArrayAccess::offsetExists()
  */
  public function offsetExists($offset)
  {
    return(!is_null(self::get($offset)));
  }

 /**
  * Returns value at given offset
  *
  * @see ArrayAccess::offsetGet()
  */
  public function offsetGet($offset)
  {
    return sfOutputEscaper::escape(sfConfig::get('sf_escaping_method'), self::get($offset));
  }

 /**
  * @see ArrayAccess::offsetSet()
  */
  public function offsetSet($offset, $value)
  {
    // Nothing to do.
    // This class is a read-only.
  }

 /**
  * @see ArrayAccess::offsetUnSet()
  */
  public function offsetUnSet($offset)
  {
    // Nothing to do.
    // This class is a read-only.
  }
}
