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
    $c = new Criteria();
    $c->addGroupByColumn(self::TYPE);
    return self::doSelect($c);
  }
}
