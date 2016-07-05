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

// CSRF
  ->info('/member/image/config - CSRF')
  ->post('/member/image/config', $fileParams)
  ->followRedirect()
  ->with('response')->begin()
    ->checkElement('#flashError td:contains("これ以上画像を追加できません。")', false)
  ->end()

  ->info('/member/deleteImage/member_image_id/1 - CSRF')
  ->post('/member/deleteImage/member_image_id/1')
  ->checkCSRF()

  ->info('/member/edit/profile - CSRF')
  ->post('/member/edit/profile')
  ->checkCSRF()

  ->info('/invite - CSRF')
  ->post('/invite')
  ->checkCSRF()

  ->info('/leave - CSRF')
  ->post('/leave')
  ->todo('checkCSRF')

  ->info('/member/config?category=secretQuestion - CSRF')
  ->post('/member/config?category=secretQuestion')
  ->checkCSRF()

  ->info('/member/config?category=publicFlag - CSRF')
  ->post('/member/config?category=publicFlag')
  ->checkCSRF()

  ->info('/member/config?category=pcAddress - CSRF')
  ->post('/member/config?category=pcAddress')
  ->checkCSRF()

  ->info('/member/config?category=mobileAddress - CSRF')
  ->post('/member/config?category=mobileAddress')
  ->checkCSRF()

  ->info('/member/config?category=password - CSRF')
  ->post('/member/config?category=password')
  ->checkCSRF()

  ->info('/member/config?category=accessBlock - CSRF')
  ->todo('checkCSRF')

  ->info('/member/config?category=mail - CSRF')
  ->post('/member/config?category=mail')
  ->checkCSRF()

  ->info('/member/config?category=language - CSRF')
  ->post('/member/config?category=language')
  ->todo('checkCSRF')

  ->info('/member/updateActivity - CSRF')
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->post('/member/updateActivity')
  ->isStatusCode(500)

  ->info('/member/changeMainImage/member_image_id/2/ - CSRF')
  ->post('/member/changeMainImage/member_image_id/2/')
  ->checkCSRF()

  ->info('/member/deleteActivity/id/1 - CSRF')
  ->post('/member/deleteActivity/id/1')
  ->checkCSRF()

  ->info('/member/editProfile - CSRF')
  ->post('/member/editProfile')
  ->checkCSRF()

  ->info('/member/registerMobileToRegisterEnd - CSRF')
  ->post('/member/registerMobileToRegisterEnd', array('member_config' => array()))
  ->checkCSRF()

// XSS
  ->login('html1@example.com', 'password')

  ->info('/ components - XSS')
  ->get('/')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('ActivityData', 'body')
  ->end()

  ->info('/member/showActivity - XSS')
  ->get('/member/showActivity')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('ActivityData', 'body')
  ->end()

  ->info('/member/showAllMemberActivity - XSS')
  ->get('/member/showAllMemberActivity')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('ActivityData', 'body')
  ->end()

  ->info('/member/search - XSS')
  ->get('/member/search', array('member' => array('name' => 'Member.name')))
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/member/deleteActivity/id/1055 - CSRF')
  ->get('/member/deleteActivity/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('ActivityData', 'body')
  ->end()

  ->info('/member/profile - XSS')
  ->get('/member/profile')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('MemberProfile', 'value')
    ->isAllEscapedData('ProfileOption', 'value')
  ->end()

  ->info('/member/home - XSS')
  ->get('/member/home')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/ rss gadget - XSS')
  ->get('/')
  ->todo('html_escape')
;
