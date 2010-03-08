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
  public function getDependency2Object(&$c, $i, $p, $s)
  {
    require_once 'PEAR/Dependency2.php';

    return new opPluginDependency($c, $i, $p, $s);
  }
}
