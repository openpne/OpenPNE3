<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$filePath = sfConfig::get('sf_web_dir').'/images/dummy.gif';

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->login('sns@example.com', 'password')

  ->info('member/configImage')
  ->info('1. When an user tries to post more than 4 photos, he gets an error message. (ref. #3544)')

  ->info('1st Post')
  ->get('member/configImage')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/gif'),
  )))
  ->with('response')->begin()
    ->isStatusCode(302)
  ->end()
  ->followRedirect()

  ->info('2nd Post')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/gif'),
  )))
  ->with('response')->begin()
    ->isStatusCode(302)
  ->end()
  ->followRedirect()

  ->info('3rd Post')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/gif'),
  )))
  ->with('response')->begin()
    ->isStatusCode(302)
  ->end()
  ->followRedirect()

  ->info('4th Post')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/gif'),
  )))
  ->with('response')->begin()
    ->isStatusCode(302)
  ->end()
  ->followRedirect()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#flashError td:contains("これ以上画像を追加できません。")', true)
  ->end()

  ->info('member/profile')
  ->get('member/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Profile', 'caption')
    ->isAllEscapedData('MemberProfile', 'value')
  ->end()
;
