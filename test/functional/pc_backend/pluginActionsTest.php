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

  ->info('/plugin/list/type/application - CSRF')
  ->post('/plugin/list/type/application', array())
  ->followRedirect()
  ->todo('checkCSRF')

  ->info('/plugin/list/type/auth - CSRF')
  ->post('/plugin/list/type/auth', array())
  ->followRedirect()
  ->todo('checkCSRF')

  ->info('/plugin/list/type/skin - CSRF')
  ->post('/plugin/list/type/skin', array())
  ->followRedirect()
  ->todo('checkCSRF')
;
