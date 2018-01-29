<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * @package     opBlogPlugin
 * @subpackage  migrate
 * @author      Kimura Youichi <kim.upsilon@bucyou.net>
 */
class Revision4_AddBlogRssCacheOndeleteCascade extends opMigration
{
  public function up()
  {
    $this->dropForeignKey('blog_rss_cache', 'blog_rss_cache_member_id_member_id');

    $this->createForeignKey('blog_rss_cache', 'blog_rss_cache_member_id_member_id', array(
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
      'onDelete'     => 'cascade',
    ));
  }

  public function down()
  {
    $this->dropForeignKey('blog_rss_cache', 'blog_rss_cache_member_id_member_id');

    $this->createForeignKey('blog_rss_cache', 'blog_rss_cache_member_id_member_id', array(
      'local'        => 'member_id',
      'foreign'      => 'id',
      'foreignTable' => 'member',
    ));
  }
}
