<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$filePath = sfConfig::get('sf_web_dir').'/images/skin_header_logo.jpg';

$browser = new sfTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$params = array('authMailAddress' => array(
  'mail_address' => 'sns@example.com',
  'password'     => 'password',
));
$browser
  ->info('1. When an user tries to post more than 4 photos, he gets an error message. (ref. #3544)')

  ->info('1st Post')
  ->post('/member/login/authMode/MailAddress', $params)
  ->get('member/configImage')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/jpeg'),
  )))
  ->isStatusCode(302)
  ->followRedirect()

  ->info('2nd Post')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/jpeg'),
  )))
  ->isStatusCode(302)
  ->followRedirect()

  ->info('3rd Post')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/jpeg'),
  )))
  ->isStatusCode(302)
  ->followRedirect()

  ->info('4th Post')
  ->click('アップロードする', array('member_image' => array(
    'file' => array('name' => $filePath, 'type' => 'image/jpeg'),
  )))
  ->isStatusCode(200)
  ->with('response')->begin()
  ->checkElement('#flashError td:contains("これ以上画像を追加できません。")', true)
  ->end()
;
