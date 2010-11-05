<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
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
