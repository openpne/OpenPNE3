<?php

/**
 * Subclass for performing query and update operations on the 'admin_user' table.
 *
 * 
 *
 * @package lib.model
 */ 
class AdminUserPeer extends BaseAdminUserPeer
{
  public static function retrieveByUsername($username)
  {
    $c = new Criteria();
    $c->add(self::USERNAME, $username);
    return self::doSelectOne($c);
  }

  public static function retrievesAll()
  {
    $c = new Criteria();
    return self::doSelect($c);
  }
}
