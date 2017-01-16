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
  /**
   * constant for exact match
   */
  const MATCH_EXACT = 0;

  /**
   * constant for left-hand match
   */
  const MATCH_LEFT = 1;

  /**
   * constant for right-hand match
   */
  const MATCH_RIGHT = 2;

  /**
   * constant for broad match
   */
  const MATCH_BROAD = 3;

  protected static $detectedSlave = null;

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

    if (Doctrine_Transaction::STATE_SLEEP === self::getMasterConnection()->transaction->getState()
      && (self::SELECT === $queryType && !$shouldGoToMaster)
    )
    {
      return self::getSlaveConnection();
    }

    return self::getMasterConnection();
  }

  public function preQuery()
  {
    if ($this->_passedConn)
    {
      return;
    }

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

  public function execute($params = array(), $hydrationMode = null)
  {
    $origWhereInCount = $this->whereInCount;

    foreach ($params as $param)
    {
      if (is_array($param))
      {
        $this->addWhereInCount(count($param));
      }
    }

    $results = parent::execute($params, $hydrationMode);

    $this->whereInCount = $origWhereInCount;

    return $results;
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
    if (!$result)
    {
      $result = parent::calculateQueryCacheHash();
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

  public function whereLike($expr, $param, $matchType = self::MATCH_BROAD, $not = false)
  {
    return $this->andWhereLike($expr, $param, $matchType, $not);
  }

  public function andWhereLike($expr, $param, $matchType = self::MATCH_BROAD, $not = false)
  {
    if (self::MATCH_EXACT != $matchType)
    {
      $param = $this->escapePattern($param);

      switch ($matchType)
      {
        case self::MATCH_LEFT:
          $param = $param.'%';
          break;
        case self::MATCH_RIGHT:
          $param = '%'.$param;
          break;
        case self::MATCH_BROAD:
          $param = '%'.$param.'%';
          break;
      }
    }

    if ($not)
    {
      $this->andWhere($expr.' NOT LIKE ?', $param);
    }
    else
    {
      $this->andWhere($expr.' LIKE ?', $param);
    }

    return $this;
  }

  public function escapePattern($text)
  {
    $conn = $this->getConnection();

    if (!$conn->string_quoting['escape_pattern'])
    {
      return $text;
    }
    $tmp = $conn->string_quoting;

    $text = str_replace($tmp['escape_pattern'], $tmp['escape_pattern'].$tmp['escape_pattern'], $text);

    foreach ($conn->wildcards as $wildcard) {
      $text = str_replace($wildcard, $tmp['escape_pattern'].$wildcard, $text);
    }

    return $text;
  }
}
