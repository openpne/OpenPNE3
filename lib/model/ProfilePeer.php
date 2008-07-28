<?php

/**
 * Subclass for performing query and update operations on the 'profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class ProfilePeer extends BaseProfilePeer
{
  public static function retrieveByIsDispRegist()
  {
    $c = new Criteria();
    $c->add(self::IS_DISP_REGIST, true);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }

  public static function retrieveByIsDispConfig()
  {
    $c = new Criteria();
    $c->add(self::IS_DISP_CONFIG, true);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }

  public static function retrieveByIsDispSearch()
  {
    $c = new Criteria();
    $c->add(self::IS_DISP_SEARCH, true);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }

  public static function retrieveByName($name)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);

    $result = self::doSelectOne($c);
    return $result;
  }
}
