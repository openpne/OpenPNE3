<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision39_AddIndexToMemberConfigHashColumn extends Doctrine_Migration_Base
{
  public function up()
  {
    $conn = Doctrine_Manager::connection();
    if (!$conn->import->tableIndexExists('name_value_hash_INDEX_idx', 'member_config'))
    {
      $options = array('fields' => array('name_value_hash'));
      $this->addIndex('member_config', 'name_value_hash_INDEX_idx', $options);
    }
  }
}
