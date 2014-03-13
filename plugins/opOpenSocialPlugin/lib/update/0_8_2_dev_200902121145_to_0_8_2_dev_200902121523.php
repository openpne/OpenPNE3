<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPluginUpdate_0_8_2_dev_200902121145_to_0_8_2_dev_200902121523 extends opUpdate
{
  public function update()
  {
    $this->createTable(
      'application_persistent_data',
      array(
        'id' => array('type' => 'integer', 'primary' => true, 'autoincrement' => true),
        'application_id' => array('type' => 'integer'),
        'member_id' => array('type' => 'integer'),
        'name' => array('type' => 'string', 'length' => 128),
        'value' => array('type' => 'string', 'length' => 65535)
      )
    );

    $this->createForeignKey('application_persistent_data', array(
      'name' => 'application_persistent_data_FI_1',
      'local' => 'application_id',
      'foreign' => 'id',
      'foreignTable' => 'application',
      'onDelete' => 'CASCADE'
    ));

    $this->createForeignKey('application_persistent_data', array(
      'name' => 'application_persistent_data_FI_2',
      'local' => 'member_id',
      'foreign' => 'id',
      'foreignTable' => 'member',
      'onDelete' => 'CASCADE'
    ));
  }
}
