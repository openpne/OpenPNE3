<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opOpenSocialPlugin5_ChangeMemberApplicationSetting extends Doctrine_Migration_Base
{
  public function up()
  {
    $manager = Doctrine_Manager::getInstance();
    $export = $manager->getCurrentConnection()->export;

    $export->dropIndex('member_application_setting', 'name_UNIQUE_idx');
    $export->alterTable('member_application_setting', array(
      'add' => array(
        'hash' => array(
          'type' => 'string',
          'length' => 32,
        )
      )
    ));

    foreach (Doctrine::getTable('MemberApplicationSetting')->findAll() as $object)
    {
      $object->save();
    }

    $export->alterTable('member_application_setting', array(
      'change' => array(
        'hash' => array(
          'definition' => array(
            'type' => 'string',
            'length' => 32,
            'notnull' => true
          )
        )
      )
    ));
    $export->createIndex('member_application_setting', 'hash_UNIQUE_idx', array(
      'fields' => array('hash' => array()),
      'type'   => 'unique'
    ));
  }

  public function down()
  {
    $manager = Doctrine_Manager::getInstance();
    $export = $manager->getCurrentConnection()->export;

    $export->dropIndex('member_application_setting', 'hash_UNIQUE_idx');
    $export->alterTable('member_application_setting', array(
      'remove' => array(
        'hash' => array()
      )
    ));
  }
}
