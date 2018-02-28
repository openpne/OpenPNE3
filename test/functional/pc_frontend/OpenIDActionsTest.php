<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser());

$browser

// XSS
  ->login('html1@example.com', 'password')

  ->info('/OpenID/index - XSS')
  ->get('/OpenID/list')
  ->todo('html_escape')

// CSRF
  ->info('/OpenID/unsetPermission - CSRF')
  ->post('/OpenID/unsetPermission/id/2')
  ->todo('checkCSRF')
;
