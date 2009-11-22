<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opSecurityConfigHandler
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opSecurityConfigHandler extends sfSecurityConfigHandler
{
  public function execute($configFiles)
  {
    $configFiles = self::filterOpenPNEPluginConfig($configFiles);

    return parent::execute($configFiles);
  }

  static public function getConfiguration(array $configFiles)
  {
    $configFiles = self::filterOpenPNEPluginConfig($configFiles);

    return parent::getConfiguration($configFiles);
  }

  static protected function filterOpenPNEPluginConfig($configFiles)
  {
    // A security.yml in the plugin root configuration directory should be ignored
    $regexp = '/'.preg_quote(sfConfig::get('sf_plugins_dir').DIRECTORY_SEPARATOR, '/')
            . 'op.+Plugin'.preg_quote(DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'security.yml', '/').'/';

    foreach ($configFiles as $k => $v)
    {
      if (preg_match($regexp, $v))
      {
        unset($configFiles[$k]);
      }
    }

    return $configFiles;
  }
}
