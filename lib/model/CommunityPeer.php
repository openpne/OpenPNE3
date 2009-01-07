<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
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
