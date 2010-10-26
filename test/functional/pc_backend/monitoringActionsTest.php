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

  ->info('monitoring/editImage - CSRF')
  ->post('monitoring/editImage', array())
  ->checkCSRF()

  ->info('monitoring/deleteImage/id/1 - CSRF')
  ->post('monitoring/deleteImage/id/1', array())
  ->checkCSRF()
;
