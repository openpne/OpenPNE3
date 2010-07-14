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
    $detectedSlave = null,
    $findQueryCacheKeys = array();

  protected
    $shouldGoToMaster = false,
    $isFoundRows = false,
    $specifiedConnection = null,
    $whereInCount = '',
    $cachedQueryCacheHash = '';

  public function connectToMaster($isMaster = false)
  {
    $shouldGoToMaster = (bool)$isMaster;

    return $this;
  }

  public function specifyConnection($conn)
  {
    $this->specifiedConnection = $conn;

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

  static public function getMasterConnectionDirect()
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

  static public function chooseConnection($shouldGoToMaster = true, $queryType = self::SELECT)
  {
    if (!sfContext::hasInstance())
    {
      return self::getMasterConnectionDirect();
    }

    elseif (self::SELECT === $queryType && !$shouldGoToMaster)
    {
      return self::getSlaveConnection();
    }

    return self::getMasterConnection();
  }

  public function preQuery()
  {
    if ($this->specifiedConnection)
    {
      $this->_conn = $this->specifiedConnection;
    }
    else
    {
      $this->_conn = self::chooseConnection($this->shouldGoToMaster, $this->getType());
    }
  }

  public function fetchOne($params = array(), $hydrationMode = null)
  {
    $this->limit(1);

    return parent::fetchOne($params, $hydrationMode);
  }

  public function andWhereIn($expr, $params = array(), $not = false)
  {
    if (isset($params) && (count($params) == 0))
    {
      if (!$not)
      {
        return $this->andWhere('0 = 1');
      }
      else
      {
        return parent::andWhereIn($expr, $params, $not);
      }
    }

    $this->addWhereInCount(count($params));

    if ($not)
    {
      $this->andWhere($expr.' NOT IN ?', array($params));
    }
    else
    {
      $this->andWhere($expr.' IN ?', array($params));
    }

    return $this;
  }

  public function setIsFoundRows($isFoundRows)
  {
    $this->isFoundRows = (bool)$isFoundRows;

    return $this;
  }

  public function addWhereInCount($count)
  {
    $this->whereInCount .= '-'.$count;

    return $this;
  }

  public function calculateQueryCacheHash()
  {
    if ($this->cachedQueryCacheHash)
    {
      $result = $this->cachedQueryCacheHash;

      $this->cachedQueryCacheHash = '';

      return $result;
    }

    $result = '';
    $findQueryCacheKey = '';

    if (isset($this->_dqlParts['from'][0]))
    {
      if (strpos($this->_dqlParts['from'][0], 'dctrn_find'))
      {
        $findQueryCacheKey = md5($this->_dqlParts['from'][0].count($this->getFlattenedParams()));
      }

      if (isset(self::$findQueryCacheKeys[$findQueryCacheKey]))
      {
        $result = self::$findQueryCacheKeys[$findQueryCacheKey];
      }
    }

    if (!$result)
    {
      $result = parent::calculateQueryCacheHash();
      if ($findQueryCacheKey)
      {
        self::$findQueryCacheKeys[$findQueryCacheKey] = $result;
      }
    }

    if ($this->isFoundRows)
    {
      $result .= ':fr';
    }

    if ($this->whereInCount)
    {
      $result .= ':count'.$this->whereInCount;
    }

    return $result;
  }

  public function setCachedQueryCacheHash($hash)
  {
    $this->cachedQueryCacheHash = $hash;
  }

  protected function _buildSqlQueryBase()
  {
    switch ($this->_type)
    {
      case self::DELETE:
        $q = 'DELETE FROM ';
        break;
      case self::UPDATE:
        $q = 'UPDATE ';
        break;
      case self::SELECT:
        $distinct = ($this->_sqlParts['distinct']) ? 'DISTINCT ' : '';
        $foundRows = ($this->isFoundRows) ? 'SQL_CALC_FOUND_ROWS ' : '';
        $q = 'SELECT '.$foundRows.$distinct.implode(', ', $this->_sqlParts['select']).' FROM ';
        break;
    }

    return $q;
  }

  public function getFrom()
  {
    return $this->_dqlParts['from'];
  }
}
