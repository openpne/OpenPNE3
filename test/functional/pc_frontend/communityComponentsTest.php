<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('0. Login')
  ->get('/member/login')
  ->click('ログイン', array('authMailAddress' => array(
    'mail_address' => 'sns@example.com',
    'password'     => 'password',
  )))
  ->isStatusCode(302)

  ->info('1. Create a community.')
  ->get('community/edit')
  ->click('送信', array('community' => array(
    'name'   => 'test',
    'config' => array('public_flag' => 'public', 'topic_authority' => 'public', 'description' => 'test')
  )))
  ->isStatusCode(302)

  ->info('2. Community list is shown on the member\'s home.')
  ->get('member/home')
;

$selector = new sfDomCssSelector($browser->getResponseDom());
$list = $selector->getElements('#Left h3:contains("コミュニティリスト")');
$browser->test()->ok($list, 'a community list gadget exists');

$photoLink = '';
$textLink = '';

$xpath = new DOMXPath($browser->getResponseDom());
foreach ($xpath->query('../../table/tr', $list[0]) as $item)
{
  if ($item->getAttribute('class') === 'photo')
  {
    $photoLink = $item->firstChild->getElementsByTagName('a')->item(0)->getAttribute('href');
  }
  elseif ($item->getAttribute('class') === 'text')
  {
    $textLink = $item->firstChild->getElementsByTagName('a')->item(0)->getAttribute('href');
  }
}

$browser->test()->ok($photoLink, 'photo link exists');
$browser->test()->ok($textLink, 'text link exists');

$browser
  ->info('links in a community list is a valid (ref. #3546)')
  ->info('photo link is a valid')
  ->get($photoLink)
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
  ->info('text link is a valid')
  ->get($textLink)
  ->isStatusCode(200)
  ->with('request')->begin()
    ->isParameter('module', 'community')
    ->isParameter('action', 'home')
  ->end()
;
