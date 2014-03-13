<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class opBlogPlugin
{
  static protected $transTable = null;

  static function createSimplePie($url)
  {
    $feed = new SimplePie();

    $dir = sfConfig::get('sf_app_cache_dir').'/'.sfConfig::get('app_blog_rss_cache_dir');
    if (!file_exists($dir))
    {
      $old = umask(0);
      if (!@mkdir($dir, 0777, true))
      {
        throw new Exception(sprintf('Could not create directory "%s"', $dir));
      }
      umask($old);
    }
    $feed->set_feed_url($url);
    $feed->set_cache_location($dir);
    if(!@$feed->init())
    {
      return false;
    }

    return $feed;
  }

  static function getFeedByUrl($url)
  {
    $feed = self::createSimplePie($url);
    if (!$feed)
    {
      return array();
    }

    $result = array();
    foreach ($feed->get_items() as $item)
    {
      $title = self::unescape($item->get_title());
      $description = self::unescape($item->get_description());
      $link = self::unescape($item->get_link());
      $date = @$item->get_date('Y-m-d H:i:s');

      $result[] = array(
        'title' => $title,
        'description' => $description,
        'link' => $link,
        'date' => $date
      );
    }

    return $result;
  }

  static function unescape($string)
  {
    if (!self::$transTable)
    {
      self::$transTable = array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES));
      self::$transTable['&#039;'] = "'";
    }

    return strtr($string, self::$transTable);
  }
}
