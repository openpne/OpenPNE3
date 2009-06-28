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
  protected $shouldGoToMaster = false;

  public function connectToMaster($isMaster = false)
  {
    $shouldGoToMaster = (bool)$isMaster;

    return $this;
  }

  static public function getSlaveConnection()
  {
    $prefix = 'slave_';

    $connections = Doctrine_Manager::getInstance()->getConnections();
    shuffle($connections);

    foreach ($connections as $name => $conn)
    {
      if (substr($name, 0, strlen($prefix)) === $prefix)
      {
        return $conn;
      }
    }

    return self::getMasterConnection();
  }

  static public function getMasterConnection()
  {
    $conn = null;

    try
    {
      $conn = Doctrine_Manager::getInstance()->getConnection('master');
    }
    catch (Doctrine_Manager_Exception $e)
    {
      // retry getting connection by the old connection name
      $conn = Doctrine_Manager::getInstance()->getConnection('doctrine');
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
