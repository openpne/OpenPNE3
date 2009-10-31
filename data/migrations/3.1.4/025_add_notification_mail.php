<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision25_AddNotificationMail extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'notification_mail', array(
      'id' => array(
        'type' => 'integer',
        'primary' => '1',
        'autoincrement' => '1',
        'comment' => 'Serial number',
        'length' => '4',
      ),
      'name' => array(
        'type' => 'string',
        'default' => '',
        'notnull' => '1',
        'comment' => 'Notification Identifier Name',
        'length' => '64',
      ),
      'renderer' => array(
        'type' => 'string',
        'default' => 'twig',
        'notnull' => '1',
        'comment' => 'Notification Template Renderer',
        'length' => '64',
      ),
      'is_enabled' => array(
        'type' => 'boolean',
        'default' => '1',
        'notnull' => '1',
        'comment' => 'Notification Enabled',
        'length' => '25',
      )), array(
        'type' => 'INNODB',
        'indexes' => array(
          'is_enabled_INDEX' => array('fields' => array(0 => 'is_enabled')),
          'name_UNIQUE' => array('fields' => array(0 => 'name'), 'type' => 'unique'),
        ),
        'primary' => array(0 => 'id'),
        'collate' => 'utf8_unicode_ci',
        'charset' => 'utf8',
    ));
    $this->table($direction, 'notification_mail_translation', array(
      'id' => array(
        'type' => 'integer',
        'comment' => 'Serial number',
        'length' => '4',
        'primary' => '1',
      ),
      'title' => array(
        'type' => 'string',
        'default' => '',
        'notnull' => '1',
        'comment' => 'Notification Title',
        'length' => '64',
      ),
      'template' => array(
        'type' => 'string',
        'default' => '',
        'notnull' => '1',
        'comment' => 'Notification Template',
        'length' => '',
      ),
      'lang' => array(
        'fixed' => '1',
        'primary' => '1',
        'type' => 'string',
        'length' => '5',
      )), array(
        'type' => 'INNODB',
        'primary' => 
        array(
         0 => 'id',
         1 => 'lang',
        ),
        'collate' => 'utf8_unicode_ci',
        'charset' => 'utf8',
    ));
  }
}
