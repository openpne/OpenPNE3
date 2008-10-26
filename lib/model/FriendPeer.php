<?php

/**
 * Subclass for performing query and update operations on the 'friend' table.
 *
 * 
 *
 * @package lib.model
 */ 
class FriendPeer extends BaseFriendPeer
{
  public static function link($memberIdTo, $memberIdFrom)
  {
    if (self::isFriend($memberIdTo, $memberIdFrom)) {
      throw new Exception('These members have already linked.');
    }

    $friend1 = new Friend();
    $friend1->setMemberIdTo($memberIdTo);
    $friend1->setMemberIdFrom($memberIdFrom);
    $friend1->save();

    $friend2 = new Friend();
    $friend2->setMemberIdTo($memberIdFrom);
    $friend2->setMemberIdFrom($memberIdTo);
    $friend2->save();
  }

  public static function unlink($memberIdTo, $memberIdFrom)
  {
    if (!self::isFriend($memberIdTo, $memberIdFrom)) {
      throw new Exception('These members don\'t link each other.');
    }

    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberIdTo);
    $c->add(self::MEMBER_ID_FROM, $memberIdFrom);
    self::doSelectOne($c)->delete();

    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberIdFrom);
    $c->add(self::MEMBER_ID_FROM, $memberIdTo);
    self::doSelectOne($c)->delete();
  }

  public static function isFriend($memberIdTo, $memberIdFrom)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberIdTo);
    $c->add(self::MEMBER_ID_FROM, $memberIdFrom);
    $result = self::doSelectOne($c);

    if ($result) {
      return true;
    }

    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberIdFrom);
    $c->add(self::MEMBER_ID_FROM, $memberIdTo);
    $result = self::doSelectOne($c);

    if ($result) {
      return true;
    }

    return false;
  }

  public static function retrievesByMemberId($memberId, $size = 5)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $memberId);
    $c->addJoin(MemberPeer::ID, self::MEMBER_ID_TO);
    $c->setLimit($size);

    return self::doSelect($c);
  }

  public static function getFriendListPager($memberId, $page = 1, $size = 20)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $memberId);
    $c->addJoin(MemberPeer::ID, self::MEMBER_ID_TO);

    $pager = new sfPropelPager('Member', $size);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
