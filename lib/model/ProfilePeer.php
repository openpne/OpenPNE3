<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class ProfilePeer extends BaseProfilePeer
{
  const PUBLIC_FLAG_SNS = 1;
  const PUBLIC_FLAG_FRIEND = 2;
  const PUBLIC_FLAG_PRIVATE = 3;

  protected static $publicFlags = array(
    self::PUBLIC_FLAG_SNS     => 'All Members',
    self::PUBLIC_FLAG_FRIEND  => 'My Friends',
    self::PUBLIC_FLAG_PRIVATE => 'Private',
  );

  public static function getPublicFlags()
  {
    return array_map(array(sfContext::getInstance()->getI18N(), '__'), self::$publicFlags);
  }

  public static function getPublicFlag($flag)
  {
    return sfContext::getInstance()->getI18N()->__(self::$publicFlags[$flag]);
  }

  public static function retrievesAll()
  {
    $c = new Criteria();
    $c->addAscendingOrderByColumn(ProfilePeer::SORT_ORDER);
    return self::doSelect($c);
  }

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

  public static function getMaxSortOrder()
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelectOne($c);
    if ($result)
    {
      return $result->getSortOrder();
    }
    return 0;
  }
}
