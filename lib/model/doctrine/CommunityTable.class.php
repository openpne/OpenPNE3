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
      ->where(('is_pre = ? OR is_pre IS NULL'), false)
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
      ->andWhere(('is_pre = ? OR is_pre IS NULL'), false)
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
      ->andWhere(('is_pre = ? OR is_pre IS NULL'), false)
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
      ->andWhere(('is_pre = ? OR is_pre IS NULL'), false)
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

    if (!$communityConfigs || !count($communityConfigs))
    {
      return null;
    }

    return $this->createQuery()
      ->whereIn('id', array_values($communityConfigs->toKeyValueArray('id', 'community_id')))
      ->execute();
  }

  public function getChangeAdminRequestCommunitiesQuery($memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $communityMemberPositions = Doctrine::getTable('CommunityMemberPosition')->findByMemberIdAndName($memberId, 'admin_confirm');

    if (!$communityMemberPositions || !count($communityMemberPositions))
    {
      return null;
    }

    return $this->createQuery()
      ->whereIn('id', array_values($communityMemberPositions->toKeyValueArray('id', 'community_id')));
  }

  public function getChangeAdminRequestCommunities($memberId = null)
  {
    $q = $this->getChangeAdminRequestCommunitiesQuery($memberId);
    return $q ? $q->execute() : null;
  }

  public function countChangeAdminRequestCommunities($memberId = null)
  {
    $q = $this->getChangeAdminRequestCommunitiesQuery($memberId);
    return $q ? $q->count() : null;
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

  public static function adminConfirmList(sfEvent $event)
  {
    $communities = Doctrine::getTable('Community')->getChangeAdminRequestCommunities($event['member']->id);

    if (!$communities)
    {
      return false;
    }

    $list = array();
    foreach ($communities as $community)
    {
      $list[] = array(
        'id' => $community->id,
        'image' => array(
          'url' => $community->getAdminMember()->getImageFileName(),
          'link' => '@member_profile?id='.$community->getAdminMember()->id,
        ),
        'list' => array(
          '%nickname%' => array(
            'text' => $community->getAdminMember()->name,
            'link' => '@member_profile?id='.$community->getAdminMember()->id,
          ),
          '%community%' => array(
            'text' => $community->name,
            'link' => '@community_home?id='.$community->id,
          ),
        ),
      );
    }

    $event->setReturnValue($list);

    return true;
  }

  public static function processAdminConfirm(sfEvent $event)
  {
    $communityMemberPosition = Doctrine::getTable('CommunityMemberPosition')
      ->findOneByMemberIdAndCommunityIdAndName($event['member']->id, $event['id'], 'admin_confirm');
    if (!$communityMemberPosition)
    {
      return false;
    }

    if ($event['is_accepted'])
    {
      Doctrine::getTable('CommunityMember')->changeAdmin($event['member']->id, $event['id']);
      $event->setReturnValue('You have just accepted taking over %community%');
    }
    else
    {
      $communityMemberPosition->delete();
      $event->setReturnValue('You have just rejected taking over %community%');
    }

    return true;
  }
}
