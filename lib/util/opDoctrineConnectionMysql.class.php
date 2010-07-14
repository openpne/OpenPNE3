<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineConnectionMysql
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opDoctrineConnectionMysql extends Doctrine_Connection_Mysql
{
  protected $hashByQuery = array();

  public function __get($name)
  {
    if ('formatter' === $name && !isset($this->properties[$name]))
    {
      $this->properties[$name] = new opDoctrineModule_Formatter($this);
    }

    return parent::__get($name);
  }

  public function quoteIdentifier($str, $checkOption = true)
  {
    // non-identifiers
    // (See http://dev.mysql.com/doc/refman/5.5/en/reserved-words.html)
    if (
      // most-used in Doctrine
      'id' === $str || 'created_at' === $str || 'updated_at' === $str || 'lft' === $str ||
      'rgt' === $str || 'tree_key' === $str || 'level' === $str

      // most-used in OpenPNE
      || 'public_flag' === $str || 'is_active' === $str || 'body' === $str || 'title' === $str
      || 'name' === $str || 'value' === $str

      // won't be identifiers
      || 1 === strlen($str)
      || strpos($str, '__') || strpos($str, '_id'))
    {
      return $str;
    }

    return parent::quoteIdentifier($str, $checkOption);
  }

  public function query($query, array $params = array(), $hydrationMode = null)
  {
    $parser = Doctrine_Query::create();

    $key = md5($query);
    if (isset($this->hashByQuery[$key]))
    {
      $parser->setCachedQueryCacheHash($this->hashByQuery[$key][0]);

      if ($this->hashByQuery[$key][1] && $this->hashByQuery[$key][2])
      {
        $parser->from($this->hashByQuery[$key][1])
          ->where($this->hashByQuery[$key][2]);
      }
      else
      {
        $parser->parseDqlQuery($query);
      }

      $res = $parser->execute($params, $hydrationMode);
    }
    else
    {
      $res = $parser->query($query, $params, $hydrationMode);

      $froms = $parser->getFrom();
      $from = $froms[0];
      if (1 < count($froms))
      {
        $from = '';
      }
      $where = '';

      $simpleRelationQuery = 'FROM '.$from.' WHERE '.$from.'.id = ?';
      $simpleRelationInQuery = 'FROM '.$from.' WHERE '.$from.'.id IN (?)';
      if ($query === $simpleRelationQuery)
      {
        $where = $from.'.id = ?';
      }
      elseif ($query === $simpleRelationInQuery)
      {
        $where = $from.'.id IN (?)';
      }

      $this->hashByQuery[$key] = array($parser->calculateQueryCacheHash(), $from, $where);
    }

    $parser->free();

    return $res;
  }
}
