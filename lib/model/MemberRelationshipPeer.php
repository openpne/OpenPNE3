<?php

/**
 * Subclass for performing query and update operations on the 'member_relationship' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberRelationshipPeer extends BaseMemberRelationshipPeer
{
  public static function retrieveByFromAndTo($memberIdFrom, $memberIdTo)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $memberIdFrom);
    $c->add(self::MEMBER_ID_TO, $memberIdTo);
    return self::doSelectOne($c);
  }

  public static function retrievesByMemberIdFrom($memberId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $memberId);
    return self::doSelect($c);
  }

  public static function getFriendListPager($memberId, $page = 1, $size = 20)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberId);
    $c->add(self::IS_FRIEND, true);
    $c->addJoin(MemberPeer::ID, self::MEMBER_ID_FROM);

    $pager = new sfPropelPager('Member', $size);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
