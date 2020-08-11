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
  ->info('/monitoring/editImage - CSRF')
  ->todo('checkCSRF')

  ->info('/monitoring/deleteImage/id/1 - CSRF')
  ->post('/monitoring/deleteImage/id/1')
  ->checkCSRF()

  ->info('/monitoring/deleteFile/id/3 - CSRF')
  ->post('/monitoring/deleteFile/id/3')
  ->checkCSRF()

// XSS
  ->info('/monitoring/imageList - XSS')
  ->get('/monitoring/imageList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('File', 'name')
  ->end()

  ->info('/monitoring/deleteImage/id/1055 - XSS')
  ->get('/monitoring/deleteImage/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('File', 'name')
  ->end()

  ->info('/monitoring/fileList - XSS')
  ->get('/monitoring/fileList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('File', 'name2')
    ->isAllEscapedData('File', 'original_filename')
  ->end()

  ->info('/monitoring/deleteFile/id/1056 - XSS')
  ->get('/monitoring/deleteFile/id/1056')
  ->with('html_escape')->begin()
    ->isAllEscapedData('File', 'name2')
    ->isAllEscapedData('File', 'original_filename')
  ->end()
;
