<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision26_AddNotificationMailIndex extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->foreignKey($direction, 'notification_mail_translation', 'notification_mail_translation_id_notification_mail_id', array(
      'name' => 'notification_mail_translation_id_notification_mail_id',
      'local' => 'id',
      'foreign' => 'id',
      'foreignTable' => 'notification_mail',
      'onUpdate' => 'CASCADE',
      'onDelete' => 'CASCADE',
    ));

    $this->index($direction, 'notification_mail_translation', 'notification_mail_translation_id', array(
      'fields' => array(0 => 'id'),
    ));
  }
}
