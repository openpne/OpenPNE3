<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberRelationshipTable extends opAccessControlDoctrineTable
{
  public function retrieveByFromAndTo($memberIdFrom, $memberIdTo)
  {
    static $queryCacheHash;

    $q = $this->createQuery()
      ->where('member_id_from = ?', $memberIdFrom)
      ->andWhere('member_id_to = ?', $memberIdTo);

    if (!$queryCacheHash)
    {
      $result = $q->fetchOne();

      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);

      $result = $q->fetchOne();
    }

    return $result;
  }

  public function retrievesByMemberIdFrom($memberId)
  {
    return $this->createQuery()
      ->where('member_id_from = ?', $memberId)
      ->execute();
  }

  public function retrievesAccessBlockByMemberIdFrom($memberId)
  {
    return $this->createQuery()
      ->where('member_id_from = ?', $memberId)
      ->andWhere('is_access_block = ?', true)
      ->execute(array(), Doctrine::HYDRATE_ARRAY);
  }

  public function getBlockedMemberIdsByTo($memberId)
  {
    $result = array();

    $blockedIds = $this->createQuery()
      ->select('member_id_from')
      ->where('member_id_to = ?', $memberId)
      ->andWhere('is_access_block = ?', true)
      ->execute(array(), Doctrine::HYDRATE_NONE);

    $inactiveMemberIds = Doctrine::getTable('Member')->getInactiveMemberIds();

    foreach ($blockedIds as $blocked)
    {
      if (!isset($inactiveMemberIds[$blocked[0]]))
      {
        $result[$blocked[0]] = $blocked[0];
      }
    }

    return $result;
  }

  public function getFriendListPager($memberId, $page = 1, $size = 20)
  {
    $friendMemberIds = $this->getFriendMemberIds($memberId);

    if (!count($friendMemberIds))
    {
      return null;
    }

    $q = Doctrine::getTable('Member')->createQuery()
      ->whereIn('id', $friendMemberIds);

    $pager = new opNonCountQueryPager('Member', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getFriendMemberIds($memberId)
  {
    static $queryCacheHash;

    $result = array();

     $q = $this->createQuery()
       ->select('member_id_to')
       ->where('member_id_from = ?', $memberId)
       ->andWhere('is_friend = ?', true);

    if (!$queryCacheHash)
    {
      $friendMemberIds = $q->execute(array(), Doctrine::HYDRATE_NONE);
      $queryCacheHash = $q->calculateQueryCacheHash();
    }
    else
    {
      $q->setCachedQueryCacheHash($queryCacheHash);
      $friendMemberIds = $q->execute(array(), Doctrine::HYDRATE_NONE);
    }

    $inactiveMemberIds = Doctrine::getTable('Member')->getInactiveMemberIds();

    foreach ($friendMemberIds as $friend)
    {
      if (!isset($inactiveMemberIds[$friend[0]]))
      {
        $result[] = $friend[0];
      }
    }

    return $result;
  }

  public function getFriends($memberId, $limit = null, $isRandom = false)
  {
    $collection = Doctrine_Collection::create('Member');
    $friendIds = $this->getFriendMemberIds($memberId);

    if ($isRandom)
    {
      shuffle($friendIds);
    }

    $limitedFriendIds = is_null($limit) ? $friendIds : array_slice($friendIds, 0, $limit);

    foreach ($limitedFriendIds as $friendId)
    {
      $collection[] = Doctrine::getTable('Member')->find($friendId);
    }

    return $collection;
  }

  public static function friendConfirmList(sfEvent $event)
  {
    $list = array();
    foreach ($event['member']->getFriendPreTo() as $k => $v)
    {
      $from = $v->getMemberRelatedByMemberIdFrom();
      $list[] = array(
        'id' => $from->id,
        'image' => array(
          'url'  => $from->getImageFileName(),
          'link' => '@member_profile?id='.$from->id,
        ),
        'list' => array(
          '%nickname%' => array(
            'text' => $from->name,
            'link' => '@member_profile?id='.$from->id,
          ),
        ),
      );
    }

    $event->setReturnValue($list);

    return true;
  }

  public static function processFriendConfirm(sfEvent $event)
  {
    $toMember = Doctrine::getTable('Member')->find($event['id']);
    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($event['member']->id, $toMember->id);
    if (!$relation)
    {
      $relation = Doctrine::getTable('MemberRelationship')->create(array('member_id_from' => $event['member']->id, 'member_id_to' => $toMember->id));
    }

    if (!$relation->isFriendPreTo())
    {
      return false;
    }

    if ($event['is_accepted'])
    {
      $relation->setFriend();
      $event->setReturnValue('You have just accepted %friend% link request.');

      $params = array(
        'subject' => sfContext::getInstance()->getI18N()->__('%1% accepted your %friend% link request', array('%1%' => $event['member']->getName())),
        'member'  => $event['member'],
      );
      opMailSend::sendTemplateMail('friendLinkComplete', $toMember->getEmailAddress(), opConfig::get('admin_mail_address'), $params);
    }
    else
    {
      $event->setReturnValue('You have just rejected %friend% link request.');
      $relation->removeFriendPre();
    }

    return true;
  }

  public function appendRoles(Zend_Acl $acl)
  {
    return $acl
      ->addRole(new Zend_Acl_Role('anonymous'))
      ->addRole(new Zend_Acl_Role('everyone'), 'anonymous');
  }

  public function appendRules(Zend_Acl $acl, $resource = null)
  {
    $acl->allow('everyone', $resource, 'friend_link');

    return $acl;
  }
}
