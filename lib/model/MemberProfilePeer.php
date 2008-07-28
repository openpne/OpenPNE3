<?php

/**
 * Subclass for performing query and update operations on the 'member_profile' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberProfilePeer extends BaseMemberProfilePeer
{
  public static function retrieveByMemberIdAndProfileId($memberId, $profileId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID, $memberId);
    $c->add(self::PROFILE_ID, $profileId);

    $result = self::doSelectOne($c);
    return $result;
  }

  public static function retrieveByMemberIdAndProfileName($memberId, $profileName)
  {
    $profile = ProfilePeer::retrieveByName($profileName);

    return self::retrieveByMemberIdAndProfileId($memberId, $profile->getId());
  }
}
