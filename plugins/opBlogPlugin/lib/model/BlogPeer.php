<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class BlogPeer
{
  public static function getFeedByUrl($url)
  {
    if (is_null($url))
    {
      return false;
    }
    $old = umask(0);
    $feed = new SimplePie();
    $dir = sfConfig::get('sf_app_cache_dir') . '/plugins';
    if (!file_exists($dir))
    {
      if (!@mkdir($dir, 0777, true))
      {
        throw new Exception(sprintf('Could not create directory "%s"', $dir));
      }
    }
    $dir .= '/opBlogPlugin';
    if (!file_exists($dir))
    {
      if (!@mkdir($dir, 0777, true))
      {
        throw new Exception(sprintf('Could not create directory "%s"', $dir));
      }
    }
    umask($old);
    $feed->set_cache_location($dir);
    $feed->set_feed_url($url);
    if(!@$feed->init())
    {
      return false;
    }
    $feed->handle_content_type();

    return $feed;
  }

  public static function getBlogListByMemberId($member_id, &$list)
  {
    $member = MemberPeer::retrieveByPk($member_id);
    if (!$member || !$member->getIsActive())
    {
      return;
    }

    $feed = self::getFeedByUrl($member->getConfig('blog_url'));
    if (!$feed)
    {
      return;
    }

    foreach ($feed->get_items() as $item)
    {
      $list[] = self::setBlog(
        strtotime(@$item->get_date()),
        @$item->get_title(),
        @$item->get_link(),
        $member->getName()
      ); 
    }
  }

  protected static function setBlog($date, $title, $link, $name)
  {
    return array(
      'date' => $date,
      'title' => htmlspecialchars_decode($title),
      'link_to_external' => $link,
      'name' => $name
    );
  }

  public static function sortBlogList(&$list, $size = 20)
  {
    foreach ($list as $aKey => $a)
    {
      $pickKey = $aKey;
      for ($bKey = $aKey + 1; $bKey < count($list); $bKey++)
      {
        if ($list[$bKey]['date'] > $list[$pickKey]['date'])
        {
          $pickKey = $bKey;
        }
      }
      if ($aKey != $pickKey)
      {
        $list[$aKey] = $list[$pickKey];
        $list[$pickKey] = $a;
      }
    }
    return array_splice($list, 0, $size);
  }

  public static function limitBlogTitle(&$list)
  {
    foreach($list as &$res)
    {
      $res['title'] = mb_strcut($res['title'], 0, 30);
    }
  }

  public static function getBlogListOfFriend($member_id, $size = 20, $limitTitle = false)
  {
    $c = new Criteria();
    $c->add(MemberRelationshipPeer::MEMBER_ID_TO, $member_id);
    $c->add(MemberRelationshipPeer::IS_FRIEND, true);
    $c->addSelectColumn(MemberRelationshipPeer::MEMBER_ID_FROM);
    $stmt = MemberRelationshipPeer::doSelectStmt($c);
    $list = array();
    while($id = $stmt->fetchColumn(0))
    {
      self::getBlogListByMemberId($id, $list);
    }
    $list = self::sortBlogList($list, $size);
    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }
    
    return $list;
  }

  public static function getBlogListOfMember($member_id, $size = 20, $limitTitle = false)
  {
    $list = array();
    self::getBlogListByMemberId($member_id, $list);
    $list = self::sortBlogList($list, $size);
    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }
    
    return $list;
  }

  public static function getBlogListOfAllMember($size = 20, $limitTitle = false)
  {
    $c = new Criteria();
    $c->addSelectColumn(MemberPeer::ID);
    $stmt = MemberPeer::doSelectStmt($c);
    $list = array();
    while($id = $stmt->fetchColumn(0))
    {
      self::getBlogListByMemberId($id, $list);
    }
    $list = self::sortBlogList($list, $size);
    if ($limitTitle)
    {
      self::limitBlogTitle($list);
    }
    
    return $list;
  }
}
