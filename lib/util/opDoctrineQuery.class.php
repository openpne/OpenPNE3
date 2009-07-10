<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineQuery
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opDoctrineQuery extends Doctrine_Query
{
  protected static
    $detectedSlave = null;

  protected
    $shouldGoToMaster = false;

  public function connectToMaster($isMaster = false)
  {
    $shouldGoToMaster = (bool)$isMaster;

    return $this;
  }

  static public function getSlaveConnection()
  {
    if (!is_null(self::$detectedSlave))
    {
      return self::$detectedSlave;
    }

    $prefix = 'slave_';
    $list = array();

    $manager = sfContext::getInstance()->getDatabaseManager();
    foreach ($manager->getNames() as $name)
    {
      if (substr($name, 0, strlen($prefix)) === $prefix
        || 'doctrine' === $name
        || 'master' === $name)
      {
        $db = $manager->getDatabase($name);
        $list = array_pad($list, count($list) + (int)$db->getParameter('priority', 1), $name);
      }
    }

    shuffle($list);
    $key = array_shift($list);

    try
    {
      $connection = $manager->getDatabase($key)->getDoctrineConnection();

      self::$detectedSlave = $connection;
    }
    catch (Doctrine_Connection_Exception $e)
    {
      self::$detectedSlave = self::getMasterConnection();
    }

    return self::$detectedSlave;
  }

  static public function getMasterConnection()
  {
    $conn = null;
    $manager = sfContext::getInstance()->getDatabaseManager();

    try
    {
      $conn = $manager->getDatabase('master')->getDoctrineConnection();
    }
    catch (sfDatabaseException $e)
    {
      // retry getting connection by the old connection name
      $conn = $manager->getDatabase('doctrine')->getDoctrineConnection();
    }

    return $conn;
  }

  public function preQuery()
  {
    if (self::SELECT === $this->getType() && !$this->shouldGoToMaster)
    {
      $this->_conn = self::getSlaveConnection();
    }
    else
    {
      $this->_conn = self::getMasterConnection();
    }
  }
}
