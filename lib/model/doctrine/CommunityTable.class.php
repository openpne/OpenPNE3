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
    $q = Doctrine::getTable('Community')->createQuery()
        ->where('Community.CommunityMember.position <> ?', 'pre')
        ->leftJoin('Community.CommunityMember');

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
    $q = Doctrine::getTable('Community')->createQuery()
      ->where('cm.position <> ?', 'pre')
      ->leftJoin('Community.CommunityMember cm');

    $pager = new sfDoctrinePager('Community', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getCommunityMemberListPager($communityId, $page = 1, $size = 20)
  {
    $q = Doctrine::getTable('Member')->createQuery()
      ->where('cm.position <> ?', 'pre')
      ->leftJoin('Member.CommunityMember cm');

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
      ->where('cm.member_id = ?', $memberId)
      ->andWhere('cm.position <> ?', 'pre')
      ->leftJoin('Community.CommunityMember cm')
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
