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
      ->select('community_id')
      ->where('is_pre = ?', false)
      ->andWhere('member_id = ?', $memberId)
      ->execute(array(), Doctrine::HYDRATE_NONE);

    $ids = array();
    foreach ($communityMembers as $communityMember)
    {
      $ids[] = $communityMember[0];
    }

    if (empty($ids))
    {
      return;
    }

    $q = $this->createQuery()->whereIn('id', $ids);

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
      ->select('community_id')
      ->where('member_id = ?', $memberId)
      ->andWhere('is_pre = ?', false)
      ->execute(array(), Doctrine::HYDRATE_NONE);

    $ids = array();
    foreach ($communityMembers as $communityMember)
    {
      $ids[] = $communityMember[0];
    }

    $pager = new sfDoctrinePager('Community', $size);

    if (empty($ids))
    {
      return $pager;
    }

    $q = $this->createQuery()
      ->whereIn('id', $ids);
 
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getCommunityMemberListPager($communityId, $page = 1, $size = 20)
  {
    $communityMembers = Doctrine::getTable('CommunityMember')->createQuery()
      ->select('member_id')
      ->where('community_id = ?', $communityId)
      ->andWhere('is_pre = ?', false)
      ->execute(array(), Doctrine::HYDRATE_NONE);

    $ids = array();
    foreach ($communityMembers as $communityMember)
    {
      $ids[] = $communityMember[0];
    }

    $pager = new opNonCountQueryPager('Member', $size);

    if (empty($ids))
    {
      return $pager;
    }

    $q = Doctrine::getTable('Member')->createQuery()
      ->whereIn('id', $ids);

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
      ->andWhere('is_pre = ?', false)
      ->execute(array(), Doctrine::HYDRATE_NONE);

    foreach ($resultSet as $value)
    {
      $result[] = $value[0];
    }

    return $result;
  }

  public function getDefaultCommunities()
  {
    $communityConfigs = Doctrine::getTable('CommunityConfig')->createQuery()
      ->select('community_id')
      ->where('name = ?', 'is_default')
      ->andWhere('value = ?', true)
      ->execute(array(), Doctrine::HYDRATE_NONE);

    $ids = array();
    foreach ($communityConfigs as $communityConfig)
    {
      $ids[] = $communityConfig[0];
    }
    if (empty($ids))
    {
      return null;
    }

    return $this->createQuery()
      ->whereIn('id', $ids)
      ->execute();
  }

  public function getPositionRequestCommunitiesQuery($position = 'admin', $memberId = null)
  {
    if (null === $memberId)
    {
      $memberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $communityMemberPositions = Doctrine::getTable('CommunityMemberPosition')->findByMemberIdAndName($memberId,  $position.'_confirm');

    if (!$communityMemberPositions || !count($communityMemberPositions))
    {
      return null;
    }

    return $this->createQuery()
      ->whereIn('id', array_values($communityMemberPositions->toKeyValueArray('id', 'community_id')));
  }

  public function getPositionRequestCommunities($position = 'admin', $memberId = null)
  {
    $q = $this->getPositionRequestCommunitiesQuery($position, $memberId);
    return $q ? $q->execute() : null;
  }

  public function countPositionRequestCommunities($position = 'admin', $memberId = null)
  {
    $q = $this->getPositionRequestCommunitiesQuery($position, $memberId);
    return $q ? $q->count() : null;
  }

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('everyone'))
      ->addRole(new Zend_Acl_Role('member'), 'everyone')
      ->addRole(new Zend_Acl_Role('sub_admin'), 'member')
      ->addRole(new Zend_Acl_Role('admin'), 'member');
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    return $acl
      ->allow('everyone', $resource, 'view')
      ->allow('sub_admin', $resource, 'edit')
      ->allow('admin', $resource, 'edit');
  }

  protected static function confirmList(sfEvent $event, $position = 'admin')
  {
    $communities = Doctrine::getTable('Community')->getPositionRequestCommunities($position, $event['member']->id);

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

  public static function adminConfirmList(sfEvent $event)
  {
    return self::confirmList($event, 'admin');
  }

  public static function subAdminConfirmList(sfEvent $event)
  {
    return self::confirmList($event, 'sub_admin');
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
      $communityMemberPosition->getCommunityMember()->removePosition('admin_confirm');
      $event->setReturnValue('You have just rejected taking over %community%');
    }

    return true;
  }

  public static function processSubAdminConfirm(sfEvent $event)
  {
    $communityMemberPosition = Doctrine::getTable('CommunityMemberPosition')
      ->findOneByMemberIdAndCommunityIdAndName($event['member']->id, $event['id'], 'sub_admin_confirm');
    if (!$communityMemberPosition)
    {
      return false;
    }

    if ($event['is_accepted'])
    {
      Doctrine::getTable('CommunityMember')->addSubAdmin($event['member']->id, $event['id']);
      $event->setReturnValue('You have just accepted request of %community% sub-administrator');
    }
    else
    {
      $communityMemberPosition->getCommunityMember()->removePosition('sub_admin_confirm');
      $event->setReturnValue("You have just rejected request of %community% sub-administrator");
    }

    return true;
  }
}
