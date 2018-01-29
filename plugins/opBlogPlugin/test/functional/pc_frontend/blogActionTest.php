<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';
include dirname(__FILE__).'/../../bootstrap/util.php';

$test = new opTestFunctional(new sfBrowser());
$test->login('sns@example.com', 'password');

$test->get('/blog')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'index')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', '最新Blog')
  ->end()

  ->get('/blog/user')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'user')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', 'OpenPNE1さんの最新Blog')
  ->end()

  ->get('/blog/user/2')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'user')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', 'OpenPNE2さんの最新Blog')
  ->end()

  ->get('/blog/user/100')
  ->with('response')->begin()
    ->checkElement('h3', NULL)
  ->end()

  ->get('/blog/friend')
  ->with('request')->begin()
    ->isParameter('module', 'blog')
    ->isParameter('action', 'friend')
  ->end()

  ->with('response')->begin()
    ->checkElement('h3', 'マイフレンド最新Blog')
  ->end()

//--
  ->info('CSRF check')

  ->info('/member/config?category=blogUrl')
  ->post('/member/config?category=blogUrl', array())
  ->checkCSRF()

//--
  ->info('XSS check')

  ->info('/member/home')
  ->get('/member/home')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->countEscapedData(2, 'BlogRssCache', 'title', array('width' => 30))
  ->end()

  ->info('/blog')
  ->get('/blog')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('BlogRssCache', 'title')
  ->end()

  ->info('/blog/user/11')
  ->get('/blog/user/11')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('BlogRssCache', 'title')
  ->end()

  ->info('/blog/friend')
  ->get('/blog/friend')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('BlogRssCache', 'title')
  ->end()
;
