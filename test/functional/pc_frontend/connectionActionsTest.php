<?php include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->login('sns@example.com', 'password')
  ->isStatusCode(302)

// CSRF
  ->info('/connection - CSRF')
  ->post('/connection')
  ->checkCSRF()

  ->info('/connection/2/delete - CSRF')
  ->post('/connection/2/delete')
  ->checkCSRF()

  ->info('/connection/2 - CSRF')
  ->post('/connection/2')
  ->checkCSRF()

  ->info('/connection/revoke/2 - CSRF')
  ->post('/connection/revoke/2')
  ->checkCSRF()
;
