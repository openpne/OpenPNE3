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

    return $result;
  }

  public static function retrieveByNameAndValue($name, $value)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);
    $c->add(self::VALUE, $value);
    return self::doSelectOne($c);
  }

  public static function retrievesByName($name)
  {
    $c = new Criteria();
    $c->add(self::NAME, $name);
    return self::doSelect($c);
  }

  public static function deleteDuplicatedPre($memberId, $name, $value)
  {
    $memberConfig = self::retrieveByNameAndMemberId($name.'_pre', $memberId);
    if ($memberConfig) {
      $memberConfig->delete();
    }

    $memberConfigSettings = sfConfig::get('openpne_member_config');
    if ($memberConfigSettings[$name]['IsUnique']) {
      $memberConfigs = self::retrievesByName($name.'_pre');
      foreach ($memberConfigs as $config) {
        if ($value === $config->getValue()) {
          if (!$config->getMember()->getIsActive()) {
            $config->getMember()->delete();
          }
          $config->delete();
        }
      }
    }
  }
}
