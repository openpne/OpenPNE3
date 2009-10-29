<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityTable extends opAccessControlDoctrineTable
{
  public function retrievesByMemberId($memberId, $limit = 5, $isRandom = false)
  {
    $communityMembers = Doctrine::getTable('CommunityMember')->createQuery()
      ->where('position <> ?', 'pre')
      ->andWhere('member_id = ?', $memberId)
      ->execute();

    if (0 === $communityMembers->count())
    {
      return;
    }

    $q = $this->createQuery()->whereIn('id', array_values($communityMembers->toKeyValueArray('id', 'community_id')));

    if (!is_null($limit))
    {
      $q->limit($limit);
    }

    if ($isRandom)
    {
      $expr = new Doctrine_Expression('RANDOM()');
      $q->orderBy($expr);
    }

    return $q->execute();
  }

  public function getJoinCommunityListPager($memberId, $page = 1, $size = 20)
  {
    $communityMembers = Doctrine::getTable('CommunityMember')->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('position <> ?', 'pre')
      ->execute();

    $pager = new sfDoctrinePager('Community', $size);

    if (0 === $communityMembers->count())
    {
      return $pager;
    }

    $q = $this->createQuery()
      ->whereIn('id', array_values($communityMembers->toKeyValueArray('id', 'community_id')));
 
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getCommunityMemberListPager($communityId, $page = 1, $size = 20)
  {
    $communityMembers = Doctrine::getTable('CommunityMember')->createQuery()
      ->where('community_id = ?', $communityId)
      ->andWhere('position <> ?', 'pre')
      ->execute();

    $pager = new sfDoctrinePager('Member', $size);

    if (0 === $communityMembers->count())
    {
      return $pager;
    }

    $q = Doctrine::getTable('Member')->createQuery()
      ->whereIn('id', array_values($communityMembers->toKeyValueArray('id', 'member_id')));

    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getIdsByMemberId($memberId)
  {
    $result = array();

    $resultSet = Doctrine::getTable('CommunityMember')->createQuery()
      ->select('community_id')
      ->where('member_id = ?', $memberId)
      ->andWhere('position <> ?', 'pre')
      ->execute();

    foreach ($resultSet as $value)
    {
      $result[] = $value->getCommunityId();
    }

    return $result;
  }

  public function getDefaultCommunities()
  {
    $communityConfigs = Doctrine::getTable('CommunityConfig')->createQuery()
      ->where('name = ?', 'is_default')
      ->andWhere('value = ?', true)
      ->execute();

    return $this->createQuery()
      ->whereIn('id', array_values($communityConfigs->toKeyValueArray('id', 'community_id')));
  }

  public function getChangeAdminRequestCommunities($memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $communityIds = Doctrine::getTable('CommunityMember')->createQuery()
      ->select('community_id')
      ->where('member_id = ?', $memberId)
      ->andWhere('position = ?', 'admin_confirm')
      ->execute(array(), Doctrine::HYDRATE_NONE);

    if (!$communityIds)
    {
      return null;
    }

    foreach ($communityIds as &$communityId)
    {
      $communityId = $communityId[0];
    }

    return $this->createQuery()
      ->whereIn('id', $communityIds)
      ->execute();
  }

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('everyone'))
      ->addRole(new Zend_Acl_Role('member'), 'everyone')
      ->addRole(new Zend_Acl_Role('admin'), 'member');
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    return $acl
      ->allow('everyone', $resource, 'view')
      ->allow('admin', $resource, 'edit');
  }
}
