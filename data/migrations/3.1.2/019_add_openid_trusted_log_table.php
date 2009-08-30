<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision19_addOpenIDTrustedLogTable extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'openid_trust_log', array(
      'id' => array(
        'type'          => 'integer',
        'primary'       => '1',
        'autoincrement' => '1',
        'comment'       => 'Serial number',
        'length'        => '4',
      ),
      'member_id' => array(
        'type'    => 'integer',
        'notnull' => '',
        'comment' => 'Member id',
        'length'  => '4',
      ),
      'uri' => array(
        'type'    => 'string',
        'comment' => 'URI for RP',
        'length'  => '',
      ),
      'uri_key' => array(
        'type' => 'string',
        'comment' => 'Hashed URI for RP',
        'length' => '32',
      ),
      'is_permanent' => array(
        'type'    => 'boolean',
        'comment' => 'A permanent flag',
        'length'  => '25',
      ),
      'created_at' => array(
        'notnull' => '1',
        'type'    => 'timestamp',
        'length ' => '25',
      ),
      'updated_at' => array(
        'notnull' => '1',
        'type'    => 'timestamp',
        'length'  => '25',
      )),
      array(
      'type' => 'INNODB',
      'indexes' => array(
        'uri_key_INDEX' => array(
          'fields' => array(0 => 'uri_key'),
        ),
      ),
      'primary' => array(0 => 'id'),
      'collate' => 'utf8_unicode_ci',
      'charset' => 'utf8',
    ));
  }
}
