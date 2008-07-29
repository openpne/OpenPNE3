<?php

/**
 * Subclass for performing query and update operations on the 'community' table.
 *
 * 
 *
 * @package lib.model
 */ 
class CommunityPeer extends BaseCommunityPeer
{
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
}
