<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
