<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * openpneUpdateBlogRssCacheTask
 *
 * @package    OpenPNE
 * @subpackage task
 * @author     Masawa Nagasawa <nagasawa@tejimaya.com>
 */
class openpneUpdateBlogRssCacheTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'openpne';
    $this->name             = 'update-blog-rss-cache';
    $this->briefDescription = 'Updating blog rss cache';
    $this->detailedDescription = <<<EOF
The [openpne:update-blog-rss-cache|INFO] task does things.
Call it with:

  [php symfony openpne:update-blog-rss-cache|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $this->openDatabaseConnection();
    opDoctrineRecord::setDefaultCulture(sfConfig::get('default_culture', 'ja_JP'));
    sfContext::createInstance($this->createConfiguration('pc_frontend', 'prod'), 'pc_frontend');

    $next = Doctrine::getTable('SnsConfig')->get('next_update_blog_rss_cache', 0);
    $size = sfConfig::get('app_update_blog_rss_cache_limit', 0);
    $last = Doctrine::getTable('BlogRssCache')->countFeedUrl();

    $next += Doctrine::getTable('BlogRssCache')->update($next, $size);

    if ($next >= $last)
    {
      $next = 0;
    }

    Doctrine::getTable('SnsConfig')->set('next_update_blog_rss_cache', $next);
  }

  protected function openDatabaseConnection()
  {
    new sfDatabaseManager($this->configuration);
  }
}
