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

  ->info('/profile/edit - CSRF')
  ->post('/profile/edit', array())
  ->checkCSRF()

  ->info('/profile/delete/id/1 - CSRF')
  ->post('/profile/delete/id/1', array())
  ->checkCSRF()
;
