<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class creating311Tables extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $conn = Doctrine_Manager::connection();
    $list = $conn->import->listTables();

    if ($direction == 'up' && in_array('banner', $list))
    {
      return null;
    }

    $this->table($direction, 'banner',
      array(
        'id'          => array('type' => 'integer', 'primary' => '1', 'autoincrement' => '1', 'length' => '4'),
        'name'        => array('type' => 'string', 'default' => '', 'notnull' => '1', 'length' => '64'),
        'html'        => array('type' => 'string', 'length' => ''),
        'is_use_html' => array('type' => 'boolean', 'default' => '0', 'notnull' => '1', 'length' => '25'),
      ),
      array(
        'indexes' => array(
          'name_UNIQUE' => array('fields' => array(0 => 'name'), 'type' => 'unique'),
        ),
        'primary' => array(0 => 'id'),
        'charset' => 'utf8',
    ));

    $this->table($direction, 'banner_translation',
      array(
        'id'      => array('type' => 'integer', 'length' => '4', 'primary' => '1'),
        'caption' => array('type' => 'string', 'notnull' => '1', 'length' => ''),
        'lang'    => array('fixed' => '1', 'primary' => '1', 'type' => 'string', 'length' => '5'),
      ),
      array(
        'primary' => array(0 => 'id', 1 => 'lang'),
        'charset' => 'utf8',
    ));

    $this->table($direction, 'banner_image',
      array(
        'id'         => array('type' => 'integer', 'length' => '4', 'primary' => '1'),
        'file_id'    => array('type' => 'integer', 'notnull' => '1', 'length' => '4'),
        'url'        => array('type' => 'string', 'length' => ''),
        'name'       => array('type' => 'string', 'length' => '64'),
        'created_at' => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
        'updated_at' => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      ),
      array(
        'primary' => array(0 => 'id'),
        'charset' => 'utf8',
    ));

    $this->table($direction, 'banner_use_image',
      array(
        'id'              => array('type' => 'integer', 'length' => '4', 'primary' => '1'),
        'banner_id'       => array('type' => 'integer', 'notnull' => '1', 'length' => '4'),
        'banner_image_id' => array('type' => 'integer', 'notnull' => '1', 'length' => '4'),
        'created_at'      => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
        'updated_at'      => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      ),
      array(
        'primary' => array(0 => 'id'),
        'charset' => 'utf8',
    ));

    $this->table($direction, 'o_auth_admin_token',
      array(
        'id'                => array('type' => 'integer', 'length' => '4', 'primary' => '1'),
        'oauth_consumer_id' => array('type' => 'integer', 'notnull' => '1', 'length' => '4'),
        'key_string'        => array('type' => 'string', 'default' => '', 'notnull' => '1', 'length' => '16'),
        'secret'            => array('type' => 'string', 'default' => '', 'notnull' => '1', 'length' => '32'),
        'type'              => array('type' => 'enum', 'values' => array(0 => 'request', 1 => 'access'), 'default' => 'request', 'length' => ''),
        'expires_at'        => array('type' => 'timestamp', 'length' => '25'),
        'is_active'         => array('type' => 'boolean', 'default' => '1', 'notnull' => '1', 'length' => '25'),
        'created_at'        => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
        'updated_at'        => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      ),
      array(
        'indexes' => array(
          'key_secret_UNIQUE' => array('fields' => array(0 => 'key_string', 1 => 'secret'), 'type' => 'unique'),
         ),
         'primary' => array(0 => 'id'),
         'charset' => 'utf8',
    ));

    $this->table($direction, 'oauth_consumer',
      array(
        'id'                => array('type' => 'integer', 'length' => '4', 'primary' => '1'),
        'name'              => array('type' => 'string', 'default' => '', 'notnull' => '1', 'length' => '64'),
        'description'       => array('type' => 'string', 'length' => ''),
        'type'              => array('type' => 'enum', 'values' => array(0 => 'browser', 1 => 'client'), 'default' => 'browser', 'length' => ''),
        'key_string'        => array('type' => 'string', 'default' => '', 'notnull' => '1', 'length' => '16'),
        'secret'            => array('type' => 'string', 'default' => '', 'notnull' => '1', 'length' => '32'),
        'file_id'           => array('type' => 'integer', 'length' => '4'),
        'created_at'        => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
        'updated_at'        => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      ),
      array(
        'indexes' => array(
          'key_secret_UNIQUE' => array('fields' => array(0 => 'key_string', 1 => 'secret'), 'type' => 'unique'),
         ),
         'primary' => array(0 => 'id'),
         'charset' => 'utf8',
    ));

    $this->table($direction, 'session',
      array(
        'id'                => array('type' => 'integer', 'length' => '4', 'primary' => '1'),
        'data' => array('type' => 'string', 'length' => ''),
        'time' => array('type' => 'string', 'length' => ''),
      ),
      array(
        'primary' => array(0 => 'id'),
        'charset' => 'utf8',
    ));
  }
}
