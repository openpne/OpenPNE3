<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityMemberTable extends opAccessControlDoctrineTable
{
  public function retrieveByMemberIdAndCommunityId($memberId, $communityId)
  {
    return $this->createQuery()
        ->where('member_id = ?', $memberId)
        ->andWhere('community_id = ?', $communityId)
        ->fetchOne();
  }

  protected function isPosition($memberId, $communityId, $position)
  {
    $object = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if ($object)
    {
      return $object->hasPosition($position);
    }
    return false;
  }

  public function isMember($memberId, $communityId)
  {
    if ($this->retrieveByMemberIdAndCommunityId($memberId, $communityId))
    {
      return !$this->isPreMember($memberId, $communityId);
    }
    return false;
  }

  public function isPreMember($memberId, $communityId)
  {
    $object = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if ($object && $object->getIsPre())
    {
      return true;
    }
    return false;
  }

  public function isAdmin($memberId, $communityId)
  {
    return $this->isPosition($memberId, $communityId, 'admin');
  }

  public function isSubAdmin($memberId, $communityId)
  {
    return $this->isPosition($memberId, $communityId, 'sub_admin');
  }

  public function join($memberId, $communityId, $isRegisterPolicy = 'open')
  {
    if ($this->isPreMember($memberId, $communityId))
    {
      throw new Exception('This member has already applied this community.');
    }

    if ($this->isMember($memberId, $communityId))
    {
      throw new Exception('This member has already joined this community.');
    }

    $communityMember = new CommunityMember();
    $communityMember->setMemberId($memberId);
    $communityMember->setCommunityId($communityId);
    if ($isRegisterPolicy == 'close')
    {
      $communityMember->setIsPre(true);
    }
    $communityMember->save();
  }

  public function quit($memberId, $communityId)
  {
    if (!$this->isMember($memberId, $communityId)) {
      throw new Exception('This member is not a member of this community.');
    }

    if ($this->isAdmin($memberId, $communityId)) {
      throw new Exception('This member is community admin.');
    }

    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    $communityMember->delete();
  }

  public function getCommunityAdmin($communityId)
  {
    return Doctrine::getTable('CommunityMemberPosition')->findOneByCommunityIdAndName($communityId, 'admin');
  }

  public function getCommunitySubAdmin($communityId)
  {
    return Doctrine::getTable('CommunityMemberPosition')->findByCommunityIdAndName($communityId, 'sub_admin');
  }

  public function getCommunityIdsOfAdminByMemberId($memberId)
  {
    $objects = Doctrine::getTable('CommunityMemberPosition')->findByMemberIdAndName($memberId, 'admin');

    $results = array();
    foreach ($objects as $obj)
    {
      $results[] = $obj->getCommunityId();
    }
    return $results;
  }

  public function getCommunityMembersPreQuery($memberId)
  {
    $adminCommunityIds = $this->getCommunityIdsOfAdminByMemberId($memberId);

    if (count($adminCommunityIds))
    {
      return Doctrine::getTable('CommunityMember')->createQuery()
        ->whereIn('community_id', $adminCommunityIds)
        ->andWhere('is_pre = ?', true);
    }

    return false;
  }

  public function getCommunityMembersPre($memberId)
  {
    $q = $this->getCommunityMembersPreQuery($memberId);

    if (!$q)
    {
      return array();
    }

    return $q->execute();
  }

  public function countCommunityMembersPre($memberId)
  {
    $q = $this->getCommunityMembersPreQuery($memberId);
    if (!$q)
    {
      return 0;
    }

    return $q->count();
  }

  public function getCommunityMembers($communityId)
  {
    $subqueryResults = Doctrine::getTable('CommunityMemberPosition')->createQuery()
      ->where('community_id = ?', $communityId)
      ->andWhere('name = ?','admin')
      ->execute();

    $ids = array();
    foreach ($subqueryResults as $result)
    {
      $ids[] = $result->getCommunityMemberId();
    }

    return $this->createQuery()
      ->where('community_id = ?', $communityId)
      ->andWhere('is_pre = ?', false)
      ->andWhereNotIn('id', $ids)
      ->execute();
  }

  protected function requestChangePosition($memberId, $communityId, $fromMemberId = null, $position = 'admin')
  {
    if (null === $fromMemberId)
    {
      $fromMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    if (!$this->isAdmin($fromMemberId, $communityId))
    {
      throw new Exception("Requester isn't community's admin.");
    }

    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      throw new Exception("Invalid community member.");
    }

    if ($communityMember->getIsPre())
    {
      throw new Exception("This member is pre-member.");
    }

    $dennyPositions = array('admin', 'admin_confirm');
    if ('admin' !== $position)
    {
      $dennyPositions[] = $position;
      $dennyPositions[] = $position.'_confirm';
    }
    if ($communityMember->hasPosition($dennyPositions))
    {
      throw new Exception("This member is already position of something.");
    }

    $nowRequestMember = Doctrine::getTable('CommunityMemberPosition')->findOneByCommunityIdAndName($communityId, $position.'_confirm');
    if ($nowRequestMember)
    {
      $nowRequestMember->delete();
    }

    $communityMember->addPosition($position.'_confirm');
  }

  public function requestAddPosition($memberId, $communityId, $fromMemberId = null, $position = 'sub_admin')
  {
    if (null === $fromMemberId)
    {
      $fromMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    if (!$this->isAdmin($fromMemberId, $communityId))
    {
      throw new Exception("Requester isn't community's admin.");
    }

    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      throw new Exception("Invalid community member.");
    }

    if ($communityMember->getIsPre())
    {
      throw new Exception("This member is pre-member.");
    }

    $dennyPositions = array('admin', 'admin_confirm');
    $dennyPositions[] = $position;
    $dennyPositions[] = $position.'_confirm';
    if ($communityMember->hasPosition($dennyPositions))
    {
      throw new Exception("This member is already position of something.");
    }

    $communityMember->addPosition($position.'_confirm');
    $communityMember->save();
  }

  public function requestChangeAdmin($memberId, $communityId, $fromMemberId = null)
  {
    $this->requestChangePosition($memberId, $communityId, $fromMemberId, 'admin');
  }

  public function requestSubAdmin($memberId, $communityId, $fromMemberId = null)
  {
    $this->requestAddPosition($memberId, $communityId, $fromMemberId, 'sub_admin');
  }

  protected function addPosition($memberId, $communityId, $position = 'sub_admin')
  {
    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      throw new Exception("Invalid community member.");
    }
    if (!$communityMember->hasPosition($position.'_confirm'))
    {
      throw new Exception('This member position isn\'t "'.$position.'_confirm".');
    }

    try
    {
      $this->getConnection()->beginTransaction();

      $communityMember->removePosition($position.'_confirm');
      $communityMember->addPosition($position);

      $this->getConnection()->commit();
    }
    catch (Exception $e)
    {
      $this->getConnection()->rollback();
      throw $e;
    }
  }

  public function changeAdmin($memberId, $communityId)
  {
    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      throw new Exception("Invalid community member.");
    }
    if (!$communityMember->hasPosition('admin_confirm'))
    {
      throw new Exception('This member position isn\'t "admin_confirm".');
    }

    $nowAdmin = $this->getCommunityAdmin($communityId);
    if (!$nowAdmin)
    {
      throw new Exception("Community's admin was not found.");
    }

    try
    {
      $this->getConnection()->beginTransaction();

      $communityMember->removeAllPosition();
      $communityMember->addPosition('admin');
      $nowAdmin->delete();

      $this->getConnection()->commit();
    }
    catch(Exception $e)
    {
      $this->getConnection()->rollback();
      throw $e;
    }
  }

  public function addSubAdmin($memberId, $communityId)
  {
    $this->addPosition($memberId, $communityId, 'sub_admin');
  }

  public function getMemberIdsByCommunityId($communityId)
  {
    return Doctrine::getTable('CommunityMember')->createQuery()
      ->select('id', 'member_id')
      ->where('community_id = ?', $communityId)
      ->execute()
      ->toKeyValueArray('id', 'member_id');
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
      ->allow('sub_admin', $resource, 'view')
      ->allow('sub_admin', $resource, 'edit')
      ->allow('admin', $resource, 'view')
      ->allow('admin', $resource, 'edit');
  }

  public static function joinConfirmList(sfEvent $event)
  {
    $list = array();
    $members = Doctrine::getTable('CommunityMember')->getCommunityMembersPre($event['member']->id);
    foreach ($members as $member)
    {
      $list[] = array(
        'id' => $member->id,
        'image' => array(
          'url' => $member->getMember()->getImageFileName(),
          'link' => '@member_profile?id='.$member->getMember()->id,
        ),
        'list' => array(
          '%nickname%' => array(
            'text' => $member->getMember()->name,
            'link' => '@member_profile?id='.$member->getMember()->id,
          ),
          '%community%' => array(
            'text' => $member->getCommunity()->name,
            'link' => '@community_home?id='.$member->getCommunity()->id,
          ),
        ),
      );
    }

    $event->setReturnValue($list);

    return true;
  }

  public static function processJoinConfirm(sfEvent $event)
  {
    $communityMember = Doctrine::getTable('CommunityMember')->find($event['id']);
    if (!($communityMember && $communityMember->getIsPre()))
    {
      return false;
    }

    $i18n = sfContext::getInstance()->getI18N();
    if ($event['is_accepted'])
    {
      $communityMember->setIsPre(false);
      $communityMember->save();

      opCommunityAction::sendJoinMail($communityMember->getMember()->id, $communityMember->getCommunity()->id);

      $event->setReturnValue($i18n->__('You have just accepted joining to %1%', array('%1%' => $communityMember->getCommunity()->getName())));
    }
    else
    {
      $communityMember->delete();

      $event->setReturnValue($i18n->__('You have just rejected joining to %1%', array('%1%' => $communityMember->getCommunity()->getName())));
    }

    return true;
  }
}
