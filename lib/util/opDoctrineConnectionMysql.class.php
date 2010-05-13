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
}
