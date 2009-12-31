<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision36_CreateTableForActivity extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'activity_data', array(
      'id' => array(
        'type' => 'integer',
        'primary' => 1,
        'autoincrement' => 1,
        'comment' => 'Serial number',
        'length' => 4,
      ),
      'member_id' => array(
        'type' => 'integer',
        'notnull' => true,
        'comment' => 'Member id',
        'length' => 4
      ),
      'in_reply_to_activity_id' => array(
        'type' => 'integer',
        'comment' => 'Activity data id is in reply to',
        'length' => 4
      ),
      'body' => array(
        'type' => 'string',
        'notnull' => true,
        'comment' => 'Activity body',
        'length' => 140
      ),
      'uri' => array(
        'type' => 'string',
        'comment' => 'Activity URI'
      ),
      'public_flag' => array(
        'type' => 'integer',
        'notnull' => true,
        'default' => 1,
        'comment' => 'Public flag of activity',
        'length' => 1
      ),
      'is_pc' => array(
        'type' => 'boolean',
        'notnull' => true,
        'default' => 1,
        'comment' => 'Display this in PC?'
      ),
      'is_mobile' => array(
        'type' => 'boolean',
        'notnull' => true,
        'default' => 1,
        'comment' => 'Display this in Mobile?'
      ),
      'source' => array(
        'type' => 'string',
        'comment' => 'The source caption',
        'length' => 64
      ),
      'source_uri' => array(
        'type' => 'string',
        'comment' => 'The source URI',
      ),
      'foreign_table' => array(
        'type' => 'string',
        'comment' => 'Reference table name',
      ),
      'foreign_id' => array(
        'type' => 'integer',
        'comment' => 'The id of reference table',
        'length' => 8,
      ),
      'created_at' => array(
        'type' => 'timestamp',
        'length' => 25,
        'notnull' => true,
      ),
      'updated_at' => array(
        'type' => 'timestamp',
        'length' => 25,
        'notnull' => true,
      ),
    ), array(
      'type' => 'INNODB',
      'collate' => 'utf8_unicode_ci',
      'charset' => 'utf8',
      'comment' => 'Saves activities',
    ));

    $this->table($direction, 'activity_image', array(
      'id' => array(
        'type' => 'integer',
        'primary' => 1,
        'autoincrement' => 1,
        'comment' => 'Serial number',
        'length' => 4
      ),
      'activity_data_id' => array(
        'type' => 'integer',
        'notnull' => true,
        'comment' => 'Activity data id',
        'length' => 4
      ),
      'mime_type' => array(
        'type' => 'string',
        'notnull' => true,
        'comment' => 'MIME type',
        'length' => 64
      ),
      'uri' => array(
        'type' => 'string',
        'comment' => 'Image URI'
      ),
      'file_id' => array(
        'type' => 'integer',
        'comment' => 'File id',
        'length' => 4
      ),
      'created_at' => array(
        'type' => 'timestamp',
        'length' => 25,
        'notnull' => true,
      ),
      'updated_at' => array(
        'type' => 'timestamp',
        'length' => 25,
        'notnull' => true,
      ),
    ), array(
      'type' => 'INNODB',
      'collate' => 'utf8_unicode_ci',
      'charset' => 'utf8',
      'comment' => 'Saves image information of activity',
    ));
  }
}
