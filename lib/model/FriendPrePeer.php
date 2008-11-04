<?php

/**
 * Subclass for performing query and update operations on the 'friend_pre' table.
 *
 * 
 *
 * @package lib.model
 */ 
class FriendPrePeer extends BaseFriendPrePeer
{
  public static function retrieveByMemberIdToAndMemberIdFrom($memberIdTo, $memberIdFrom)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberIdTo);
    $c->add(self::MEMBER_ID_FROM, $memberIdFrom);
    return self::doSelectOne($c);
  }

  public static function retrievesByMemberIdTo($memberId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberIdTo);
    return self::doSelect($c);
  }

  public static function isFriendPre($memberIdTo, $memberIdFrom)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberIdTo);
    $c->add(self::MEMBER_ID_FROM, $memberIdFrom);
    return (bool)self::doSelectOne($c);
  }
}
