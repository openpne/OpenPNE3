<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class Revision2_AddIndexToBlogRssCache extends Doctrine_Migration_Base
{
  public function migrate($direction)
  {
    $this->index($direction, 'blog_rss_cache', 'date_INDEX_idx', array('fields' => array('date')));
  }
}
