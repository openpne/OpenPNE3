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
  public static function retrieveByIsProfileId($profileId)
  {
    $c = new Criteria();
    $c->add(self::PROFILE_ID, $profileId);
    $c->addAscendingOrderByColumn(self::SORT_ORDER);

    $result = self::doSelect($c);
    return $result;
  }
}
