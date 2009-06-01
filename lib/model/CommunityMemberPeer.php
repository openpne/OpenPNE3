<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

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
    $communityMember = self::retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      return false;
    }
    return ($communityMember->getPosition() != 'pre');
  }

  public static function isPreMember($memberId, $communityId)
  {
    $communityMember = self::retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      return false;
    }
    return ($communityMember->getPosition() == 'pre');
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

  public static function join($memberId, $communityId, $isRegisterPoricy = 'open')
  {
    if (self::isPreMember($memberId, $communityId))
    {
      throw new Exception('This member has already applied this community.');
    }

    if (self::isMember($memberId, $communityId))
    {
      throw new Exception('This member has already joined this community.');
    }

    $communityMember = new CommunityMember();
    $communityMember->setMemberId($memberId);
    $communityMember->setCommunityId($communityId);
    if ($isRegisterPoricy == 'close')
    {
      $communityMember->setPosition('pre');
    }
    $communityMember->save();
  }

  public static function quit($memberId, $communityId)
  {
    if (!self::isMember($memberId, $communityId)) {
      throw new Exception('This member is not a member of this community.');
    }

    if (self::isAdmin($memberId, $communityId)) {
      throw new Exception('This member is community admin.');
    }

    $communityMember = self::retrieveByMemberIdAndCommunityId($memberId, $communityId);
    $communityMember->delete();
  }

  public static function getCommunityAdmin($communityId)
  {
    $c = new Criteria();
    $c->add(self::COMMUNITY_ID, $communityId);
    $c->add(self::POSITION, 'admin');
    return self::doSelectOne($c);
  }

  public static function getCommunityIdsOfAdminByMemberId($memberId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID, $memberId);
    $c->add(self::POSITION, 'admin');
    $communityMembers = self::doSelect($c);

    $result = array();

    foreach ($communityMembers as $communityMember)
    {
      $result[] = $communityMember->getCommunityId();
    }

    return $result;
  }

  public static function getCommunityMembersPre($memberId)
  {
    $adminCommunityIds = self::getCommunityIdsOfAdminByMemberId($memberId);
    
    if (count($adminCommunityIds))
    {
      $c = new Criteria();
      $c->add(self::COMMUNITY_ID, $adminCommunityIds, Criteria::IN);
      $c->add(self::POSITION, 'pre');
      return self::doSelect($c);
    }
    return array();
  }

  public static function getCommunityMemberCount($communityId)
  {
    $c = new Criteria();
    $c->add(self::COMMUNITY_ID, $communityId);
    $c->add(self::POSITION, '');

    return self::doCount($c);
  }
}
