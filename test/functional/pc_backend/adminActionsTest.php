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

  ->info('/admin/addUser - CSRF')
  ->post('/admin/addUser', array())
  ->checkCSRF()

  ->info('admin/editPassword - CSRF')
  ->post('admin/editPassword', array())
  ->checkCSRF()

  ->info('admin/editPassword - CSRF')
  ->post('admin/editPassword', array())
  ->checkCSRF()
;
