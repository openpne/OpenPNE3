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
 * Subclass for representing a row from the 'member_relationship' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberRelationship extends BaseMemberRelationship
{
  private $toInstance;

  public function save(PropelPDO $con = null)
  {
    if ($this->isSelf())
    {
      throw new LogicException('Cannot save an object because member_id_to is equal to member_id_from');
    }

    return parent::save($con);
  }

  public function isFriend()
  {
    return (bool)$this->getIsFriend();
  }

  public function isFriendPreFrom()
  {
    return (bool)$this->getIsFriendPre();
  }

  public function isFriendPreTo()
  {
    return (bool)$this->getToInstance()->getIsFriendPre();
  }

  public function isFriendPre()
  {
    return (bool)($this->isFriendPreTo() || $this->isFriendPreFrom());
  }

  public function isSelf()
  {
    return (bool)($this->getMemberIdTo() == $this->getMemberIdFrom());
  }

  public function isAccessBlocked()
  {
    return (bool)$this->getToInstance()->getIsAccessBlock();
  }

  public function setFriendPre()
  {
    $this->setIsFriendPre(true);
    return $this->save();
  }

  public function setFriend()
  {
    $this->removeFriendPre();

    $this->setIsFriend(true);
    $result = $this->save();
    if (!$result) {
      return false;
    }

    $this->getToInstance()->setIsFriend(true);
    return $this->getToInstance()->save();
  }

  public function removeFriend()
  {
    $this->setIsFriend(false);
    $result = $this->save();
    if (!$result) {
      return false;
    }

    $this->getToInstance()->setIsFriend(false);
    return $this->getToInstance()->save();
  }

  public function removeFriendPre()
  {
    $this->setIsFriendPre(false);
    $resultFrom = $this->save();
    $this->getToInstance()->setIsFriendPre(false);
    $resultTo = $this->getToInstance()->save();

    return $resultFrom && $resultTo;
  }

  public function getToInstance()
  {
    if ($this->toInstance) {
      return $this->toInstance;
    }

    $relation = MemberRelationshipPeer::retrieveByFromAndTo($this->getMemberIdTo(), $this->getMemberIdFrom());
    if (!$relation) {
      $relation = new MemberRelationship();
      $relation->setMemberIdFrom($this->getMemberIdTo());
      $relation->setMemberIdTo($this->getMemberIdFrom());
    }

    $this->toInstance = $relation;
    return $this->toInstance;
  }
}
