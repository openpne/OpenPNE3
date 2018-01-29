<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPlugin4_ChangeTable extends Doctrine_Migration_Base
{
  public function up()
  {
    $manager = Doctrine_Manager::getInstance();
    $export = $manager->getCurrentConnection()->export;

    $export->dropForeignKey('application_persistent_data', 'application_persistent_data_FK_1');
    $export->dropForeignKey('application_persistent_data', 'application_persistent_data_FK_2');

    $export->dropForeignKey('member_application', 'member_application_FK_1');
    $export->dropForeignKey('member_application', 'member_application_FK_2');
    
    $export->dropTable('member_application_setting');
    $export->dropTable('opensocial_person_field');

    // member_application
    $export->createForeignKey('member_application', array(
      'name'         => 'member_application_member_id_member_id',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
      'onDelete'     => 'cascade',
    ));
    $export->createForeignKey('member_application', array(
      'name'         => 'member_application_application_id_application_id',
      'local'        => 'application_id',
      'foreign'      => 'id',
      'foreignTable' => 'application',
      'onDelete'     => 'cascade',
    ));

    // application_persistent_data
    $export->createForeignKey('application_persistent_data', array(
      'name'         => 'application_persistent_data_application_id_application_id',
      'local'        => 'application_id',
      'foreign'      => 'id',
      'foreignTable' => 'application',
      'onDelete'     => 'cascade',
    ));
    $export->createForeignKey('application_persistent_data', array(
      'name'         => 'application_persistent_data_member_id_member_id',
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
      'onDelete'     => 'cascade',
    ));
    $export->createIndex('application_persistent_data', 'name_UNIQUE_idx', array(
      'type'   => 'unique',
      'fields' => array('application_id', 'member_id', 'name'),
    ));

    // new member application setting
    $export->createTable('member_application_setting', array(
      'id' => array('type' => 'integer', 'primary' => true, 'autoincrement' => true),
      'member_application_id' => array('type' => 'integer'),
      'type' => array('type' => 'enum', 'values' => array('application', 'user'), 'notnull' => true, 'default' => 'application'),
      'name' => array('type' => 'string', 'length' => 255, 'notnull' => true),
      'value' => array('type' => 'string'),
    ), array('charset' => 'utf8'));

    $export->createIndex('member_application_setting', 'name_UNIQUE_idx', array(
      'type'   => 'unique',
      'fields' => array('member_application_id', 'type', 'name'),
    ));
    $export->createIndex('member_application_setting', 'member_application_id_idx', array(
      'fields' => array('member_application_id'),
    ));
    $export->createForeignKey('member_application_setting', array(
      'name'         => 'member_application_id',
      'local'        => 'member_application_id',
      'foreign'      => 'id',
      'foreignTable' => 'member_application',
      'onDelete'     => 'cascade'
    ));

    $this->renameTable('application_i18n', 'application_translation');
  }

  public function down()
  {
  }
}
