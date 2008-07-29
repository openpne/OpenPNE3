<?php

/**
 * Subclass for performing query and update operations on the 'community_member' table.
 *
 * 
 *
 * @package lib.model
 */ 
class CommunityMemberPeer extends BaseCommunityMemberPeer
{
  public static function retrieveByMemberIdAndCommunityId($memberId, $communityId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID, $memberId);
    $c->add(self::COMMUNITY_ID, $communityId);
    return self::doSelectOne($c);
  }

  public static function isMember($memberId, $communityId)
  {
    return (bool)self::retrieveByMemberIdAndCommunityId($memberId, $communityId);
  }

  public static function isAdmin($memberId, $communityId)
  {
    $communityMember = self::retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember) {
      return false;
    }

    if ($communityMember->getPosition() != 'admin') {
      return false;
    }

    return true;
  }

  public static function join($memberId, $communityId)
  {
    $communityMember = new CommunityMember();
    $communityMember->setMemberId($memberId);
    $communityMember->setCommunityId($communityId);
    $communityMember->save();
  }

  public static function quit($memberId, $communityId)
  {
    $communityMember = self::retrieveByMemberIdAndCommunityId($memberId, $communityId);
    $communityMember->delete();
  }
}
