<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision30_CreateSkinConfig extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'skin_config', array(
      'id' => array(
        'type' => 'integer',
        'primary' => '1',
        'autoincrement' => '1',
        'comment' => 'Serial number',
        'length' => '4',
      ),
      'plugin' => array(
        'type' => 'string',
        'default' => '',
        'notnull' => '1',
        'comment' => 'Plugin name',
        'length' => '64',
      ),
      'name' => array(
        'type' => 'string',
        'default' => '',
        'notnull' => '1',
        'comment' => 'Configuration name',
        'length' => '64',
      ),
      'value' => array(
        'type' => 'string',
        'comment' => 'Configuration value',
        'length' => '',
      ),
      'created_at' => array(
        'notnull' => '1',
        'type' => 'timestamp',
        'length' => '25',
      ),
      'updated_at' => array(
        'notnull' => '1',
        'type' => 'timestamp',
        'length' => '25',
      )), array(
        'type' => 'INNODB',
        'indexes' => array(
          'plugin_name_UNIQUE' => array('fields' => array(
            0 => 'plugin',
            1 => 'name',
          ), 'type' => 'unique'),
        ), 'primary' => array( 0 => 'id'),
        'collate' => 'utf8_unicode_ci',
        'charset' => 'utf8',
    ));
  }
}
