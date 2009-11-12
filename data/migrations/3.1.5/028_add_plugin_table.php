<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision28_AddPluginTable extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'plugin',
      array(
        'id' => array('type' => 'integer', 'primary' => '1', 'autoincrement' => '1', 'comment' => 'Serial number', 'length' => '4'),
        'name' => array('type' => 'string', 'default' => '', 'notnull' => '1', 'comment' => 'Nickname', 'length' => '64'),
        'is_enabled' => array('type' => 'boolean', 'default' => '1', 'notnull' => '1', 'comment' => 'Notification Enabled', 'length' => '25'),
        'created_at' => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
        'updated_at' => array( 'notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      ),
      array(
        'type' => 'INNODB',
         'indexes' => array(
           'is_enabled_INDEX' => array('fields' => array(0 => 'is_enabled')),
           'name_UNIQUE' => array('fields' => array(0 => 'name'), 'type' => 'unique'),
         ),
         'primary' => array(0 => 'id'),
         'collate' => 'utf8_unicode_ci',
         'charset' => 'utf8',
      )
    );
  }
}
