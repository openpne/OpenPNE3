<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Member extends BaseMember implements opAccessControlRecordInterface
{
  public function getProfiles($viewableCheck = false, $myMemberId = null)
  {
    if ($viewableCheck)
    {
      return Doctrine::getTable('MemberProfile')->getViewableProfileListByMemberId($this->getId(), $myMemberId);
    }

    return Doctrine::getTable('MemberProfile')->getProfileListByMemberId($this->getId());
  }

  public function getProfile($profileName)
  {
    $profile = Doctrine::getTable('MemberProfile')->retrieveByMemberIdAndProfileName($this->getId(), $profileName);

    return $profile;
  }

  public function getConfig($configName)
  {
    $config = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($configName, $this->getId());

    if (!$config)
    {
      return null;
    }

    return $config->getValue();
  }

  public function setConfig($configName, $value, $isDateTime = false)
  {
    Doctrine::getTable('MemberConfig')->setValue($this->getId(), $configName, $value, $isDateTime);
  }

  public function getFriends($limit = null, $isRandom = false)
  {
    $subQuery = Doctrine::getTable('MemberRelationship')->createQuery()
        ->select('mr.member_id_to')
        ->from('MemberRelationship mr')
        ->where('member_id_from = ?')
        ->andWhere('is_friend = ?');

    $q = Doctrine::getTable('Member')->createQuery()
        ->where('id IN ('.$subQuery->getDql().')', array($this->getId(), true));

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

  public function countFriends()
  {
    return $this->getFriends()->count();
  }

  public function getNameAndCount($format = '%s (%d)')
  {
    if (!opConfig::get('enable_friend_link'))
    {
      return $this->getName();
    }
    return sprintf($format, $this->getName(), $this->countFriends());
  }
  
  public function getJoinCommunities($limit = null, $isRandom = false)
  {
    return Doctrine::getTable('Community')->retrievesByMemberId($this->getId(), $limit, $isRandom);
  }

  public function getFriendPreTo(Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = Doctrine::getTable('MemberRelationship')->createQuery();
    }
    $q->where('member_id_to = ?', $this->getId());
    $q->addWhere('is_friend_pre = ?', true);

    return $q->execute();
  }

  public function countFriendPreTo(Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = Doctrine::getTable('MemberRelationship')->createQuery();
    }
    $q->where('member_id_to = ?', $this->getId());
    $q->addWhere('is_friend_pre = ?', true);

    return $q->count();
  }

  public function getFriendPreFrom(Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = Doctrine::getTable('MemberRelationship')->createQuery();
    }
    $q->where('member_id_from = ?', $this->getId());
    $q->addWhere('is_friend_pre = ?', true);

    return $q->execute();
  }

  public function countFriendPreFrom(Doctrine_Query $q = null)
  {
    if (!$q)
    {
      $q = Doctrine::getTable('MemberRelationship')->createQuery();
    }
    $q->where('member_id_from = ?', $this->getId());
    $q->addWhere('is_friend_pre = ?', true);

    return $q->count();
  }

  public function getImage()
  {
    return Doctrine::getTable('MemberImage')->createQuery()
      ->where('member_id = ?', $this->getId())
      ->orderBy('is_primary DESC')
      ->fetchOne();
  }

  public function getImageFileName()
  {
    if ($this->getImage())
    {
      return $this->getImage()->getFile();
    }

    return false;
  }

  public function updateLastLoginTime()
  {
    $this->setConfig('lastLogin', date('Y-m-d H:i:s'), true);
  }

  public function getLastLoginTime()
  {
    return strtotime($this->getConfig('lastLogin'));
  }

  public function isOnBlackList()
  {
    $uid = $this->getConfig('mobile_uid');
    if ($uid)
    {
      return (bool)Doctrine::getTable('Blacklist')->retrieveByUid($uid);
    }

    return false;
  }

  public function getInvitingMembers()
  {
    return Doctrine::getTable('Member')->retrivesByInviteMemberId($this->getId());
  }

  public function getInviteMember()
  {
    return Doctrine::getTable('Member')->find($this->getInviteMemberId());
  }

  public function getEmailAddress($isPriorityMobile = null)
  {
    if (is_null($isPriorityMobile))
    {
      $isPriorityMobile = false;
      if (sfConfig::get('sf_app') == 'mobile_frontend')
      {
        $isPriorityMobile = true;
      }
    }

    $memberPcAddress     = $this->getConfig('pc_address');
    $memberMobileAddress = $this->getConfig('mobile_address');

    if ($memberMobileAddress && ($isPriorityMobile || !$memberPcAddress))
    {
      return $memberMobileAddress;
    }

    if ($memberPcAddress)
    {
      return $memberPcAddress;
    }

    return null;
  }

  public function getEmailAddresses()
  {
    $result = array();

    $memberPcAddress     = $this->getConfig('pc_address');
    $memberMobileAddress = $this->getConfig('mobile_address');

    if ($memberPcAddress)
    {
      $result[] = $memberPcAddress;
    }

    if ($memberMobileAddress)
    {
      $result[] = $memberMobileAddress;
    }

    return $result;
  }

  public function delete(Doctrine_Connection $conn = null)
  {
    $communityMemberTable = Doctrine::getTable('CommunityMember');
    $communityIds = $communityMemberTable->getCommunityIdsOfAdminByMemberId($this->getId());

    foreach ($communityIds as $communityId)
    {
      $communityMembers = $communityMemberTable->getCommunityMembers($communityId);
      if (!$communityMembers->count())
      {
        $community = Doctrine::getTable('Community')->find($communityId);
        $community->delete();
        continue;
      }
      $communityMembers[0]->setPosition('admin');
      $communityMembers[0]->save();

      $adminCommunityMember = $communityMemberTable->retrieveByMemberIdAndCommunityId($this->getId(), $communityId);
      $adminCommunityMember->delete();
    }
    return parent::delete($conn);
  }

  public function getMailAddressHash($length = null)
  {
    if (is_null($length))
    {
      $length = sfConfig::get('op_mail_address_hash_length', 12);
    }

    $hash = $this->getConfig('mail_address_hash');
    if (!$hash)
    {
      $hash = md5(strval($this->id).$this->getConfig('password'));
    }

    return substr($hash, 0, (int)$length);
  }

  public function generateRoleId(Member $member)
  {
    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->id, $member->id);

    if ($this->id === $member->id)
    {
      return 'self';
    }
    elseif ($relation && $relation->getIsAccessBlock())
    {
      return 'blocked';
    }

    return 'everyone';
  }
}
