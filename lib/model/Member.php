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
    $profile = MemberProfilePeer::retrieveByMemberIdAndProfileName($this->getId(), $profileName);

    if (!$profile) {
      return null;
    }

    return $profile->getValue();
  }

  public function getFriends($limit = null)
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    if (!is_null($limit)) {
      $c->setLimit($limit);
    }
    return $this->getMemberRelationshipsRelatedByMemberIdTo($c);
  }

  public function countFriends()
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    return $this->countMemberRelationshipsRelatedByMemberIdTo($c);
  }

  public function getFriendPreTo()
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->getMemberRelationshipsRelatedByMemberIdTo($c);
  }

  public function countFriendPreTo()
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->countMemberRelationshipsRelatedByMemberIdTo($c);
  }

  public function getFriendPreFrom()
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->getMemberRelationshipsRelatedByMemberIdFrom($c);
  }

  public function countFriendPreFrom()
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->countMemberRelationshipsRelatedByMemberIdFrom($c);
  }
}
