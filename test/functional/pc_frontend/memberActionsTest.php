<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$filePath = sfConfig::get('sf_web_dir').'/images/test.png';
$fileParams = array('member_image' => array('file' => array('name' => $filePath, 'type' => 'image/png')));

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
echo $browser
  ->info('1. When an user tries to post more than 4 photos, he gets an error message. (ref. #3544)')
  ->info('1st Post')
  ->login('sns@example.com', 'password')

  ->get('/member/configImage')
  ->click('アップロードする', $fileParams)
  ->isStatusCode(302)
  ->followRedirect()

  ->info('2nd Post')
  ->click('アップロードする', $fileParams)
  ->isStatusCode(302)
  ->followRedirect()

  ->info('3rd Post')
  ->click('アップロードする', $fileParams)
  ->isStatusCode(302)
  ->followRedirect()

  ->info('4th Post')
  ->click('アップロードする', $fileParams)
  ->followRedirect()
  ->with('response')->begin()
    ->checkElement('#flashError td:contains("これ以上画像を追加できません。")', true)
  ->end()

  ->info('/member/image/config - CSRF')
  ->post('/member/image/config', $fileParams)
  ->followRedirect()
  ->with('response')->begin()
    ->checkElement('#flashError td:contains("これ以上画像を追加できません。")', false)
  ->end()

  ->info('/member/deleteImage/member_image_id/1 - CSRF')
  ->post('/member/deleteImage/member_image_id/1', array())
  ->checkCSRF()

  ->info('/member/edit/profile - CSRF')
  ->post('/member/edit/profile', array())
  ->checkCSRF()

  ->info('/invite - CSRF')
  ->post('/invite', array())
  ->checkCSRF()

  ->info('/leave - CSRF')
  ->post('/leave', array())
  ->checkCSRF()

  ->info('/member/config?category=secretQuestion - CSRF')
  ->post('/member/config?category=secretQuestion', array())
  ->checkCSRF()

  ->info('/member/config?category=publicFlag - CSRF')
  ->post('/member/config?category=publicFlag', array())
  ->checkCSRF()

  ->info('/member/config?category=pcAddress - CSRF')
  ->post('/member/config?category=pcAddress', array())
  ->checkCSRF()

  ->info('/member/config?category=mobileAddress - CSRF')
  ->post('/member/config?category=mobileAddress', array())
  ->checkCSRF()

  ->info('/member/config?category=password - CSRF')
  ->post('/member/config?category=password', array())
  ->checkCSRF()

  ->info('/member/config?category=accessBlock - CSRF')
  ->post('/member/config?category=accessBlock', array())
  ->checkCSRF()

  ->info('/member/config?category=mail - CSRF')
  ->post('/member/config?category=mail', array())
  ->checkCSRF()

  ->info('/member/config?category=language - CSRF')
  ->post('/member/config?category=language', array())
  ->checkCSRF()

  ->info('/member/logout - CSRF')
  ->post('/member/logout', array())
  ->checkCSRF()
;
