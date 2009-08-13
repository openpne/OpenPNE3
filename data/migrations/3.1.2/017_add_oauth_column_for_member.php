<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision17_addOAuthColumnForMember extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'o_auth_member_token', array(
      'id' => array(
        'type' => 'integer',
        'primary' => '1',
        'autoincrement' => '1',
        'comment' => 'Serial number',
        'length' => '4',
      ),
      'oauth_consumer_id' => array(
        'type' => 'integer',
        'notnull' => '1',
        'comment' => 'OAuth Consumer id',
        'length' => '4',
      ),
      'key_string' => array(
        'type' => 'string',
        'default' => '',
        'notnull' => '1',
        'comment' => 'Key string of this token',
        'length' => '16',
      ),
      'secret' => array(
        'type' => 'string',
        'default' => '',
         'notnull' => '1',
        'comment' => 'Secret string of this token',
        'length' => '32',
      ),
      'type' => array(
        'type' => 'enum',
        'values' => array(
          0 => 'request',
          1 => 'access',
        ),
        'default' => 'request',
        'comment' => 'Token type',
        'length' => '',
      ),
      'is_active' => array(
        'type' => 'boolean',
        'default' => '1',
        'notnull' => '1',
        'comment' => 'Activation flag',
        'length' => '25',
      ),
      'callback_url' => array(
        'type' => 'string',
        'comment' => 'Callback url',
        'length' => '2147483647',
      ),
      'verifier' => array(
        'type' => 'string',
        'comment' => 'Token verifier',
        'length' => '2147483647',
      ),
      'member_id' => array(
        'type' => 'integer',
        'notnull' => '1',
        'comment' => 'Member id',
        'length' => '4',
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
      ),
      ),
      array(
        'type' => 'INNODB',
        'indexes' => array(
          'key_secret_UNIQUE' => array(
            'fields' => array(
              0 => 'key_string',
              1 => 'secret',
            ),
            'type' => 'unique',
          ),
        ),
        'primary' => array(
          0 => 'id',
        ),
        'collate' => 'utf8_unicode_ci',
        'charset' => 'utf8',
    ));

    $this->column($direction, 'oauth_consumer', 'member_id', 'integer', '4', array(
      'notnull' => '',
      'comment' => 'Member id',
    ));
  }
}
