<?php

/**
 * @copyright 2005-2009 OpenPNE Project
 * @license   http://www.php.net/license/3_01.txt PHP License 3.01
 */

require_once 'simplepie.inc';

/**
 * This class is for fetching RSS / Atom feed
 *
 * This is ported from OpenPNE_RSS in OpenPNE2Path.
 * The opRssFetcher delegates some behaviors to SimplePie
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class opRssFetcher
{
  public $charset;

  public function __construct($charset = '')
  {
      $this->charset = $charset;
  }

  public function createSimplePieObject($rssUrl)
  {
    $feed = new SimplePie();
    if (sfConfig::get('op_http_proxy'))
    {
      $proxy = sfConfig::get('op_http_proxy');
      $feed->set_proxy($proxy);
    }

    $feed->set_feed_url($rssUrl);
    $feed->set_cache_location(sfConfig::get('sf_cache_dir'));
    if (!(@$feed->init()))
    {
      return false;
    }

    return $feed;
  }

  public function getFeedTitle($rssUrl)
  {
    if (!$feed = $this->createSimplePieObject($rssUrl))
    {
      return false;
    }

    return @$feed->get_title();
  }

  public function getFeedDescription($rssUrl)
  {
    if (!$feed = $this->createSimplePieObject($rssUrl))
    {
      return false;
    }

    return @$feed->get_description();
  }

  public function fetch($rssUrl, $isGetFeedTitle = false)
  {
    if (!$feed = $this->createSimplePieObject($rssUrl))
    {
      return false;
    }

    if (!($items = $feed->get_items()))
    {
      return false;
    }

    if ($isGetFeedTitle)
    {
      $feedTitle = @$feed->get_title();
    }
    else
    {
      $feedTitle = '';
    }

    $result = array();
    foreach ($items as $item)
    {
      $title = $item->get_title();
      $links = $item->get_links();
      $description = $item->get_description();
      $date = @$item->get_date('Y-m-d H:i:s');
      $enclosure = $item->get_enclosure();

      if (!$title)
      {
        $title = '';
      }

      if (!$description)
      {
        $description = '';
      }

      if (!$links)
      {
        $link = '';
      }
      else
      {
        $link = $links[0];
      }

      if (!$date)
      {
        $date = '';
      }

      if (!$enclosure)
      {
        $enclosure = '';
      }

      // Reverts escaped strings
      $transTable = array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES));
      $transTable['&#039;'] = "'";
      $title = strtr($title, $transTable);
      $description = strtr($description, $transTable);
      $link = strtr($link, $transTable);

      $fitem = array(
          'title' => $this->convertEncoding($title),
          'body'  => $this->convertEncoding($description),
          'link'  => $link,
          'date'  => $date,
          'enclosure' => $enclosure,
      );
      $result[] = $fitem;
    }

    if ($isGetFeedTitle && $feedTitle)
    {
      return array($feedTitle, $result);
    }

    return $result;
  }

  protected function convertEncoding($string)
  {
    if (!$this->charset)
    {
      return $string;
    }
    return mb_convert_encoding($string, $this->charset, 'UTF-8');
  }

  static public function autoDiscovery($url)
  {
    $parts = parse_url($url);
    if (empty($parts['path']))
    {
      $url .= '/';
    }

    $result = '';
    $proxy = null;
    if (sfConfig::get('op_http_proxy'))
    {
      $proxy = sfConfig::get('op_http_proxy');
    }
    $file = @new SimplePie_File($url, 10, 5, null, null, false, $proxy);
    $locator = new SimplePie_Locator($file, 10, null, 'SimplePie_File', 10, $proxy);
    $feedUrl = $locator->find();
    if (SimplePie_Misc::is_a($feedUrl, 'SimplePie_File'))
    {
      $result = $feedUrl->url;
    }

    return $result;
  }
}
