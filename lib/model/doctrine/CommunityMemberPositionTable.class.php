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
    $query = $this->createQuery()
      ->where('member_id = ?', $memberId)
      ->andWhere('community_id = ?', $communityId);

    $results = array();
    foreach ($query->fetchArray() as $position)
    {
      $results[] = $position['name'];
    }

    $query->free();

    return $results;
  }
}
