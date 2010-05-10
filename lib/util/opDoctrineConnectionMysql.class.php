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
}
