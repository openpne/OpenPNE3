<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision37_IndexForActivity extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->index($direction, 'activity_data', 'member_id_idx', array(
      'name' => 'member_id_idx',
      'fields' => array('member_id')
    ));

    $this->index($direction, 'activity_data', 'in_reply_to_activity_id_idx', array(
      'name' => 'in_reply_to_activity_id_idx',
      'fields' => array('in_reply_to_activity_id')
    ));

    $this->index($direction, 'activity_image', 'activity_data_id_idx', array(
      'name' => 'activity_data_id_idx',
      'fields' => array('activity_data_id')
    ));

    $this->index($direction, 'activity_image', 'file_id_idx', array(
      'name' => 'file_id_idx',
      'fields' => array('file_id')
    ));

    $this->foreignKey($direction, 'activity_data', 'activity_data_member_id_member_id', array(
      'name' => 'activity_data_member_id_member_id',
      'local' => 'member_id',
      'foreign' => 'id',
      'foreignTable' => 'member',
      'onDelete' => 'CASCADE'
    ));

    $this->foreignKey($direction, 'activity_data', 'activity_data_in_reply_to_activity_id_activity_data_id', array(
      'name' => 'activity_data_in_reply_to_activity_id_activity_data_id',
      'local' => 'in_reply_to_activity_id',
      'foreign' => 'id',
      'foreignTable' => 'activity_data',
      'onDelete' => 'CASCADE'
    ));

    $this->foreignKey($direction, 'activity_image', 'activity_image_file_id_file_id', array(
      'name' => 'activity_image_file_id_file_id',
      'local' => 'file_id',
      'foreign' => 'id',
      'foreignTable' => 'file',
      'onDelete' => 'CASCADE'
    ));

    $this->foreignKey($direction, 'activity_image', 'activity_image_activity_data_id_activity_data_id', array(
      'name' => 'activity_image_activity_data_id_activity_data_id',
      'local' => 'activity_data_id',
      'foreign' => 'id',
      'foreignTable' => 'activity_data',
      'onDelete' => 'CASCADE'
    ));
  }
}
