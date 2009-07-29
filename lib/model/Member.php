<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Member
 *
 * @package    OpenPNE
 * @subpackage model
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */ 
class Member extends BaseMember
{
  public function getProfiles($viewableCheck = false, $myMemberId = null)
  {
    if ($viewableCheck)
    {
      return MemberProfilePeer::getViewableProfileListByMemberId($this->getId(), $myMemberId);
    }
    return MemberProfilePeer::getProfileListByMemberId($this->getId());
  }

  public function getProfile($profileName)
  {
    $profile = MemberProfilePeer::retrieveByMemberIdAndProfileName($this->getId(), $profileName);
    return $profile;
  }

  public function getConfig($configName)
  {
    $config = MemberConfigPeer::retrieveByNameAndMemberId($configName, $this->getId());

    if (!$config)
    {
      return null;
    }

    return $config->getValue();
  }

  public function setConfig($configName, $value)
  {
    $config = MemberConfigPeer::retrieveByNameAndMemberId($configName, $this->getId());
    if (!$config)
    {
      $config = new MemberConfig();
      $config->setMember($this);
      $config->setName($configName);
    }
    $config->setValue($value);
    $config->save();
  }

  public function getFriends($limit = null, Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    if (!is_null($limit)) {
      $c->setLimit($limit);
    }
    $c->add(MemberRelationshipPeer::MEMBER_ID_TO, $this->getId());
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    $c->addJoin(MemberPeer::ID, MemberRelationshipPeer::MEMBER_ID_FROM);
    return MemberPeer::doSelect($c);
  }
  
  public function countFriends(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(MemberRelationshipPeer::MEMBER_ID_TO, $this->getId());
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    $c->addJoin(MemberPeer::ID, MemberRelationshipPeer::MEMBER_ID_FROM);
    return MemberPeer::doCount($c);
  }
  
  public function getNameAndCount($format = '%s (%d)')
  {
    return sprintf($format, $this->getName(), $this->countFriends());
  }
  
  public function getJoinCommunities($limit = null, Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    return CommunityPeer::retrievesByMemberId($this->getId(), $limit, $c);
  }

  public function getFriendPreTo(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->getMemberRelationshipsRelatedByMemberIdTo($c);
  }

  public function countFriendPreTo(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->countMemberRelationshipsRelatedByMemberIdTo($c);
  }

  public function getFriendPreFrom(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->getMemberRelationshipsRelatedByMemberIdFrom($c);
  }

  public function countFriendPreFrom(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(MemberRelationshipPeer::IS_FRIEND_PRE, true);
    return $this->countMemberRelationshipsRelatedByMemberIdFrom($c);
  }

  public function getImage()
  {
    $c = new Criteria();
    $c->addDescendingOrderByColumn(MemberImagePeer::IS_PRIMARY, true);
    $result = $this->getMemberImages($c);

    if ($result)
    {
      return array_shift($result);
    }

    return false;
  }
  
  public function getImageFileName()
  {
    if($this->getImage())
    {
      return $this->getImage()->getFile();
    }
    return false;
  }

  public function updateLastLoginTime()
  {
    $this->setConfig('lastLogin', time());
  }

  public function getLastLoginTime()
  {
    return $this->getConfig('lastLogin');
  }

  public function isOnBlackList()
  {
    $uid = $this->getConfig('mobile_uid');
    if ($uid)
    {
      return (bool)BlacklistPeer::retrieveByUid($uid);
    }

    return false;
  }

  public function getInvitingMembers()
  {
    return MemberPeer::retrivesByInviteMemberId($this->getId());
  }

  public function getInviteMember()
  {
    return MemberPeer::retrieveByPk($this->getInviteMemberId());
  }

  public function getEmailAddress($isPriorityMobile = null)
  {
    if(is_null($isPriorityMobile))
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

  public function delete(PropelPDO $con = null)
  {
    $adminCommunityIds = CommunityMemberPeer::getCommunityIdsOfAdminByMemberId($this->getId());
    foreach ($adminCommunityIds as $communityId)
    {
      if (!CommunityMemberPeer::getCommunityMemberCount($communityId))
      {
        $community = CommunityPeer::retrieveByPk($communityId);
        $community->delete();
        continue;
      }
      $c = new Criteria();
      $c->add(CommunityMemberPeer::COMMUNITY_ID, $communityId);
      $c->add(CommunityMemberPeer::POSITION, '');
      $communityMember = CommunityMemberPeer::doSelectOne($c);
      $communityMember->setPosition('admin');
      $communityMember->save();
      $communityMember = CommunityMemberPeer::retrieveByMemberIdAndCommunityId($this->getId(), $communityId);
      $communityMember->delete();
    }

    return parent::delete($con);
  }
}
