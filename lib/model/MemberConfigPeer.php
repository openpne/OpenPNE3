<?php

/**
 * Subclass for performing query and update operations on the 'member_config' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberConfigPeer extends BaseMemberConfigPeer
{
  public static function retrieveByNameAndMemberId($name, $memberId)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);
    $c->add(self::MEMBER_ID, $memberId);

    $result = self::doSelectOne($c);
    if (!$result) {
      $result = new MemberConfig();
      $result->setName($name);
      $result->setMemberId($memberId);
    }

    return $result;
  }

  public static function retrieveByNameAndValue($name, $value)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);
    $c->add(self::VALUE, $value);
    return self::doSelectOne($c);
  }
}
