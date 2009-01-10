<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class MemberPeer extends BaseMemberPeer
{
  public static function createPre()
  {
    $member = new Member();
    $member->setIsActive(false);
    $member->save();

    return $member;
  }

  public static function searchMemberIds($member = array(), $ids = null)
  {
    // the searchable field of the member table is only "name"
    if (!empty($member['name']))
    {
      $_result = array();

      $c = new Criteria();
      $c->clearSelectColumns()->addSelectColumn(self::ID);
      $c->setIgnoreCase(false);
      $c->add(self::NAME, '%'.$member['name'].'%', Criteria::LIKE);
      $stmt = self::doSelectStmt($c);
      while ($raw = $stmt->fetch(PDO::FETCH_NUM))
      {
        $_result[] = $raw[0];
      }

      if (is_array($ids))
      {
        $ids = array_values(array_intersect($ids, $_result));
      }
      else
      {
        $ids = array_values($_result);
      }
    }

    return $ids;
  }
}
