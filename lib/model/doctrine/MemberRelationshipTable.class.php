<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberRelationshipTable extends Doctrine_Table
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
    $q = Doctrine::getTable('Member')->createQuery()
      ->where('mr.member_id_to = ?', $memberId)
      ->andWhere('mr.is_friend = ?', true)
      ->leftJoin('Member.MemberRelationship mr ON mr.member_id_from = Member.id');

    $pager = new sfDoctrinePager('Member', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function getFriendMemberIds($memberId)
  {
    return $this->createQuery()
      ->select('id')
      ->where('member_id_to = ?', $memberId)
      ->andWhere('is_friend = ?', true)
      ->fetchArray();
  }
}
