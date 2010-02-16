<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberRelationship extends BaseMemberRelationship implements opAccessControlRecordInterface
{
  private $toInstance;

  public function preSave($event)
  {
    if ($this->isSelf())
    {
      throw new LogicException('Cannot save an object because member_id_to is equal to member_id_from');
    }
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
    $this->save();

    $this->getToInstance()->setIsFriend(true);
    return $this->getToInstance()->save();
  }

  public function removeFriend()
  {
    $this->setIsFriend(false);
    $this->save();

    $this->getToInstance()->setIsFriend(false);
    return $this->getToInstance()->save();
  }

  public function removeFriendPre()
  {
    $this->setIsFriendPre(false);
    $resultFrom = $this->save();

    $this->getToInstance()->setIsFriendPre(false);
    $this->getToInstance()->save();

    return true;
  }

  public function getToInstance()
  {
    if ($this->toInstance) {
      return $this->toInstance;
    }

    $relation = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($this->getMemberIdTo(), $this->getMemberIdFrom());
    if (!$relation) {
      $relation = new MemberRelationship();
      $relation->setMemberIdFrom($this->getMemberIdTo());
      $relation->setMemberIdTo($this->getMemberIdFrom());
    }

    $this->toInstance = $relation;
    return $this->toInstance;
  }

  public function generateRoleId(Member $member)
  {
    if ($member instanceof opAnonymousMember)
    {
      return 'anonymous';
    }

    return 'everyone';
  }
}
