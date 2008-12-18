<?php

/**
 * Subclass for representing a row from the 'member' table.
 *
 * 
 *
 * @package lib.model
 */ 
class Member extends BaseMember
{
  public function getProfiles()
  {
    return MemberProfilePeer::getProfileListByMemberId($this->getId());
  }

  public function getProfile($profileName)
  {
    $profile = MemberProfilePeer::retrieveByMemberIdAndProfileName($this->getId(), $profileName);

    if (!$profile)
    {
      return null;
    }

    return $profile->getValue();
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
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    if (!is_null($limit)) {
      $c->setLimit($limit);
    }
    return $this->getMemberRelationshipsRelatedByMemberIdTo($c);
  }

  public function countFriends(Criteria $c = null)
  {
    if (!$c)
    {
      $c = new Criteria();
    }
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    return $this->countMemberRelationshipsRelatedByMemberIdTo($c);
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
}
