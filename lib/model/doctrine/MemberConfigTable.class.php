<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberConfigTable extends Doctrine_Table
{
  public $results;

  public function retrieveByNameAndMemberId($name, $memberId, $force = false)
  {
    $results = $this->getResultsByMemberId($memberId, $force);
    return (isset($results[$name])) ? $results[$name] : null;
  }

  public function retrieveByNameAndValue($name, $value)
  {
    return $this->createQuery()
      ->where('name = ?', $name)
      ->andWhere('value = ?', $value)
      ->fetchOne();
  }

  public function retrievesByName($name)
  {
    return $this->createQuery()
      ->where('name = ?', $name)
      ->execute();
  }

  public function deleteDuplicatedPre($memberId, $name, $value)
  {
    $memberConfig = $this->retrieveByNameAndMemberId($name.'_pre', $memberId);
    if ($memberConfig) {
      $memberConfig->delete();
    }

    $memberConfigSettings = sfConfig::get('openpne_member_config');
    if ($memberConfigSettings[$name]['IsUnique']) {
      $memberConfigs = $this->retrievesByName($name.'_pre');
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

  protected function getResultsByMemberId($memberId, $force = false)
  {
    if (!isset($this->results[$memberId]) || $force)
    {
      $objects = $this->createQuery()
        ->where('member_id = ?', $memberId)
        ->execute();

      $this->results[$memberId] = array();
      foreach ($objects as $object)
      {
        $this->results[$memberId][$object->getName()] = $object;
      }
    }

    return $this->results[$memberId];
  }

}
