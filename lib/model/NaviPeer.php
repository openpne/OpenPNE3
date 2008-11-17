<?php

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
    $c->addAscendingOrderByColumn(self::ID);
    $stmt = self::doSelectStmt($c);
    while ($res = $stmt->fetchColumn(1)) {
      $result[] = $res;
    }

    return array_unique(array_merge($defaultTypes, $result));
  }
}
