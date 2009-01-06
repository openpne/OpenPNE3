<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
}
