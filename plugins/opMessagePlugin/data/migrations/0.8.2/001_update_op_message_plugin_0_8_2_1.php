<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class updateOpMessagePlugin_0_8_2_1 extends opMigration
{
  public function up()
  {
    $conn = Doctrine_Manager::connection();
    $export = $conn->export;

    // message table
    $export->dropForeignKey('message', 'message_FK_1');
    $export->createForeignKey('message', array(
      'name'         => 'message_FK_1',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
      'onDelete'     => 'SET NULL'
    ));

    // message_send_list table
    $export->dropForeignKey('message_send_list', 'message_send_list_FK_1');
    $export->createForeignKey('message_send_list', array(
      'name'         => 'message_send_list_FK_1',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
      'onDelete'     => 'SET NULL'
    ));

    // delete_message table
    $export->dropForeignKey('deleted_message', 'deleted_message_FK_1');
    $export->createForeignKey('deleted_message', array(
      'name'         => 'deleted_message_FK_1',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
      'onDelete'     => 'CASCADE'
    ));
  }

  public function down()
  {
    $conn = Doctrine_Manager::connection();
    $export = $conn->export;

    // message table
    $export->dropForeignKey('message', 'message_FK_1');
    $export->createForeignKey('message', array(
      'name'         => 'message_FK_1',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
    ));

    // message_send_list table
    $export->dropForeignKey('message_send_list', 'message_send_list_FK_1');
    $export->createForeignKey('message_send_list', array(
      'name'         => 'message_send_list_FK_1',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
    ));

    // deleted_message table
    $export->dropForeignKey('deleted_message', 'deleted_message_FK_1');
    $export->createForeignKey('deleted_message', array(
      'name'         => 'deleted_message_FK_1',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
    ));
 }
}
