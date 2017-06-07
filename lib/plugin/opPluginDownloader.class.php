<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * The opPluginDownloader class
 *
 * @package    OpenPNE
 * @subpackage plugin
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opPluginDownloader extends sfPearDownloader
{
  protected static $cachedDependency = array();

  public function getDependency2Object(&$c, $i, $p, $s)
  {
    require_once 'PEAR/Dependency2.php';

    if ($this->getCachedDependency($p['channel'], $p['package']))
    {
      return $this->getCachedDependency($p['channel'], $p['package']);
    }

    $obj = new opPluginDependency($c, $i, $p, $s);
    self::$cachedDependency[$p['channel'].'-'.$p['package']] = $obj;

    return $obj;
  }

  public static function getCachedDependency($channel, $package)
  {
    $key = $channel.'-'.$package;

    if (isset(self::$cachedDependency[$key]))
    {
      return self::$cachedDependency[$key];
    }

    return null;
  }
}
