<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberTable extends opAccessControlDoctrineTable
{
  public function createPre()
  {
    $member = new Member();
    $member->setIsActive(false);
    $member->save();

    return $member;
  }

  public function searchMemberIds($nickname, $ids = null)
  {
    if (!empty($nickname))
    {
      $_result = $this->createQuery()
        ->select('id')
        ->where('name LIKE ?', '%'.$nickname.'%')
        ->execute();
      $result = array();

      foreach ($_result as $value)
      {
        $result[] = $value->getId();
      }

      if (is_array($ids))
      {
        $ids = array_values(array_intersect($ids, $result));
      }
      else
      {
        $ids = array_values($result);
      }
    }

    return $ids;
  }
 
  public function getInactiveMemberIds()
  {
    static $queryCacheHash;

    $e = opActivateBehavior::getEnabled();
    opActivateBehavior::disable();

    $q = $this->createQuery()
      ->select('id')
      ->andWhere('is_active = ?', false);

    if (!$queryCacheHash)
    {
      $members = $q->execute(array(), Doctrine::HYDRATE_NONE);
      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $members = $q->execute(array(), Doctrine::HYDRATE_NONE);
    }

    if ($e)
    {
      opActivateBehavior::enable();
    }

    $memberIds = array();
    foreach ($members as $member)
    {
      $memberIds[$member[0]] = $member[0];
    }

    return $memberIds;
  }

  public function retrivesByInviteMemberId($memberId)
  {
    $e = opActivateBehavior::getEnabled();
    opActivateBehavior::disable();

    $members = $this->createQuery()
      ->where('invite_member_id = ?', $memberId)
      ->andWhere('is_active = ?', false)
      ->execute();

    if ($e)
    {
      opActivateBehavior::enable();
    }

    return $members;
  }

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('anonymous'))
      ->addRole(new Zend_Acl_Role('everyone'), 'anonymous')
      ->addRole(new Zend_Acl_Role('self'), 'everyone')
      ->addRole(new Zend_Acl_Role('blocked'));
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    $acl->allow('everyone', $resource, 'view')
      ->allow('self', $resource, 'edit')
      ->deny('blocked');

    if (Doctrine::getTable('SnsConfig')->get('is_allow_config_public_flag_profile_page'))
    {
      $config = Doctrine::getTable('SnsConfig')->get('is_allow_config_public_flag_profile_page');
    }
    elseif ($resource)
    {
      $config = $resource->getConfig('profile_page_public_flag');
    }

    if ($config && 4 == $config)
    {
      $acl->allow('anonymous', $resource, 'view');
    }

    return $acl;
  }

  public function findInactive($id)
  {
    return $this->createQuery('m')
      ->where('m.id = ?', $id)
      ->andWhere('m.is_active = ?', false)
      ->fetchOne();
  }

  public function findByRegisterToken($token)
  {
    $config = Doctrine::getTable('MemberConfig')
      ->retrieveByNameAndValue('register_token', $token);
    if (!$config)
    {
      return false;
    }

    return $this->findInactive($config->getMemberId());
  }
}
