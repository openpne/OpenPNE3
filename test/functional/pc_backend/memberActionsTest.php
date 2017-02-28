<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->get('/')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))

// CSRF
  ->info('/member/delete/id/2 - CSRF')
  ->post('/member/delete/id/2')
  ->checkCSRF()

  ->info('/member/reject/id/2 - CSRF')
  ->post('/member/reject/id/2')
  ->checkCSRF()

  ->info('/member/reissuePassword/id/2 - CSRF')
  ->post('/member/reissuePassword/id/2')
  ->checkCSRF()

  ->info('/member/blacklist/uid - CSRF')
  ->post('/member/blacklist/uid')
  ->checkCSRF()

  ->info('/member/blacklistDelete/id/1 - CSRF')
  ->post('/member/blacklistDelete/id/1')
  ->checkCSRF()

  ->info('/member/invite - CSRF')
  ->post('/member/invite')
  ->checkCSRF()

// XSS
  ->info('/member/list - XSS')
  ->get('/member/list')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('MemberProfile', 'value')
  ->end()

  ->info('/member/delete/id/1055 - XSS')
  ->todo('html_escape')

  ->info('/member/reissuePassword/id/1055 - XSS')
  ->get('/member/reissuePassword/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/member/reject/id/1055 - XSS')
  ->get('/member/reject/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()
;
