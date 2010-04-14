<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'profile_option' table.
 *
 * 
 *
 * @package lib.model
 */ 
class ProfileOptionPeer extends BaseProfileOptionPeer
{
  public static function retrieveByProfileId($profileId)
  {
    $c = new Criteria();
    $c->add(self::PROFILE_ID, $profileId);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }

  public static function getMaxSortOrder($profileId)
  {
    $c = new Criteria();
    $c->add(self::PROFILE_ID, $profileId);
    $c->addDescendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelectOne($c);
    if ($result)
    {
      return $result->getSortOrder();
    }
    return false;
  }
}
