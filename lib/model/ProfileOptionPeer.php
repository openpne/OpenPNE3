<?php

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
