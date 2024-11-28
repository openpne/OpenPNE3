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
  ->info('/community/categoryList - CSRF')
  ->post('/community/categoryList')
  ->checkCSRF()

  ->info('/community/categoryEdit/id/1 - CSRF')
  ->post('/community/categoryEdit/id/1')
  ->todo('checkCSRF')

  ->info('/community/categoryDelete/id/1 - CSRF')
  ->post('/community/categoryDelete/id/1')
  ->checkCSRF()

  ->info('/community/addDefaultCommunity/id/1 - CSRF')
  ->post('/community/addDefaultCommunity/id/1')
  ->todo('checkCSRF')

  ->info('/community/removeDefaultCommunity/id/1 - CSRF')
  ->post('/community/removeDefaultCommunity/id/1')
  ->checkCSRF()

  ->info('/community/addAllMember/id/1 - CSRF')
  ->post('/community/addAllMember/id/1')
  ->checkCSRF()

  ->info('/community/delete/id/1 - CSRF')
  ->post('/community/delete/id/1')
  ->checkCSRF()

// XSS
  ->info('/community/list - XSS')
  ->get('/community/list')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/community/defaultCommunityList - XSS')
  ->get('/community/defaultCommunityList')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name2')
  ->end()

  ->info('/community/addDefaultCommunity/id/1055 - XSS')
  ->get('/community/addDefaultCommunity/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/community/removeDefaultCommunity/id/1056 - XSS')
  ->get('/community/removeDefaultCommunity/id/1056')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name2')
  ->end()

  ->info('/community/addAllMember/id/1055 - XSS')
  ->get('/community/addAllMember/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()

  ->info('/community/delete/id/1055 - XSS')
  ->get('/community/delete/id/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('Community', 'name')
  ->end()
;
