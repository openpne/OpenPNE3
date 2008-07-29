<?php

/**
 * Subclass for representing a row from the 'member' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Member extends BaseMember
{
  public function getProfiles()
  {
    return MemberProfilePeer::getProfileListByMemberId($this->getId());
  }

  public function getProfile($profileName)
  {
    $c = new Criteria();
    $c->add(ProfilePeer::NAME, $profileName);
    $c->add(MemberProfilePeer::MEMBER_ID, $this->getId());
    $c->addJoin(MemberProfilePeer::PROFILE_ID, ProfilePeer::ID);
    $profile = MemberProfilePeer::doSelectOne($c);
    return $profile->getValue();
  }
}
