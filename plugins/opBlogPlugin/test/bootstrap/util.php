<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

define('BEFORE_FEED_URL', dirname(__FILE__).'/../stub/01_fake_feed.xml');
define( 'AFTER_FEED_URL', dirname(__FILE__).'/../stub/02_fake_feed.xml');
define(       'FEED_URL', dirname(__FILE__).'/../stub/03_fake_feed.xml');

function setBlogUrl($memberId, $url)
{
  $memberConfig = Doctrine::getTable('MemberConfig')->findOneByMemberIdAndName($memberId, 'blog_url');
  if (!$memberConfig)
  {
    $memberConfig = new MemberConfig();
    $memberConfig->setMemberId($memberId);
    $memberConfig->setName('blog_url');
  }
  $memberConfig->setValue($url);
  $memberConfig->save();
}

function addFriend($memberId, $memberId2, $isAccessBlock = false)
{
  $memberRelationship = Doctrine::getTable('MemberRelationship')->retrieveByFromAndTo($memberId, $memberId2);
  if (!$memberRelationship)
  {
    $memberRelationship = new MemberRelationship();
    $memberRelationship->setMemberIdFrom($memberId);
    $memberRelationship->setMemberIdTo($memberId2);
  }
  $memberRelationship->setFriend(true);
  $memberRelationship->setIsAccessBlock($isAccessBlock);
  $memberRelationship->save();
}
