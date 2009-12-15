<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision32_CreateTableCommunityMemberPosition extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'community_member_position', array(
      'id' => array(
         'type' => 'integer',
         'primary' => true,
         'autoincrement' => true,
         'comment' => 'Serial number',
         'length' => '4',
       ),
       'community_id' => array(
         'type' => 'integer',
         'notnull' => true,
         'comment' => 'Community id',
         'length' => '4',
       ),
       'member_id' => array(
         'type' => 'integer',
         'notnull' => true,
         'comment' => 'Member id',
         'length' => '4',
       ),
       'community_member_id' => array(
         'type' => 'integer',
         'notnull' => true,
         'comment' => 'Community Member id',
         'length' => '4',
       ),
       'name' => array(
         'type' => 'string',
         'notnull' => true,
         'comment' => 'Member\'\'s position name in this community',
         'length' => '32',
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
           'name_UNIQUE' => array('fields' => array(
             0 => 'community_member_id',
             1 => 'name'
           ), 'type' => 'unique'),
         ), 'primary' => array(0 => 'id'),
        'collate' => 'utf8_unicode_ci',
        'charset' => 'utf8',
       )
    );
  }
}
