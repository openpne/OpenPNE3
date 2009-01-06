<?php

/**
 * Subclass for performing query and update operations on the 'member' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberPeer extends BaseMemberPeer
{
  public static function createPre()
  {
    $member = new Member();
    $member->setIsActive(false);
    $member->save();

    return $member;
  }
}
