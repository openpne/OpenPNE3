<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberRelationshipTable extends opDoctrineTable
{
  public function retrieveByFromAndTo($memberIdFrom, $memberIdTo)
  {
    return $this->createQuery()
      ->where('member_id_from = ?', $memberIdFrom)
      ->andWhere('member_id_to = ?', $memberIdTo)
      ->fetchOne();
  }

  public function retrievesByMemberIdFrom($memberId)
  {
    return $this->createQuery()
      ->where('member_id_from = ?', $memberId)
      ->execute();
  }

  public function getFriendListPager($memberId, $page = 1, $size = 20)
  {
    $subQuery = Doctrine::getTable('MemberRelationship')->createQuery()
        ->select('mr.member_id_to')
        ->from('MemberRelationship mr')
        ->where('member_id_from = ?')
        ->andWhere('is_friend = ?');

    $q = Doctrine::getTable('Member')->createQuery()
        ->where('id IN ('.$subQuery->getDql().')', array($memberId, true));

    $pager = new sfDoctrinePager('Member', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getFriendMemberIds($memberId)
  {
    $result = array();

    $collection = $this->createQuery()
      ->select('member_id_to')
      ->where('member_id_from = ?', $memberId)
      ->andWhere('is_friend = ?', true)
      ->execute();

    foreach ($collection as $record)
    {
      $result[] = $record->member_id_to;
    }

    return $result;
  }
}
