<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));

$browser->login('sns5@example.com', 'password');
$browser
  ->info('/friend/showImage?id=*')
  ->get('/friend/showImage?id=1')
    ->info('4-1. Member E cannot view the profile image page of Member A (Access blocked)')
    ->checkDispatch('friend', 'showImage')
    ->followRedirect()
    ->checkDispatch('default', 'error')
  ->get('/friend/showImage?id=2')
    ->info('4-2. Member E can view the profile image page of Member B (Normal behavior)')
    ->checkDispatch('friend', 'showImage')
    ->isStatusCode(200)
    ->with('response')
      ->checkElement('#memberImagesBox img', 3)
;

$browser
  ->info('Login')
  ->login('sns@example.com', 'password')
  ->isStatusCode(302)

// CSRF
  ->info('/friend/link/id/6 - CSRF')
  ->post('/friend/link/id/6')
  ->checkCSRF()

  ->info('/friend/unlink/4 - CSRF')
  ->post('/friend/unlink/4')
  ->checkCSRF()

// XSS
  ->info('/friend/list - XSS')
  ->get('/friend/list')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/friend/link/id/1056 - XSS')
  ->get('/friend/link/id/1056')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/friend/unlink/1055 - XSS')
  ->get('/friend/unlink/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/friend/manage - XSS')
  ->get('/friend/manage')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/friend/showImage/1055 - XSS')
  ->get('/friend/showImage/1055')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->login('html1@example.com', 'password')
  ->isStatusCode(302)

  ->info('/friend/showActivity - XSS')
  ->get('/friend/showActivity')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
  ->end()

  ->info('/ components - XSS')
  ->get('/')
  ->with('html_escape')->begin()
    ->isAllEscapedData('Member', 'name')
    ->isAllEscapedData('ActivityData', 'body')
  ->end()
;
