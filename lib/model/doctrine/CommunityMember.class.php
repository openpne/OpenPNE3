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

  public function hasPosition($name)
  {
    return Doctrine::getTable('CommunityMemberPosition')->hasPosition($this->getMemberId(), $this->getCommunityId(), $name);
  }

  public function addPosition($name)
  {
    $object = Doctrine::getTable('CommunityMemberPosition')->findOneByCommunityMemberIdAndName($this->getId(), $name);
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
    $object = Doctrine::getTable('CommunityMemberPosition')->findOneByCommunityMemberIdAndName($this->getId(), $name);
    if (!$object)
    {
      throw new LogicException('The role data does not exist.');
    }
    $object->delete();
  }
}
