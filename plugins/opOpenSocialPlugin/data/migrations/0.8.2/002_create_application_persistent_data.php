<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class create_application_persistent_data extends opMigration
{
  public function up()
  {
    $conn = Doctrine_Manager::connection();

    $conn->export->createTable(
      'application_persistent_data',
      array(
        'id' => array('type' => 'integer', 'primary' => true, 'autoincrement' => true),
        'application_id' => array('type' => 'integer'),
        'member_id' => array('type' => 'integer'),
        'name' => array('type' => 'string', 'length' => 128),
        'value' => array('type' => 'string', 'length' => 65535)
      )
    );

    $conn->export->createForeignKey('application_persistent_data', array(
      'name' => 'application_persistent_data_FI_1',
      'local' => 'application_id',
      'foreign' => 'id',
      'foreignTable' => 'application',
      'onDelete' => 'CASCADE'
    ));

    $conn->export->createForeignKey('application_persistent_data', array(
      'name' => 'application_persistent_data_FI_2',
      'local' => 'member_id',
      'foreign' => 'id',
      'foreignTable' => 'member',
      'onDelete' => 'CASCADE'
    ));
  }

  public function down()
  {
    $conn = Doctrine_Manager::connection();

    $conn->export->dropTable('application_persistent_data');
  }
}
