<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberConfigTable extends opAccessControlDoctrineTable
{
  public $results;

  public function generateNameValueHash($name, $value)
  {
    return md5($name.','.$value);
  }

  public function retrieveByNameAndMemberId($name, $memberId, $force = false)
  {
    $results = $this->getResultsByMemberId($memberId, $force);
    return (isset($results[$name])) ? $results[$name] : null;
  }

  public function retrieveByNameAndValue($name, $value)
  {
    return $this->createQuery()
      ->where('name_value_hash = ?', $this->generateNameValueHash($name, $value))
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
    static $queryCacheHash;

    if (!isset($this->results[$memberId]) || $force)
    {
      $q = $this->createQuery()
        ->where('member_id = ?', $memberId);

      if (!$queryCacheHash)
      {
        $objects = $q->execute();

        $queryCacheHash = $q->calculateQueryCacheHash();
      }
      else
      {
        $q->setCachedQueryCacheHash($queryCacheHash);

        $objects = $q->execute();
      }

      $this->results[$memberId] = array();
      foreach ($objects as $object)
      {
        $this->results[$memberId][$object->getName()] = $object;
      }
    }

    return $this->results[$memberId];
  }

  public function setValue($memberId, $name, $value, $isDateTime = false)
  {
    $config = $this->retrieveByNameAndMemberId($name, $memberId);
    if (!$config)
    {
      $config = new memberConfig();
      $config->setMemberId($memberId);
      $config->setName($name);
    }
    if ($isDateTime)
    {
      $config->setValueDatetime($value);
    }
    $config->setValue($value);
    $config->save();
    $this->results[$memberId][$name] = $config;
  }

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('everyone'))
      ->addRole(new Zend_Acl_Role('self'), 'everyone');
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    return $acl
      ->allow('self', $resource, 'view')
      ->allow('self', $resource, 'edit')
      ->deny('everyone');
  }
}
