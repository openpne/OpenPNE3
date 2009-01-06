<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
    if (self::isMember($memberId, $communityId)) {
      throw new Exception('This member has already joined this community.');
    }

    $communityMember = new CommunityMember();
    $communityMember->setMemberId($memberId);
    $communityMember->setCommunityId($communityId);
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
}
