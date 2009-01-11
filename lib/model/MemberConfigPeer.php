<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'member_config' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberConfigPeer extends BaseMemberConfigPeer
{
  static protected $results;

  public static function retrieveByNameAndMemberId($name, $memberId)
  {
    $results = self::getResultsByMemberId($memberId);

    return (isset($results[$name])) ? $results[$name] : null;
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

  static protected function getResultsByMemberId($memberId)
  {
    if (is_null(self::$results[$memberId]))
    {
      $criteria = new Criteria();
      $criteria->add(self::MEMBER_ID, $memberId);

      self::$results[$memberId] = array();
      foreach (self::doSelect($criteria) as $object)
      {
        self::$results[$memberId][$object->getName()] = $object;
      }
    }

    return self::$results[$memberId];
  }
}
