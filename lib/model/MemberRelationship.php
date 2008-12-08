<?php

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
    $result = $this->save();
    if (!$result) {
      return false;
    }

    $this->getToInstance()->setIsFriendPre(false);
    return $this->getToInstance()->save();
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
