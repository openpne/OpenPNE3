<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opConfigCache
 *
 * @package    OpenPNE
 * @subpackage config
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opConfigCache extends sfConfigCache
{
  /**
   * @see sfConfigCache
   */
  public function registerConfigHandler($handler, $class, $params = array())
  {
    $handler = str_replace(DIRECTORY_SEPARATOR, '/', $handler);
    parent::registerConfigHandler($handler, $class, $params);
  }

  /**
   * @see sfConfigCache
   */
  protected function writeCacheFile($config, $cache, $data)
  {
    parent::writeCacheFile($config, $cache, $data);
    @chmod($cache, 0666);
  }
}
