<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityTable extends Doctrine_Table
{
  public function retrievesByMemberId($memberId, $limit = 5, $isRandom = false)
  {
    $q = $this->createQuery('c')
        ->leftJoin('c.CommunityMember cm')
        ->where('cm.position <> ?', 'pre')
        ->andWhere('cm.member_id = ?', $memberId);

    if (!is_null($limit))
    {
      $q->limit($limit);
    }

    if ($isRandom)
    {
      $expr = new Doctrine_Expression('RANDOM()');
      $q->orderBy($expr);
    }

    return $q->execute();
  }

  public function getJoinCommunityListPager($memberId, $page = 1, $size = 20)
  {
    $q = $this->createQuery('c')
      ->leftJoin('c.CommunityMember cm')
      ->where('cm.position <> ?', 'pre')
      ->andWhere('cm.member_id = ?', $memberId);

    $pager = new sfDoctrinePager('Community', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getCommunityMemberListPager($communityId, $page = 1, $size = 20)
  {
    $q = Doctrine::getTable('Member')->createQuery()
      ->leftJoin('Member.CommunityMember cm')
      ->where('cm.position <> ?', 'pre')
      ->andWhere('cm.community_id = ?', $communityId);

    $pager = new sfDoctrinePager('Member', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getIdsByMemberId($memberId)
  {
    $result = array();

    $resultSet = $this->createQuery()
      ->select('id')
      ->leftJoin('Community.CommunityMember cm')
      ->where('cm.member_id = ?', $memberId)
      ->andWhere('cm.position <> ?', 'pre')
      ->execute();

    foreach ($resultSet as $value)
    {
      $result[] = $value->getId();
    }

    return $result;
  }

  public function getDefaultCommunities()
  {
    return Doctrine::getTable('Community')->createQuery()
      ->where('cc.name = ?', 'is_default')
      ->andWhere('cc.value = ?', true)
      ->leftJoin('Community.CommunityConfig cc')
      ->execute();
  }
}
