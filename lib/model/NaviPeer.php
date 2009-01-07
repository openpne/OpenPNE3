<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'navi' table.
 *
 * 
 *
 * @package lib.model
 */ 
class NaviPeer extends BaseNaviPeer
{
  public static function retrieveByType($type)
  {
    $c = new Criteria();
    $c->add(self::TYPE, $type);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);
    return self::doSelect($c);
  }

  public static function retrieveTypes()
  {
    $result = array();

    $defaultTypes = array(
      'insecure_global',
      'secure_global',
      'default',
      'friend',
      'community',
    );

    $c = new Criteria();
    $c->clearSelectColumns();
    $c->addSelectColumn(self::TYPE);
    $c->addGroupByColumn(self::TYPE);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);
    $stmt = self::doSelectStmt($c);
    while ($res = $stmt->fetchColumn(1)) {
      $result[] = $res;
    }

    return array_unique(array_merge($defaultTypes, $result));
  }
}
