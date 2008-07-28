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
  public function getProfile($profileName)
  {
    return MemberProfilePeer::retrieveByMemberIdAndProfileName($this->getId(), $profileName);
  }
}
