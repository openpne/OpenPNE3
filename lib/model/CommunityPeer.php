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
 * Subclass for performing query and update operations on the 'community' table.
 *
 * 
 *
 * @package lib.model
 */ 
class CommunityPeer extends BaseCommunityPeer
{
  public static function retrievesByMemberId($memberId, $limit = 5, Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(CommunityMemberPeer::MEMBER_ID, $memberId);
    $c->addJoin(self::ID, CommunityMemberPeer::COMMUNITY_ID);
    $c->setLimit($limit);

    return self::doSelect($c);
  }

  public static function getJoinCommunityListPager($memberId, $page = 1, $size = 20)
  {
    $c = new Criteria();
    $c->add(CommunityMemberPeer::MEMBER_ID, $memberId);
    $c->addJoin(self::ID, CommunityMemberPeer::COMMUNITY_ID);

    $pager = new sfPropelPager('Community', $size);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public static function getCommunityMemberListPager($communityId, $page = 1, $size = 20)
  {
    $c = new Criteria();
    $c->add(CommunityMemberPeer::COMMUNITY_ID, $communityId);
    $c->addJoin(MemberPeer::ID, CommunityMemberPeer::MEMBER_ID);

    $pager = new sfPropelPager('Member', $size);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  static public function getIdsByMemberId($memberId)
  {
    $result = array();

    $c = new Criteria();
    $c->clearSelectColumns()->addSelectColumn(self::ID);
    $c->add(CommunityMemberPeer::MEMBER_ID, $memberId);
    $c->addJoin(self::ID, CommunityMemberPeer::COMMUNITY_ID);
    $stmt = self::doSelectStmt($c);

    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $result[] = $row[0];
    }

    return $result;
  }
}
