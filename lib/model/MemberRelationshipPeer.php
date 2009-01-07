<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'member_relationship' table.
 *
 * 
 *
 * @package lib.model
 */ 
class MemberRelationshipPeer extends BaseMemberRelationshipPeer
{
  public static function retrieveByFromAndTo($memberIdFrom, $memberIdTo)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $memberIdFrom);
    $c->add(self::MEMBER_ID_TO, $memberIdTo);
    return self::doSelectOne($c);
  }

  public static function retrievesByMemberIdFrom($memberId)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_FROM, $memberId);
    return self::doSelect($c);
  }

  public static function getFriendListPager($memberId, $page = 1, $size = 20)
  {
    $c = new Criteria();
    $c->add(self::MEMBER_ID_TO, $memberId);
    $c->add(self::IS_FRIEND, true);
    $c->addJoin(MemberPeer::ID, self::MEMBER_ID_FROM);

    $pager = new sfPropelPager('Member', $size);
    $pager->setCriteria($c);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public static function getFriendMemberIds($memberId)
  {
    $ids = array();

    $c = new Criteria();
    $c->clearSelectColumns()->addSelectColumn(self::MEMBER_ID_FROM);
    $c->add(self::MEMBER_ID_TO, $memberId);
    $c->add(self::IS_FRIEND, true);

    $stmt = self::doSelectStmt($c);
    while ($row = $stmt->fetch(PDO::FETCH_NUM))
    {
      $ids[] = (int)$row[0];
    }
    return $ids;
  }
}
