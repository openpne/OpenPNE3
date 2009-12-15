<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * CommunityMemberPositionTable
 * 
 * @package    OpenPNE
 * @subpackage model
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */
class CommunityMemberPositionTable extends Doctrine_Table
{
  public function getPositionsByMemberIdAndCommunityId($memberId, $communityId)
  {
    $objects = $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('community_id = ?', $communityId)
      ->execute();

    $results = array();
    foreach ($objects as $obj)
    {
      $results[] = $obj->getName();
    }
    return $results;
  }
}
