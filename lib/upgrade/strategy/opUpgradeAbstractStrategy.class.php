<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opUpgradeAbstractStrategy
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class opUpgradeAbstractStrategy
{
  protected $options;

  protected static $dbManager = null;

  public function __construct($options)
  {
    $this->options = $options;
  }

  abstract public function run();

  protected function getDatabaseManager()
  {
    if (!self::$dbManager)
    {
      self::$dbManager = new sfDatabaseManager($this->options['configuration']);
    }

    return self::$dbManager;
  }

  public function setOption($name, $value)
  {
    $this->options[$name] = $value;
  }

  public function getOption($name, $default = null)
  {
    if (!isset($this->options[$name]))
    {
      return $default;
    }

    return $this->options[$name];
  }

  protected function dataLoad($path)
  {
    opApplicationConfiguration::unregisterZend();
    Doctrine::loadData($path, true);
    opApplicationConfiguration::registerZend();
  }
}

