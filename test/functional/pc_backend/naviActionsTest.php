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

  ->info('/navigation/edit/app/pc - CSRF')
  ->post('/navigation/edit/app/pc')
  ->todo('checkCSRF')

  ->info('/navigation/edit/app/mobile - CSRF')
  ->post('/navigation/edit/app/mobile')
  ->todo('checkCSRF')

  ->info('/navigation/edit/app/backEnd - CSRF')
  ->post('/navigation/edit/app/backend')
  ->todo('checkCSRF')

  ->info('/navigation/sort - CSRF')
  ->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest')
  ->post('/navigation/sort')
  ->checkCSRF()
;
