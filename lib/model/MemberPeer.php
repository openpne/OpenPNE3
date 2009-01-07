<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Subclass for performing query and update operations on the 'member' table.
 *
 * 
 *
 * @package lib.model
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
}
