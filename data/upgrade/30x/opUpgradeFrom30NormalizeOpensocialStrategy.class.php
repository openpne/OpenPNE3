<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * This strategy fixes opensocial tables for simple upgrading.
 *
 * @package    OpenPNE
 * @subpackage upgrade
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opUpgradeFrom30NormalizeOpensocialStrategy extends opUpgradeAbstractStrategy
{
  public function run()
  {
    $conn = $this->getDatabaseManager()->getDatabase('doctrine')->getDoctrineConnection();

    $conn->export->dropForeignKey('application_i18n', 'application_i18n_FK_1');

    $conn->export->dropForeignKey('application_persistent_data', 'application_persistent_data_FK_1');
    $conn->export->dropForeignKey('member_application', 'member_application_FK_2');
    $conn->export->dropForeignKey('member_application_setting', 'member_application_setting_FK_1');

    $conn->export->alterTable('application', array('change' => array(
      'id' => array('definition' => Doctrine::getTable('Application')->getColumnDefinition('id')),
    )));
    $conn->export->alterTable('member_application_setting', array('change' => array(
      'id' => array('definition' => Doctrine::getTable('MemberApplicationSetting')->getColumnDefinition('id')),
    )));
    $conn->export->alterTable('application_persistent_data', array('change' => array(
      'application_id' => array('definition' => Doctrine::getTable('ApplicationPersistentData')->getColumnDefinition('application_id')),
    )));
    $conn->export->alterTable('member_application', array('change' => array(
      'application_id' => array('definition' => Doctrine::getTable('MemberApplication')->getColumnDefinition('application_id')),
    )));

    // repair foreign key
    $conn->export->createForeignKey('application_persistent_data', array(
      'name'         => 'application_persistent_data_FK_1',
      'foreignTable' => 'application',
      'foreign'      => 'id',
      'local'        => 'application_id',
    ));
    $conn->export->createForeignKey('member_application', array(
      'name'         => 'member_application_FK_2',
      'foreignTable' => 'application',
      'foreign'      => 'id',
      'local'        => 'application_id',
    ));
    $conn->export->createForeignKey('member_application_setting', array(
      'name'         => 'member_application_setting_FK_1',
      'foreignTable' => 'member_application',
      'foreign'      => 'id',
      'local'        => 'member_application_id',
    ));

    // drop useless table
    $conn->export->dropTable('opensocial_person_field');
  }
}
