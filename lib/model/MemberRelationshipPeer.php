<?php

/**
 * Copyright (C) 2005-2009 OpenPNE Project
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
