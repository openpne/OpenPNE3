<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser
  ->info('Login')
  ->login('sns@example.com', 'password')
  ->isStatusCode(302)

  ->post('/friend/link/id/6')
  ->checkCSRF()

  ->post('/friend/unlink/2')
  ->checkCSRF()

  ->get('/friend/unlink/2')
  ->click('はい', array())
  ->checkCSRF()
;
