<?php include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->login('sns@example.com', 'password')
  ->isStatusCode(302)

// XSS
  ->info('/connection/1055 - XSS')
  ->get('/connection/1055')
  ->todo('html_escape')

  ->info('/connection/list - XSS')
  ->get('/connection/list')
  ->todo('html_escape')

// CSRF
  ->info('/connection - CSRF')
  ->post('/connection')
  ->todo('checkCSRF')

  ->info('/connection/2/delete - CSRF')
  ->post('/connection/2/delete')
  ->todo('checkCSRF')

  ->info('/connection/2 - CSRF')
  ->post('/connection/2')
  ->todo('checkCSRF')

  ->info('/connection/revoke/2 - CSRF')
  ->post('/connection/revoke/2')
  ->todo('checkCSRF')
;
