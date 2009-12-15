<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityMember extends BaseCommunityMember implements opAccessControlRecordInterface
{
  public function generateRoleId(Member $member)
  {
    return $this->Community->generateRoleId($member);
  }

  public function getPositions()
  {
    return Doctrine::getTable('CommunityMemberPosition')->getPositionsByMemberIdAndCommunityId($this->getMemberId(), $this->getCommunityId());
  }

  public function hasPosition($name)
  {
    if (!is_array($name))
    {
      $name = array($name);
    }
    foreach ($name as $n)
    {
      if (in_array($n, $this->getPositions()))
      {
        return true;
      }
    }
    return false;
  }

  public function addPosition($name)
  {
    $object = null;
    if (!$this->isNew())
    {
      $object = Doctrine::getTable('CommunityMemberPosition')->findOneByCommunityMemberIdAndName($this->getId(), $name);
    }
    if (!$object)
    {
      $object = new CommunityMemberPosition();
      $object->setMemberId($this->getMemberId());
      $object->setCommunityId($this->getCommunityId());
      $object->setCommunityMember($this);
      $object->setName($name);
      $object->save();
    }
  }

  public function removePosition($name)
  {
    if ($this->isNew())
    {
      return false;
    }
    $object = Doctrine::getTable('CommunityMemberPosition')->findOneByCommunityMemberIdAndName($this->getId(), $name);
    if (!$object)
    {
      throw new LogicException('The role data does not exist.');
    }
    $object->delete();
  }

  public function removeAllPosition()
  {
    if ($this->isNew())
    {
      return false;
    }
    Doctrine::getTable('CommunityMemberPosition')->createQuery()
      ->where('community_member_id = ?', $this->getId())
      ->delete()
      ->execute();
  }
}
