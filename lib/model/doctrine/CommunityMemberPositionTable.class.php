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
 * @author     Kimura Youichi <kim.upsilon@bucyou.net>
 */
class CommunityMemberPositionTable extends Doctrine_Table
{
  public function getPositionsByMemberIdAndCommunityId($memberId, $communityId)
  {
    $tableName = $this->getTableName();
    $query = 'SELECT name FROM '.$tableName.' WHERE member_id = ? AND community_id = ?';

    return $this->getConnection()->fetchArray($query, array($memberId, $communityId));
  }
}
