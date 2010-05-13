<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opDoctrineFormatter
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class opDoctrineModule_Formatter extends Doctrine_Formatter
{
  public function getForeignKeyName($fkey)
  {
    $prefix = sfConfig::get('op_table_prefix', '');
    if ($prefix && 0 !== strpos($fkey, $prefix))
    {
      $fkey = $prefix.$fkey;
    }

    return $fkey;
  }
}
