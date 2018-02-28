<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$filePath = sfConfig::get('sf_web_dir').'/images/test.png';
$fileParams = array('member_image' => array('file' => array('name' => $filePath, 'type' => 'image/png')));

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->get('/')
  ->click('ログイン', array('admin_user' => array(
    'username' => 'admin',
    'password' => 'password',
  )))

  ->info('/mail - CSRF')
  ->post('/mail', array())
  ->todo('checkCSRF')

  ->info('/mail/edit - CSRF')
  ->post('/mail/edit', array())
  ->todo('checkCSRF')
;
