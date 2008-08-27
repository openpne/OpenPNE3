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
  public static function getProfileListByMemberId($memberId)
  {
    $profiles = array();

    $c = new Criteria();

    parent::addSelectColumns($c);

    $c->addSelectColumn(ProfilePeer::NAME);
    $c->addSelectColumn(ProfileI18nPeer::CAPTION);

    $c->add(self::MEMBER_ID, $memberId);
    $c->addJoin(ProfilePeer::ID, ProfileI18nPeer::ID);
    $c->addJoin(ProfilePeer::ID, MemberProfilePeer::PROFILE_ID);
    $c->addAscendingOrderByColumn(ProfilePeer::SORT_ORDER);

    $rs = self::doSelectRS($c);

    while ($rs->next()) {
      $obj = new MemberProfile();
      $obj->hydrateProfiles($rs);
      $profiles[] = $obj;
    }

    return $profiles;
  }

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
    $c = new Criteria();
    $c->add(ProfilePeer::NAME, $profileName);
    $c->add(MemberProfilePeer::MEMBER_ID, $memberId);
    $c->addJoin(MemberProfilePeer::PROFILE_ID, ProfilePeer::ID);
    return MemberProfilePeer::doSelectOne($c);
  }
}
