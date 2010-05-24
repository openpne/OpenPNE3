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

  public function getAge($viewableCheck = false, $myMemberId = null)
  {
    if (!$myMemberId)
    {
      $myMemberId = sfContext::getInstance()->getUser()->getMemberId();
    }

    $birthday = $this->getProfile('op_preset_birthday');
    if (!(string)$birthday)
    {
      return false;
    }

    $age = opToolkit::calculateAge($birthday);
    $publicFlag = $this->getConfig('age_public_flag');
    if (!$viewableCheck || ($publicFlag == ProfileTable::PUBLIC_FLAG_SNS && $myMemberId))
    {
      return $age;
    }

    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($myMemberId, $this->id);
    if ($publicFlag == ProfileTable::PUBLIC_FLAG_FRIEND && ($this->id == $myMemberId || ($relation && $relation->isFriend())))
    {
      return $age;
    }

    if ($publicFlag == ProfileTable::PUBLIC_FLAG_WEB && Doctrine::getTable('SnsConfig')->get('is_allow_web_public_flag_age'))
    {
      return $age;
    }

    return false;
  }

  public function getConfig($configName, $default = null)
  {
    $config = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId($configName, $this->getId());

    return $config ? $config->getValue() : $default;
  }

  public function setConfig($configName, $value, $isDateTime = false)
  {
    Doctrine::getTable('MemberConfig')->setValue($this->getId(), $configName, $value, $isDateTime);
  }

  public function getFriends($limit = null, $isRandom = false)
  {
    return Doctrine::getTable('MemberRelationship')->getFriends($this->getId(), $limit, $isRandom);
  }

  public function countFriends()
  {
    return count(Doctrine::getTable('MemberRelationship')->getFriendMemberIds($this->getId()));
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
      $community = Doctrine::getTable('Community')->find($communityId);
      $communityMembers = $communityMemberTable->getCommunityMembers($communityId);
      if (!$communityMembers->count())
      {
        $community->delete();
        continue;
      }

      $subAdminMember = Doctrine::getTable('CommunityMemberPosition')->findOneByCommunityIdAndName($communityId, 'sub_admin');
      if ($subAdminMember)
      {
        $communityMember = $subAdminMember->getCommunityMember();
      }
      else
      {
        $communityMember = $communityMembers[0];
      }

      $communityMember->removeAllPosition();
      $communityMember->addPosition('admin');

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
    elseif ($member instanceof opAnonymousMember)
    {
      return 'anonymous';
    }

    return 'everyone';
  }

  public function generateRegisterToken()
  {
    $token = $this->getId().md5(uniqid(mt_rand(), true));
    $this->setConfig('register_token', $token);

    return $token;
  }

  public function getRegisterToken()
  {
    return $this->getConfig('register_token');
  }
}
