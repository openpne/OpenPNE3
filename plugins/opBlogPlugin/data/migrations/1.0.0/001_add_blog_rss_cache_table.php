<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision1_AddBlogRssCacheTable extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->table($direction, 'blog_rss_cache',
      array(
        'id' => array('type' => 'integer', 'primary' => '1', 'autoincrement' => '1', 'length' => '4'),
        'member_id' => array('type' => 'integer', 'length' => '4'),
        'title' => array('type' => 'string'),
        'description' => array('type' => 'string'),
        'link' => array('type' => 'string'),
        'date' => array('type' => 'timestamp'),
        'created_at' => array('notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
        'updated_at' => array( 'notnull' => '1', 'type' => 'timestamp', 'length' => '25'),
      ),
      array(
        'type' => 'INNODB',
        'charset' => 'utf8',
      )
    );
  }
}
