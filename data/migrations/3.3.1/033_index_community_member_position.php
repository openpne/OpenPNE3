<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision33_IndexCommunityMemberPosition extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->foreignKey($direction, 'community_member_position', 'community_member_position_member_id_member_id', array(
      'name' => 'community_member_position_member_id_member_id',
      'local' => 'member_id',
      'foreign' => 'id',
      'foreignTable' => 'member',
      'onDelete' => 'CASCADE',
    ));

    $this->foreignKey($direction, 'community_member_position', 'community_member_position_community_id_community_id', array(
      'name' => 'community_member_position_community_id_community_id',
      'local' => 'community_id',
      'foreign' => 'id',
      'foreignTable' => 'community',
      'onDelete' => 'CASCADE',
    ));

    $this->foreignKey($direction, 'community_member_position', 'ccci', array(
      'name' => 'ccci',
      'local' => 'community_member_id',
      'foreign' => 'id',
      'foreignTable' => 'community_member',
      'onDelete' => 'CASCADE',
    ));
  }
}

