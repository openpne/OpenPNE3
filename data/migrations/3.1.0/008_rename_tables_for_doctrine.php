<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class renameTablesForDoctrine extends Doctrine_Migration_Base
{
  public function up()
  {
    $manager = Doctrine_Manager::getInstance();
    $export = $manager->getCurrentConnection()->export;

    $export->dropForeignKey('navigation_i18n', 'navigation_i18n_FK_1');
    $export->dropForeignKey('profile_i18n', 'profile_i18n_FK_1');
    $export->dropForeignKey('profile_option_i18n', 'profile_option_i18n_FK_1');

    // for eluding bugs that are contained some plugins
    try
    {
      $export->dropForeignKey('community_topic_comment', 'community_topic_comment_FK_1');
      $export->dropForeignKey('community_event_comment', 'community_event_comment_FK_1');
      $export->dropForeignKey('community_event_member', 'community_event_member_FK_1');
      $export->dropForeignKey('application_i18n', 'application_i18n_FK_1');
      $export->dropForeignKey('application_persistent_data', 'application_persistent_data_FK_1');
      $export->dropForeignKey('member_application', 'member_application_FK_2');
      $export->dropForeignKey('diary_comment', 'diary_comment_FK_1');
      $export->dropForeignKey('diary_image', 'diary_image_FK_1');
      $export->dropForeignKey('diary_comment_unread', 'diary_comment_unread_FK_1');
    }
    catch (Doctrine_Connection_Exception $e)
    {
      $syntaxErrorCode = 42;
      if ($e->getCode() != $syntaxErrorCode)
      {
        throw $e;
      }
    }

    $this->renameTable('navigation_i18n', 'navigation_translation');
    $this->renameTable('profile_i18n', 'profile_translation');
    $this->renameTable('profile_option_i18n', 'profile_option_translation');
  }

  public function down()
  {
    $this->renameTable('navigation_translation', 'navigation_i18n');
    $this->renameTable('profile_translation', 'profile_i18n');
    $this->renameTable('profile_option_translation', 'profile_option_i18n');
  }
}
