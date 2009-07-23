<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityMemberTable extends Doctrine_Table
{
  public function retrieveByMemberIdAndCommunityId($memberId, $communityId)
  {
    return $this->createQuery()
        ->where('member_id = ?', $memberId)
        ->andWhere('community_id = ?', $communityId)
        ->fetchOne();
  }

  public function isMember($memberId, $communityId)
  {
    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      return false;
    }
    return ($communityMember->position != 'pre');
  }

  public function isPreMember($memberId, $communityId)
  {
    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember)
    {
      return false;
    }
    return ($communityMember->position == 'pre');
  }

  public function isAdmin($memberId, $communityId)
  {
    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    if (!$communityMember) {
      return false;
    }

    if ($communityMember->position != 'admin') {
      return false;
    }

    return true;
  }

  public function join($memberId, $communityId, $isRegisterPoricy = 'open')
  {
    if ($this->isPreMember($memberId, $communityId))
    {
      throw new Exception('This member has already applied this community.');
    }

    if ($this->isMember($memberId, $communityId))
    {
      throw new Exception('This member has already joined this community.');
    }

    $communityMember = new CommunityMember();
    $communityMember->setMemberId($memberId);
    $communityMember->setCommunityId($communityId);
    if ($isRegisterPoricy == 'close')
    {
      $communityMember->position = 'pre';
    }
    $communityMember->save();
  }

  public function quit($memberId, $communityId)
  {
    if (!$this->isMember($memberId, $communityId)) {
      throw new Exception('This member is not a member of this community.');
    }

    if ($this->isAdmin($memberId, $communityId)) {
      throw new Exception('This member is community admin.');
    }

    $communityMember = $this->retrieveByMemberIdAndCommunityId($memberId, $communityId);
    $communityMember->delete();
  }

  public function getCommunityAdmin($communityId)
  {
    return $this->createQuery()
        ->where('community_id = ?', $communityId)
        ->andWhere('position = ?', 'admin')
        ->fetchOne();
  }

  public function getCommunityIdsOfAdminByMemberId($memberId)
  {
    $ids = array();

    $results = $this->createQuery()
        ->select('community_id')
        ->where('member_id = ?', $memberId)
        ->andWhere('position = ?', 'admin')
        ->execute();

    foreach ($results as $result)
    {
      $ids[] = $result->getCommunityId();
    }
    return $ids;
  }

  public function getCommunityMembersPre($memberId)
  {
    $adminCommunityIds = $this->getCommunityIdsOfAdminByMemberId($memberId);

    if (count($adminCommunityIds))
    {
      return $this->createQuery()
        ->whereIn('community_id', $adminCommunityIds)
        ->where('position = ?', 'pre')
        ->execute();
    }

    return null;
  }

  public function getCommunityMembers($communityId)
  {
    return $this->createQuery()
      ->where('community_id = ?', $communityId)
      ->addWhere('position = ?', '')
      ->execute();
  }
}
